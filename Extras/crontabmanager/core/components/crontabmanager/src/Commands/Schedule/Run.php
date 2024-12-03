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
use Webnitros\CronTabManager\Commands\Abstracts\AbstractCrontabCommand;

class Run extends AbstractCrontabCommand
{
    protected static $defaultName = 'schedule:run';
    protected static $defaultDescription = 'Run current tasks';

    public function handle(InputInterface $input, OutputInterface $output): int
    {
        $Artisan = $this->manager()->artisan();
        $rows = array();
        /* @var CronTabManagerTask $object */
        $q = $this->modx->newQuery('CronTabManagerTask');
        $q->where(array(
            'active' => 1,
        ));
        if ($objectList = $this->modx->getCollection('CronTabManagerTask', $q)) {
            foreach ($objectList as $object) {
                $Crontab = $object->crontab();
                $status = 'pending';
                if ($Crontab->isDue()) {
                    $status = 'running';
                    $cli = $object->getPath();
                    if (file_exists($cli)) {
                        $Artisan->shell($cli, $object->getFileLogPath());
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
}
