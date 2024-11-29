<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 29.11.2024
 * Time: 17:35
 */

namespace Webnitros\CronTabManager;


use CronTabManagerTask;

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

}
