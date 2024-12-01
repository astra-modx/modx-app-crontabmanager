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
        $rows = array();
        /* @var CronTabManagerTask $object */
        $q = $this->modx->newQuery('CronTabManagerTask');
        $q->where(array(
            'active' => 1,
        ));
        if ($objectList = $this->modx->getCollection('CronTabManagerTask', $q)) {
            foreach ($objectList as $object) {
                $path = $object->path_task;
                $Crontab = $object->crontab();
                $status = 'pending';
                if ($Crontab->isDue()) {
                    $status = 'running';
                    $description = $object->description;

                    $comment = !empty($description) ? mb_strimwidth($description, 0, 50, "...") : '';
                    if (!empty($comment)) {
                        $this->comment($comment);
                    }
                    $cli = $object->getPath();
                    if (file_exists($cli)) {
                        $log = $object->getFileLogPath();
                        shell_exec('php '.$cli.' > '.$log.' 2>&1 &');
                    }
                }

                $rows[] = [
                    'command' => $Crontab->command(),
                    'crontab' => str_pad($Crontab->time(), 10, ' ', STR_PAD_RIGHT),
                    'Status' => $status,
                    'Next run' => $Crontab->nextRunHuman(),
                ];
            }
        }

        // Данные таблицы
        $headers = ['Command', 'Crontab', 'Status', 'Next run'];

        // Вывод таблицы
        $this->style()->table($headers, $rows);


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
