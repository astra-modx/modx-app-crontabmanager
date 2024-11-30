<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 30.11.2024
 * Time: 10:47
 */

namespace Webnitros\CronTabManager;


use ReflectionClass;
use SchedulerService;
use Webnitros\CronTabManager\Helpers\Convert;

class Task
{
    private SchedulerService $scheduler;

    public function __construct(SchedulerService $scheduler)
    {
        $this->scheduler = $scheduler;
    }

    public function items()
    {
        $path = $this->scheduler->getOption('basePath');

        $Convert = new Convert();
        $controllers = null;
        $files = $this->files(rtrim($path, '/'));
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
                $file = str_ireplace($path, '', $controller);
                if (strripos($file, '.') !== false) {
                    $basename = strstr($file, '.', true);
                } else {
                    $basename = $file;
                }

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

                $controller = $Convert->command($basename);
                $signature = mb_strtolower($controller);
                if ($reflectionClass->hasProperty('signature')) {
                    $property = $reflectionClass->getProperty('signature');
                    $property->setAccessible(true);
                    $signature = $property->getValue(new $class($this->scheduler->modx));
                }

                $signature_command = explode(' ', $signature)[0];
                $commands[$signature] = [
                    'signature_command' => $signature_command,
                    'controller' => $controller,
                    'description' => $comment,
                    'file' => $file,
                ];
            }
        }

        return $commands;
    }


    public function files($directory)
    {
        $files = glob($directory.'/*.php');
        foreach (glob($directory.'/*', GLOB_ONLYDIR) as $subdirectory) {
            $files = array_merge($files, $this->files($subdirectory));
        }

        return $files;
    }

    public function get(string $command)
    {
        $command = trim($command);
        if ($items = $this->items()) {
            foreach ($items as $signature => $item) {
                if ($signature == $command || $item['signature_command'] === $command || $item['controller'] === $command) {
                    return $item['file'];
                }
            }
        }

        return null;
    }
}
