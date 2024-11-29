<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 29.11.2024
 * Time: 20:04
 */

namespace Webnitros\CronTabManager\Artisan;


use ReflectionClass;
use SchedulerService;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Webnitros\CronTabManager\Commands\TaskRun;
use Webnitros\CronTabManager\Helpers\Convert;

class Builder
{
    private SchedulerService $scheduler;
    private Application $application;
    private string $controller_path;
    private ?string $command;
    private array $args;

    public function __construct(SchedulerService $scheduler, array $args)
    {
        $this->scheduler = $scheduler;
        $this->application = new Application('crontabmanager', $this->scheduler->version());
        $this->controller_path = MODX_CORE_PATH.'scheduler/Controllers';
        $this->command = @trim((string)$args[1]);
        $this->args = $args;
    }

    public function application(): Application
    {
        return $this->application;
    }


    public function files($directory)
    {
        $files = glob($directory.'/*.php');
        foreach (glob($directory.'/*', GLOB_ONLYDIR) as $subdirectory) {
            $files = array_merge($files, $this->files($subdirectory));
        }

        return $files;
    }


    public function generateCronLink()
    {
        $this->scheduler->generateCronLink();
    }

    public function tasks()
    {
        $Convert = new Convert();
        $controllers = null;
        $files = $this->files($this->controller_path);
        foreach ($files as $file) {
            if (strripos($file, 'default.php') !== false) {
                continue;
            }
            $controllers[] = $file;
        }

        $commands = [];
        if ($controllers) {
            foreach ($controllers as $controller) {
                require_once $controller;
                $basename = str_ireplace($this->controller_path.'/', '', $controller);
                $basename = rtrim($basename, '.php');
                $class = 'CrontabController'.$basename;
                $class = str_ireplace('/', '', $class);
                $reflectionClass = new ReflectionClass($class);
                if ($reflectionClass->hasProperty('description')) {
                    $property = $reflectionClass->getProperty('description');
                    $property->setAccessible(true);
                    $comment = $property->getValue(new $class($this->scheduler->modx));
                } else {
                    $comment = $reflectionClass->getDocComment();
                    if (!empty($comment)) {
                        $comment = str_ireplace('/**', '', $comment);
                        $comment = str_ireplace('*/', '', $comment);
                        $comment = str_ireplace('*', '', $comment);
                        $comment = trim($comment);
                    } else {
                        $comment = '-';
                    }
                }

                $command = $Convert->command($basename);
                $signature = mb_strtolower($command);
                if ($reflectionClass->hasProperty('signature')) {
                    $property = $reflectionClass->getProperty('signature');
                    $property->setAccessible(true);
                    $signature = $property->getValue(new $class($this->scheduler->modx));
                }

                $commands[$signature] = $comment;
            }
        }

        return $commands;
    }

    public function commands()
    {
        $schedules = [
            \Webnitros\CronTabManager\Commands\Schedule\ScheduleList::class,
            \Webnitros\CronTabManager\Commands\Schedule\Work::class,
            \Webnitros\CronTabManager\Commands\Schedule\Run::class,
            \Webnitros\CronTabManager\Commands\CommandCreate::class,
        ];


        foreach ($schedules as $schedule) {
            $List = new $schedule();
            $List->initModx($this->scheduler->modx);
            $this->application->add($List);
        }


        $commands = [];
        if ($tasks = $this->tasks()) {
            ksort($tasks);
            foreach ($tasks as $signature => $description) {
                $commands[$signature] = $description;
            }
        }

        return $commands;
    }

    public function addContainer(string $command, string $desc)
    {
        // первое слово перед пробелом
        $args = explode(' ', $command, 2);
        $command = array_shift($args);

        $Command = new TaskRun($command);
        $Command->setDescription($desc);

        $Command->addArgument('d', InputArgument::OPTIONAL, 'Mode development');
        foreach ($args as $arg) {
            preg_match('/\{--(.*?)\}/', $arg, $matches);
            if (!empty($matches)) {
                $key = $matches[1];
                $Command->addArgument($key, InputArgument::OPTIONAL);
            }
        }
        $this->application->add($Command);
    }


    public function run()
    {
        if ($commands = $this->commands()) {
            foreach ($commands as $key => $desc) {
                $this->addContainer($key, $desc);
            }
        }

        $command = $this->command;

        if (empty($command) || $command == 'list') {
            $this->generateCronLink();
        }

        $application = $this->application();

        $name = 'list';
        if (!empty($command) && $name != $command && $application->has($command)) {
            $command = $application->get($command);
            $name = $command->getName();
        }


        if ($name != 'list') {
            $application->setDefaultCommand($name, true);
        }

        $arguments = ($args = $this->arguments()) ? new ArrayInput($args) : null;
        $application->run($arguments);
    }

    public function arguments()
    {
        $arguments = $this->args;
        $options = null;
        foreach (array_slice($arguments, 2) as $argument) {
            if (strpos($argument, '--') === 0) {
                // Убираем "--" и разделяем на ключ и значение
                [$key, $value] = explode('=', substr($argument, 2), 2);
                $options[$key] = $value;
            }
        }

        return $options;
    }
}
