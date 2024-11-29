<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 23.04.2023
 * Time: 10:34
 */

namespace Webnitros\CronTabManager\Analyzer;


use CronTabManagerTask;
use CronTabManagerTaskLog;
use PDO;
use Webnitros\CronTabManager\Event\Subscription;

class LogAnalyzer
{

    //private $eventSubscription;

    public $last;
    public $previous;
    public $previous_before;
    /**
     * @var true
     */
    public $check = false;
    private $logs;

    public $types = [
        'successful' => false, // Отправка уведомлений после каждого успешного запуска
        'successful_after_failed' => false, // Уведомлять товально о первом успешном запуске после провального
        'fails' => false, // Уведомлять товально о провальном запуске
        'fails_after_successful' => false, // Уведомлять только о первом провальном запуске после успешного
        'fails_new_problem' => false, // Уведомлять только о первом провальном запуске после провального
    ];

    public function __construct(CronTabManagerTask $task)
    {


        $createNotice = true;
        // Проверяем разрешение отправки уведомлений
        /*
        $task_id = $task->get('id');
        $max_number_attempts = $task->get('max_number_attempts');
        $end_run = strtotime($task->get('end_run'));
        $count = 0;
        $notification_enable = $task->get('notification_enable');
        if ($notification_enable) {
            // Если задание завершилось то меняется  end_run
            if ($max_number_attempts > 0) {

                // Если задание превысело максимальное количество попыток то отправляем сообщение администратору
                $criteria_notifications = array(
                    'task_id' => $task_id,
                    'end_run' => $end_run,
                    'completed' => 0,
                    'notification' => 0,
                );


                $count = $task->xpdo->getCount('CronTabManagerTaskLog', $criteria_notifications);
                if ($max_number_attempts <= $count) {
                    $createNotice = true;
                }
                if ($count === 0) {
                    $createNotice = true;
                }
            }
        }

            if ($count !== 0) {
                // Сохраняем только если перед этим были найдены логи
                // Автопауза устанавливается чтобы не работал механиз по критериям
                $add_sql = '';
                if (!empty($end_run)) {
                    $add_sql = "end_run = {$end_run} and ";
                }
                $sql = "UPDATE {$task->xpdo->getTableName('CronTabManagerTaskLog')} SET `notification` = '1', `auto_pause` = '1' WHERE {$add_sql}notification = 0 and completed = 0;";
                $task->xpdo->exec($sql);
            }
        */

        #echo 'Create Notice ' . $createNotice;

        if ($createNotice) {
            $logs = [];

            $q = $task->xpdo->newQuery('CronTabManagerTaskLog');
            $q->select($task->xpdo->getSelectColumns('CronTabManagerTaskLog', 'CronTabManagerTaskLog'));
            $q->where(array(
                'ignore_action' => false, // Игнорировать логи с такой пометкой
                'task_id' => $task->get('id'),
            ));
            $q->sortby('id', 'DESC');
            $q->limit(3);
            if ($q->prepare() && $q->stmt->execute()) {
                while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $logs[] = $row;
                }
            }

            if (!empty($logs)) {
                $this->check = true;

                $this->logs = $logs;

                if (!empty($logs)) {
                    $this->last = $logs[0];
                    $this->last['status'] = $this->last['completed'] == 1 ? 'success' : 'failed';
                }

                if (count($logs) > 1) {
                    $this->previous = $logs[1];
                    $this->previous['status'] = $this->previous['completed'] == 1 ? 'success' : 'failed';
                }

                if (count($logs) > 2) {
                    $this->previous_before = $logs[2];
                    $this->previous_before['status'] = $this->previous_before['completed'] == 1 ? 'success' : 'failed';
                }
            }


        }
    }


    public function process()
    {
        if ($this->check) {
            if ($this->last['status'] === 'failed') {
                $this->add('fails');
                if ($this->previous && $this->previous['status'] === 'success') {
                    $this->add('fails_after_successful');
                }
                if (!$this->previous_before || $this->previous_before['status'] === 'success') {
                    $this->add('fails_new_problem');
                }
            } else {
                $this->add('successful');
                if ($this->previous && $this->previous['status'] === 'failed') {
                    $this->add('successful_after_failed');
                }
            }
        }
        return $this->types;
    }

    public function get($type)
    {
        return (bool)$this->types[$type];
    }

    private function add(string $string)
    {
        $this->types[$string] = true;
        /*if ($this->types[$string]) {
            switch ($string) {
                case 'successful':
                    $this->eventSubscription->notify('successful', $this->last);
                    break;
                case 'successful_after_failed':
                    $this->eventSubscription->notify('successful_after_failed', $this->last);
                    break;
                case 'fails':
                    $this->eventSubscription->notify('fails', $this->last);
                    break;
                case 'fails_after_successful':
                    $this->eventSubscription->notify('fails_after_successful', $this->last);
                    break;
                case 'fails_new_problem':
                    $this->eventSubscription->notify('fails_new_problem', $this->last);
                    break;
            }
        }*/
    }

    public function getLastLog()
    {
        return $this->last;
    }

}
