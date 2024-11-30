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
    public $countFailedAttempts = 0;
    /* @var CronTabManagerTask $task */
    public $task;

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
        $this->task = $task;

    }

    public function run()
    {

        $this->reCountFailedAttempts();

        $this->failedAttemptsController();

        $this->buildLogs();
    }


    /**
     * Венет количество неудачных попыток
     * @return int
     */
    public function buildLogs()
    {
        $logs = [];
        $task = $this->task;
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

    /**
     * Венет количество неудачных попыток
     * @return int
     */
    public function getCountFailedAttempts()
    {
        return $this->countFailedAttempts;
    }

    /**
     * Механизм отправки уведомлений в случае неудачного выполнения задания
     * - Проверяет количество попыток выполнения задания
     * - Если превышено количество попыток то отправляет уведомление администратору
     * - Если не превышено то игнорирует и ставим метку у этого лога что он игнорируется, чтобы следующие логи не учитывались
     * - вернет true если все в прядке
     * @return bool
     */
    public function failedAttemptsController()
    {
        // Проверяем что включен механизм проверки количества попыток
        if ($this->task->get('notification_enable')) {
            $max_number_attempts = $this->task->get('max_number_attempts');
            // Если задание завершилось то меняется  end_run
            if ($max_number_attempts > 0) {
                // Записываем количество неудачных попыток
                // Для отслеживания когда слелать рестарт

                $count = $this->getCountFailedAttempts();
                ctma_debug('Max: ' . $max_number_attempts);
                ctma_debug('Current: ' . $count);

                if ($max_number_attempts >= $count) {
                    ctma_debug('Превышено количество попыток');
                    $end_run = strtotime($this->task->get('end_run'));
                    $sql = "UPDATE {$this->task->xpdo->getTableName('CronTabManagerTaskLog')} SET `ignore_action` = '1' WHERE end_run = {$end_run} and completed = 0;";
                    $this->task->xpdo->exec($sql);
                } else {
                    ctma_debug('Количество попыток не превышено');
                }
            }
        }
    }


    // подсчет количества неудачных попыток
    public function reCountFailedAttempts()
    {
        $task = $this->task;

        // Подсчет количества неудачных попыток
        $task_id = $task->get('id');
        $end_run = strtotime($task->get('end_run'));

        // Если задание превысело максимальное количество попыток то отправляем сообщение администратору
        $criteria_notifications = array(
            'task_id' => $task_id,
            'end_run' => $end_run,
            'completed' => 0,
        );
        $this->countFailedAttempts = $task->xpdo->getCount('CronTabManagerTaskLog', $criteria_notifications);

    }

    public function process()
    {
        if ($this->check) {
            if ($this->last['status'] === 'failed') {
                $this->add('fails');
                if ($this->previous && $this->previous['status'] === 'success') {
                    $this->add('fails_after_successful');
                }

                if (count($this->logs) === 1) {
                    if (!$this->previous_before || $this->previous_before['status'] === 'success') {
                        $this->add('fails_new_problem');
                    }
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
    }

    public function getLastLog()
    {
        return $this->last;
    }

    public function successfulAfterFailed()
    {
        return (bool)$this->types['successful_after_failed'];
    }

    public function successful()
    {
        return (bool)$this->types['successful'];
    }

}
