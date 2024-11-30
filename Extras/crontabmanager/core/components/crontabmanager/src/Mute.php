<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 26.04.2023
 * Time: 10:59
 */

namespace Webnitros\CronTabManager;


use CronTabManagerTask;
use Webnitros\CronTabManager\Analyzer\LogAnalyzer;

class Mute
{

    /**
     * Упрпавление заглушкой задания для автоматического восстановления после провала
     * @param \Webnitros\CronTabManager\Analyzer\LogAnalyzer $LogAnalyzer
     * @param \CronTabManagerTask $task
     * @return bool true - если задание заглушено
     */
    public static function check(LogAnalyzer $LogAnalyzer, CronTabManagerTask $task)
    {

        // Первый успешное завершения после провала
        $successfulAfterFailed = $LogAnalyzer->successfulAfterFailed();
        $successful = $LogAnalyzer->successful();
        $mute_success = $task->get('mute_success');

        if ($task->get('mute')) {

            // Заглушка задания до первого успешного завершения
            if ($mute_success) {
                if ($successfulAfterFailed && $successful) {
                    // Если задание было загрушено, и произошло первое успешное завершение после провала
                    // То снимаем метку заглушки
                    $task->muteOff();
                }
            }


        }
        if ($task->get('mute')) {

            // Заглушка задания на определенное время, по наступлению этого времени загрулшка снимается
            $mute_time = $task->get('mute_time');
            if ($mute_time) {
                $mute_time = strtotime($mute_time);
                if ($mute_time < time()) {
                    $task->muteOff();
                }
            }

        }


        return (bool)$task->get('mute');
    }
}
