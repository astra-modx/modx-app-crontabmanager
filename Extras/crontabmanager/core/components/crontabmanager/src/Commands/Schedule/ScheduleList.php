<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 08.08.2023
 * Time: 17:10
 */

namespace Webnitros\CronTabManager\Commands\Schedule;

use CronTabManager;
use CronTabManagerTask;
use DateTime;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Cron\CronExpression;
use Webnitros\CronTabManager\Artisan\Builder;
use Webnitros\CronTabManager\Commands\Abstracts\AbstractCrontabCommand;
use Webnitros\CronTabManager\Crontab;
use Webnitros\CronTabManager\Helpers\Convert;

class ScheduleList extends AbstractCrontabCommand
{
    protected static $defaultName = 'schedule:list';
    protected static $defaultDescription = 'List scheduled tasks';

    public function handle(InputInterface $input, OutputInterface $output): int
    {
        $Convert = new Convert();
        $Crontab = new Crontab();

        /* @var CronTabManagerTask $object */
        $q = $this->modx->newQuery('CronTabManagerTask');
        if ($objectList = $this->modx->getCollection('CronTabManagerTask', $q)) {
            foreach ($objectList as $object) {
                $description = $object->description;
                $time = $Crontab->cronTime($object);

                $cron = new CronExpression($time);
                $NextRun = $cron->getNextRunDate();

                $next_run_human = $Crontab->diff($this->modx, $cron);
                $rows[] = [
                    'command' => $Convert->command($object->path_task),
                    'active' => $object->active ? 'Yes' : 'No',
                    'crontab' => $time,
                    'nextRun' => $NextRun->format('Y-m-d H:i:s'),
                    'nextRunHuman' => $next_run_human,
                    'comment' => !empty($description) ? mb_strimwidth($description, 0, 25, "...") : '---',
                ];
            }
        }

        // Данные таблицы
        $headers = ['Path', 'Active', 'Crontab', 'Next run', 'Diff', 'Comment'];


        // Вывод таблицы
        $this->style()->table($headers, $rows);


        #sleep(60);
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
