<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 29.11.2024
 * Time: 20:04
 */

namespace Webnitros\CronTabManager\Artisan;


use SchedulerService;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Webnitros\CronTabManager\Commands\TaskRun;
use Webnitros\CronTabManager\Task;


class Builder
{
    private SchedulerService $scheduler;
    private Application $application;

    private ?string $command;
    private array $args;

    public function __construct(SchedulerService $scheduler, array $args)
    {
        $this->scheduler = $scheduler;
        $this->application = new Application('crontabmanager', $this->scheduler->version());

        $this->command = @trim((string)$args[1]);
        $this->args = $args;
    }

    public function application(): Application
    {
        return $this->application;
    }


    /*  public function generateCronLink()
      {
          $this->scheduler->generateCronLink();
      }*/


    public function commands()
    {
        $schedules = [
            \Webnitros\CronTabManager\Commands\Schedule\ScheduleList::class,
            \Webnitros\CronTabManager\Commands\Schedule\Work::class,
            \Webnitros\CronTabManager\Commands\Schedule\Run::class,
            \Webnitros\CronTabManager\Commands\CommandCreate::class,
            \Webnitros\CronTabManager\Commands\CrontabAdd::class,
        ];


        foreach ($schedules as $schedule) {
            $List = new $schedule();
            $List->initModx($this->scheduler->modx);
            $this->application->add($List);
        }


        $commands = [];

        $Task = new Task($this->scheduler);
        if ($tasks = $Task->items()) {
            ksort($tasks);
            foreach ($tasks as $signature => $description) {
                $commands[$signature] = $description;
            }
        }

        return $commands;
    }

    public function addContainer(string $command, array $row)
    {
        $desc = $row['description'];
        $alias = $row['controller'];
        // первое слово перед пробелом
        $args = explode(' ', $command, 2);
        $command = array_shift($args);

        $Command = new TaskRun($command);
        $Command->setDescription($desc);

        if ($alias != $command) {
            $Command->setAliases([$alias]);
        }

        $Command->addArgument('d', InputArgument::OPTIONAL, 'Mode development');
        $Command->addArgument('dev-browser', InputArgument::OPTIONAL, 'Style for browser');
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
            foreach ($commands as $key => $row) {
                $this->addContainer($key, $row);
            }
        }

        $command = $this->command;

        /* if (empty($command) || $command == 'list') {
             $this->generateCronLink();
         }*/

        $application = $this->application();

        $name = 'list';

        if (!empty($command) && $name != $command && $application->has($command)) {
            $command = $application->get($command);
            $name = $command->getName();
        }

        if ($name != 'list') {
            $application->setDefaultCommand($name, true);
        }

        $arguments = ($args = $this->arguments()) ? new ArrayInput($args) : new ArrayInput([]);

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


                preg_match('/"(.*?)"/', $value, $matches);
                if (!empty($matches[1])) {
                    $value = $matches[1];
                }

                $options[$key] = is_null($value) ? '' : $value;
            }
        }

        return $options;
    }
}
