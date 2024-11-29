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
        global $modx;

        $commandPhp = CronTabManagerPhpExecutable($modx);
        $path = MODX_CORE_PATH . 'scheduler/ControllersLinks'; // путь к папке с контроллерами
        $output = shell_exec("ps aux | grep $user | grep $commandPhp | grep $path | grep '\.php$'");
        if (!empty($output)) {
            $arrays = explode(PHP_EOL, $output);
            $arrays = array_filter($arrays);

            if (is_array($arrays) && count($arrays) > 0) {

                foreach ($arrays as $array) {
                    list($user, $pid, $cpu, $mem, $vsz, $rss, $tty, $stat, $start, $time, $command, $process) = preg_split('/\s+/', $array);
                    if ($command !== $commandPhp) {
                        continue;
                    }
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
            }
        }

        return $processes;
    }

    /**
     * состояние процесса. Например, R - выполняется, S - ожидает ввода-вывода, Z - зомби-процесс и т.д.
     * @param string $path
     * @return string
     */
    public static function status(string $path)
    {
        $pid = Pid::getPid($path);
        switch ($pid['stat']) {
            case 'R+':
            case 'S+':
                return 'executed';
                break;
            case 'Z+':
                return 'zombie';
                break;
            default:
                break;
        }
        return 'completed';
    }

    public static function getPid(string $process)
    {
        if ($processes = Pid::pids()) {
            foreach ($processes as $pid) {
                if (strpos($pid['process'], $process) !== false) {
                    return $pid;
                }
            }
        }
        return null;
    }
}
