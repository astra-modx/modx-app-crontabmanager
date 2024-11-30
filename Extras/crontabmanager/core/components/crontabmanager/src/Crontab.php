<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 29.11.2024
 * Time: 17:35
 */

namespace Webnitros\CronTabManager;


use Cron\CronExpression;
use CronTabManagerTask;
use DateTime;

class Crontab
{

    /**
     * Проверка доступности crontab
     * @return bool
     */
    public static function isAvailable()
    {
        exec('crontab -l', $output, $returnVar);

        return $returnVar === 0;
    }


    public function expression(CronTabManagerTask $task, $separator = ' ')
    {
        $CronExpression = new CronExpression($this->cronTime($task, $separator));

        return $CronExpression;
    }

    public function cronTime(CronTabManagerTask $task, $separator = ' ')
    {
        $time = array(
            $this->eiEmpt($task->get('minutes')),
            $this->eiEmpt($task->get('hours')),
            $this->eiEmpt($task->get('days')),
            $this->eiEmpt($task->get('days')),
            $this->eiEmpt($task->get('weeks')),
        );

        return implode($separator, $time);
    }


    public function eiEmpt($val)
    {
        if (is_numeric($val)) {
            return $val;
        }

        return empty($val) ? '*' : $val;
    }

    public function diff(\modX $modx, CronExpression $cron)
    {
        $NextRun = $cron->getNextRunDate();
        $currentDate = new DateTime();
        $interval = $currentDate->diff($NextRun);


        // Человеко-понятный вывод
        if ($interval->d >= 1) {
            // Если больше суток, выводим дни
            $next_run_human = $modx->lexicon('crontabmanager_next_run_human_days', ['days' => $interval->d]);
        } elseif ($interval->h >= 1) {
            // Если меньше суток, но больше часа, выводим в часах
            $next_run_human = $modx->lexicon('crontabmanager_next_run_human_hours', ['hours' => $interval->h]);
        } elseif ($interval->i >= 1) {
            // Если меньше часа, но больше минуты, выводим в минутах
            $next_run_human = $modx->lexicon('crontabmanager_next_run_human_minutes', ['minutes' => $interval->i]);
        } else {
            // Если меньше минуты, выводим в секундах
            $next_run_human = $modx->lexicon('crontabmanager_next_run_human_seconds', ['seconds' => $interval->s]);
        }

        return $next_run_human;
    }

}
