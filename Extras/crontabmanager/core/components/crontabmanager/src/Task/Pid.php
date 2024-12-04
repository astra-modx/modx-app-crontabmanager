<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 04.12.2024
 * Time: 13:01
 */

namespace Webnitros\CronTabManager\Task;


use CronTabManagerTask;

class Pid
{
    private CronTabManagerTask $task;

    public function __construct(CronTabManagerTask $task)
    {
        $this->task = $task;
    }


    public function id()
    {
        return $this->task->get('pid') ?? null;
    }

    public function isLock()
    {
        return $this->status() !== 'completed';
    }

    public function kill()
    {
        $pid = $this->id();
        // Выполняем команду kill через shell_exec
        $output = shell_exec("kill -9 $pid 2>&1");

        // Проверяем результат выполнения
        if (strpos($output, 'No such process') !== false) {
            return $this->task->xpdo->lexicon('crontabmanager_task_pid_kill_error', [
                'pid' => $pid,
            ]);
        }

        return true;
    }

    public function get()
    {
        // find command field command
        if (!$pid = $this->id()) {
            return null;
        }

        $command = 'ps -o pid,comm,stat | grep '.$pid;
        $output = shell_exec($command);

        // Разбиваем вывод по строкам
        if (!empty($output)) {
            $lines = explode("\n", trim($output));

            if (!empty($lines)) {
                $line = $lines[0];
                {
                    // Разбиваем строку на компоненты по пробелам
                    $fields = preg_split('/\s+/', $line);

                    // Если строка содержит 3 поля (PID, COMMAND, STAT), то добавляем их в массив
                    if (count($fields) === 3) {
                        return [
                            'pid' => $fields[0],
                            'comm' => $fields[1],
                            'stat' => $fields[2],
                        ];
                    }
                }
            }
        }

        return null;
    }

    /**
     * состояние процесса. Например, R - выполняется, S - ожидает ввода-вывода, Z - зомби-процесс и т.д.
     * @return string
     */
    public function status()
    {
        if ($info = $this->get()) {
            switch ($info['stat']) {
                case 'R':
                case 'S':
                    return 'running';
                    break;
                case 'Z':
                    return 'zombie';
                    break;
                default:
                    break;
            }
        }

        return 'completed';
    }
}
