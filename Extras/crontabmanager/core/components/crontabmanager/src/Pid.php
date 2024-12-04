<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 10.04.2023
 * Time: 09:10
 */

namespace Webnitros\CronTabManager;


class Pid
{
    public static function pids()
    {
        $processes = null;
        $user = get_current_user();
        $command = "ps aux | grep {$user} |grep artisan | grep -v grep";

        #$command = 'ps -o pid,comm,stat | grep '.$pid;

        $output = shell_exec($command);
        if (!empty($output)) {
            $arrays = explode(PHP_EOL, $output);
            $arrays = array_filter($arrays);

            if (is_array($arrays) && count($arrays) > 0) {
                foreach ($arrays as $array) {
                    list($pid, $user, $time, $bin, $artisan, $command, $args) = preg_split('/\s+/', $array);

                    if (strripos($bin, 'php') === false) {
                        continue;
                    }
                    if (strripos($artisan, 'artisan') === false) {
                        continue;
                    }

                    if (empty($command)) {
                        continue;
                    }
                    $processes[] = [
                        'user' => $user, // имя пользователя, от имени которого запущен процесс
                        'pid' => $pid, // идентификатор процесса (PID)
                        #'cpu' => $cpu, // процент использования CPU процессом
                        #'mem' => $mem, // процент использования памяти процессом
                        #'vsz' => $vsz, // виртуальный размер процесса в килобайтах (KiB)
                        #'rss' => $rss, // размер сегмента данных процесса в килобайтах (KiB)
                        #'tty' => $tty, // управляющий терминал процесса
                        #'stat' => $stat, // состояние процесса (например, R - выполняется, S - ожидает ввода-вывода, Z - зомби-процесс и т.д.)
                        #'start' => $start, // время запуска процесса
                        'time' => $time, // общее время использования процессом CPU в минутах и секундах
                        'command' => $command, // имя команды или исполняемого файла процесса
                        'args' => $args, // полный вывод строки процесса, содержащей все перечисленные выше атрибуты
                    ];
                }
            }
        }

        return $processes;
    }


}
