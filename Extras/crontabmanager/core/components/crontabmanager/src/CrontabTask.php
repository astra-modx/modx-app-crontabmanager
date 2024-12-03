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

class CrontabTask
{

    private CronTabManagerTask $task;
    private CronExpression $expression;

    public function __construct(CronTabManagerTask $task)
    {
        $this->task = $task;
        $this->expression = new CronExpression($this->time());
    }

    public function expression()
    {
        return $this->expression;
    }

    public function time($separator = ' ')
    {
        $time = array(
            $this->eiEmpt($this->task->get('minutes')),
            $this->eiEmpt($this->task->get('hours')),
            $this->eiEmpt($this->task->get('days')),
            $this->eiEmpt($this->task->get('months')),
            $this->eiEmpt($this->task->get('weeks')),
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

    public function getNextRunDate()
    {
        return $this->expression()->getNextRunDate();
    }

    public function getNextRunDateFormat(string $format = 'Y-m-d H:i:s')
    {
        return $this->getNextRunDate()->format($format);
    }

    public function isDue()
    {
        return $this->expression()->isDue();
    }

    public function nextRunHuman()
    {
        return $this->interval();
    }

    public function interval()
    {
        $cron = $this->expression();
        $modx = $this->task->xpdo;

        // Убедимся, что $NextRun — это объект DateTime
        $NextRun = $cron->getNextRunDate();

        // Если $NextRun — строка, преобразуем в объект DateTime
        if (is_string($NextRun)) {
            $NextRun = new DateTime($NextRun);
        }

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

    public function command(string $path = null)
    {
        $path = $path ?? $this->task->get('path_task');

        if (strripos($path, '.') !== false) {
            $path = strstr($path, '.', true);
        }

        $command = str_ireplace('/', ':', $path);
        $pattern = "/\r?\n/";
        $replacement = "";
        $command = preg_replace($pattern, $replacement, $command);

        return mb_strtolower($command);
    }


    public function manager()
    {
        if ($Crontab = $this->task->loadCronTabManager()) {
            if ($manager = $Crontab->loadManager()) {
                return $manager;
            }
        }

        return null;
    }

    /**
     * Записать крон задание
     * @return bool
     */
    public function addCron()
    {
        if ($manager = $this->manager()) {
            return $manager->process($this->task, 'add');
        }

        return false;
    }


    /**
     * Удалить крон задание
     * @return bool
     */
    public function removeCron()
    {
        if ($manager = $this->manager()) {
            return $manager->process($this->task, 'remove');
        }

        return false;
    }

    /**
     * Вернет хеш задания
     * @return bool|string
     */
    public function findCron()
    {
        if ($manager = $this->manager()) {
            return $manager->findHashTask($this->task->get('path_task'), $this->task->get('id'));
        }

        return false;
    }


}
