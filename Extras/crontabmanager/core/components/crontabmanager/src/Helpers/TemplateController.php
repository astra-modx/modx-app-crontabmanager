<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 03.12.2024
 * Time: 16:01
 */

namespace Webnitros\CronTabManager\Helpers;


use CronTabManager;

class TemplateController
{
    private CronTabManager $cronTabManager;

    public function __construct(\modX $modX)
    {
        $this->cronTabManager = $modX->getService('crontabmanager', 'CronTabManager', MODX_CORE_PATH.'components/crontabmanager/model/');
    }


    public function fileExists(string $path_task)
    {
        $schedulerPath = $this->cronTabManager->option('schedulerPath');
        $controller = $schedulerPath.'/Controllers/'.$path_task;

        return file_exists($controller);
    }

    public function process(string $controller)
    {
        $value = str_ireplace('/', ' ', $controller);
        $value = ucwords($value);
        $value = str_ireplace(' ', '', $value);
        $controllerName = 'CrontabController'.$value;

        $info = pathinfo($controller);

        // Получаем расширение файла
        $extension = $info['extension'];

// Вырезать расширение из названия
        $controllerName = str_replace('.'.$extension, '', $controllerName);

        // Проверка, что имя контроллера состоит только из латинских символов
        if (!preg_match('/^CrontabController[a-zA-Z0-9]*$/', $controllerName)) {
            return 'The controller name must start with "CrontabController" and consist of Latin characters only.';
        }


        // Извлекаем путь к директории
        $directory = $info['dirname'];

        // Получаем имя файла без расширения
        $filenameWithoutExtension = $info['filename'];

        // Получаем путь к файлу без расширения, сохраняя директорию
        $fileName = $directory.'/'.$filenameWithoutExtension.'.php';


        $basePath = $this->cronTabManager->scheduler()->getOption('basePath');
        // Полный путь к файлу
        $filePath = $basePath.$fileName;


        if (file_exists($filePath)) {
            return 'Файл с таким именем уже существует: '.$filePath;
        }

        $sig = strtolower($controller);
        $sig = str_ireplace('/', ':', $sig);
        $content = $this->template($sig, $controllerName);
        $this->cronTabManager->modx->getCacheManager()->writeFile($filePath, $content);
        if (!file_exists($filePath)) {
            return 'Failed to create file '.$filePath;
        }

        $this->controllerName = $controllerName;
        $this->filePath = $filePath;
        $this->sig = $sig;

        return true;
    }


    private ?string $controllerName = null;
    private ?string $filePath = null;
    private ?string $sig = null;

    public function getControllerName()
    {
        return $this->controllerName;
    }

    public function getFilePath()
    {
        return $this->filePath;
    }

    public function getSig()
    {
        return $this->sig;
    }

    public function template(string $sig, string $controllerName)
    {
        $controllerCode = <<<PHP
<?php

/**
 * New Command "php artisan $sig --arg_name=water"
 */
class $controllerName extends modCrontabController
{
    protected \$signature = '$sig {--name}'; // no required arguments
    
    public function process()
    {
        \$name = \$this->getArgument('name', 'world');
        \$this->info('Hello: '.\$name);
    }
}
PHP;

        return $controllerCode;
    }
}
