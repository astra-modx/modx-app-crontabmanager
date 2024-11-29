<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 08.08.2023
 * Time: 17:10
 */

namespace Webnitros\CronTabManager\Commands\Schedule;

use CronTabManagerTask;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Cron\CronExpression;
use Webnitros\CronTabManager\Commands\Abstracts\AbstractCrontabCommand;
use Webnitros\CronTabManager\Crontab;

class Run extends AbstractCrontabCommand
{
    protected static $defaultName = 'schedule:run';
    protected static $defaultDescription = 'Run current tasks';

    public function handle(InputInterface $input, OutputInterface $output): int
    {
        $Crontab = new Crontab();
        /* @var CronTabManagerTask $object */
        $q = $this->modx->newQuery('CronTabManagerTask');
        $q->where(array(
            'active' => 1,
        ));
        if ($objectList = $this->modx->getCollection('CronTabManagerTask', $q)) {
            foreach ($objectList as $object) {
                $path = $object->path_task;
                $time = $Crontab->cronTime($object);
                $cron = new CronExpression($time);
                if ($cron->isDue()) {
                    $description = $object->description;

                    $comment = !empty($description) ? ' comment: '.mb_strimwidth($description, 0, 25, "...") : '';
                    $this->info('run: '.$path.$comment);
                    $cli = $object->getPath();
                    if (file_exists($cli)) {
                        $log = $object->getFileLogPath();
                        shell_exec('php '.$cli.' > '.$log.' 2>&1 &');
                    }
                }
            }
        }

        return self::SUCCESS;
    }

    public function runTask(string $path)
    {
        $path = str_ireplace('.php', '', $path);
        $path = rtrim($path, '.php');
        $this->scheduler->php($path);
        $this->scheduler->process();
    }

}
