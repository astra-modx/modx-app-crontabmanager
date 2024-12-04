<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 03.12.2024
 * Time: 12:04
 */

namespace Webnitros\CronTabManager;


use CronTabManager;
use CronTabManagerTask;

class ArtisanCall
{
    private CronTabManager $cronTabManager;

    public function __construct(CronTabManager $cronTabManager)
    {
        $this->cronTabManager = $cronTabManager;
    }

    public function callCommand(string $command, array $args = [])
    {
        $this->run($command, $args);
    }

    public function callPath(string $path, array $args = [])
    {
        $command = $this->command(pathinfo($path, PATHINFO_FILENAME));

        $this->callCommand($command, $args);
    }

    protected function run(string $command, array $args = [])
    {
        $command = $this->parseCommand($command);

        # $command = $this->parseCommand($command);
        $arrays = [
            0 => "core/scheduler/artisan",
            1 => $command,
        ];
        if (!empty($args)) {
            foreach ($args as $key => $value) {
                $arrays[] = $value;
            }
        }

        $Artisan = new \Webnitros\CronTabManager\Artisan\Builder($this->cronTabManager->scheduler(), $arrays);
        $Artisan->run();
    }

    protected function command(string $path = null)
    {
        if (strripos($path, '.') !== false) {
            $path = strstr($path, '.', true);
        }
        $command = str_ireplace('/', ':', $path);
        $pattern = "/\r?\n/";
        $replacement = "";
        $command = preg_replace($pattern, $replacement, $command);
        $command = mb_strtolower($command);

        return $command;
    }

    public function parseCommand(string $command)
    {
        list($command) = explode(' ', $command);

        return $command;
    }

    public function parseArgs(string $input): array
    {
        # $input = '--snippet="Crontab" --snippet2=" Crontab2" --name=dasdsad';
        preg_match_all('/--\S+?=".*?"|--\S+=\S+|--\S+/', $input, $matches);
        $args = $matches[0];

        return $args;
    }

    public function shellTask(CronTabManagerTask $task, $arg = null, bool $async = true)
    {
        // Проверка блокировки задания
        $this->cronTabManager->scheduler()->isLook($task);


        $ControllerPath = $this->cronTabManager->option('schedulerPath');
        $Artisan = $this->cronTabManager->artisan();
        $Crontab = $task->crontab();
        $command = $Crontab->command();

        if (!empty($arg)) {
            $arg = trim($arg);
            $command .= ' '.$arg;
        }

        $cli = $ControllerPath.'/artisan '.$command;
        $pid = $Artisan->shell($cli, $task->getFileLogPath(), $async);


        $table = $task->xpdo->getTableName('CronTabManagerTask');
        $task->xpdo->exec("UPDATE {$table} SET pid = '{$pid}'  WHERE id = '{$task->id}'");
    }

    public function shell(string $controller, $logFile = null, bool $async = true)
    {
        $logFile = mb_strtolower($logFile);
        $bin = CronTabManagerPhpExecutable($this->cronTabManager->modx);
        $command = $bin.' '.$controller.' > '.$logFile.' 2>&1';
        if ($async) {
            $command .= ' &';
        }

        $command .= ' echo $!';

        $pid = shell_exec($command);
        $pid = trim($pid);
        $pid = (int)$pid;

        return $pid;
    }

}
