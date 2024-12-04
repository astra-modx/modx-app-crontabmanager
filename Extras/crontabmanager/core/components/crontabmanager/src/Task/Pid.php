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
        switch ($this->status()) {
            case 'completed':
            case 'did_not_start':
                return false;
            default:
                break;
        }

        return true;
    }

    public function kill()
    {
        $pid = $this->id();
        if (empty($pid)) {
            return false;
        }
        $command = "kill -9 $pid 2>&1";
        $output = shell_exec($command);

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

        if ($info = $this->_pid($pid)) {
            if (!empty($info['stat'])) {
                return $info;
            }
        }
        if ($info = $this->_pid2($pid)) {
            return $info;
        }

        return null;
    }


    private function _pid2(int $pid)
    {
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

    private function _pid(int $pid)
    {
        $processes = null;
        $command = "ps aux | grep {$pid} | grep -v grep";
        $output = shell_exec($command);
        if (!empty($output)) {
            $arrays = explode(PHP_EOL, $output);
            $arrays = array_filter($arrays);

            if (is_array($arrays) && count($arrays) > 0) {
                foreach ($arrays as $array) {
                    list($user, $pid, $cpu, $mem, $vsz, $rss, $tty, $stat, $start, $time, $command, $process) = preg_split('/\s+/', $array);
                    $processes[] = [
                        'user' => $user, // имя пользователя, от имени которого запущен процесс
                        'pid' => $pid, // идентификатор процесса (PID)
                        'cpu' => $cpu, // процент использования CPU процессом
                        'mem' => $mem, // процент использования памяти процессом
                        'vsz' => $vsz, // виртуальный размер процесса в килобайтах (KiB)
                        'rss' => $rss, // размер сегмента данных процесса в килобайтах (KiB)
                        'tty' => $tty, // управляющий терминал процесса
                        'stat' => $stat, // состояние процесса (например, R - выполняется, S - ожидает ввода-вывода, Z - зомби-процесс и т.д.)
                        'start' => $start, // время запуска процесса
                        'time' => $time, // общее время использования процессом CPU в минутах и секундах
                        'command' => $command, // имя команды или исполняемого файла процесса
                        'process' => $process, // полный вывод строки процесса, содержащей все перечисленные выше атрибуты
                    ];
                }

                if (!empty($processes)) {
                    return @$processes[0];
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
        if ($this->id() == 0) {
            return 'did_not_start';
        }
        if ($info = $this->get()) {
            switch ($info['stat']) {
                case 'R':
                case 'R+':
                case 'S':
                case 'S+':
                    return 'running';
                case 'Z':
                case 'Z+':
                    return 'zombie';
                default:
                    break;
            }
        }

        return 'completed';
    }

    public function update($pid = null)
    {
        $pid = $pid ?? getmypid();
        $table = $this->task->xpdo->getTableName('CronTabManagerTask');
        $this->task->xpdo->exec("UPDATE {$table} SET pid = '{$pid}'  WHERE id = '{$this->task->id}'");
    }
}
