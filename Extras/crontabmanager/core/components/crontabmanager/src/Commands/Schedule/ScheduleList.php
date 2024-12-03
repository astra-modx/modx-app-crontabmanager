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

class ScheduleList extends AbstractCrontabCommand
{
    protected static $defaultName = 'schedule:list';
    protected static $defaultDescription = 'List scheduled tasks';

    public function handle(InputInterface $input, OutputInterface $output): int
    {
        $rows = array();
        /* @var CronTabManagerTask $object */
        $q = $this->modx->newQuery('CronTabManagerTask');
        if ($objectList = $this->modx->getCollection('CronTabManagerTask', $q)) {
            foreach ($objectList as $object) {
                $description = $object->description;
                $Crontab = $object->crontab();
                $rows[] = [
                    'command' => $Crontab->command(),
                    'active' => $object->active ? 'Yes' : 'No',
                    'crontab' => $Crontab->time(),
                    'nextRun' => $Crontab->getNextRunDateFormat(),
                    'nextRunHuman' => $Crontab->nextRunHuman(),
                    'comment' => !empty($description) ? mb_strimwidth($description, 0, 25, "...") : '---',
                ];
            }
        }

        // Данные таблицы
        $headers = ['Command', 'Active', 'Crontab', 'Next run', 'Diff', 'Comment'];


        // Вывод таблицы
        $this->style()->table($headers, $rows);

        return self::SUCCESS;
    }

}
