<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 08.08.2023
 * Time: 17:10
 */

namespace Webnitros\CronTabManager\Commands;

use modCacheManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webnitros\CronTabManager\Commands\Abstracts\AbstractCrontabCommand;
use Webnitros\CronTabManager\Helpers\TemplateController;

class CommandCreate extends AbstractCrontabCommand
{
    protected static $defaultName = 'command:create';
    protected static $defaultDescription = 'Create command controller';

    protected function configure()
    {
        $this
            ->setDescription('Creates a new controller.')
            ->addArgument('name', InputArgument::REQUIRED, 'Enter the controller name'); // Добавление аргумента
    }

    public function handle(InputInterface $input, OutputInterface $output): int
    {
        // Вывод таблицы
        # $this->style()->table($headers, $rows);

        // Получение значения аргумента
        $controller = $input->getArgument('name');

        if (empty($controller)) {
            $this->style()->error('Set name of controller');

            return Command::FAILURE;
        }
        if (strripos($controller, 'CrontabController') !== false) {
            $this->style()->error('Controller name must not start with "CrontabController"');

            return Command::FAILURE;
        }

        $TemplateController = new TemplateController($this->modx);

        $response = $TemplateController->process($controller);
        if ($response !== true) {
            $this->style()->error($response);

            return Command::FAILURE;
        }

        $controllerName = $TemplateController->getControllerName();
        $filePath = $TemplateController->getFilePath();
        $sig = $TemplateController->getSig();
        $output->writeln('Create a controller with the name: '.$controllerName.' [command: php artisan '.$sig.' --arg_name=water]');
        $output->writeln('Path controller: '.$filePath);

        return self::SUCCESS;
    }


}
