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

class CommandCreate extends AbstractCrontabCommand
{
    protected static $defaultName = 'command:create';
    protected static $defaultDescription = 'Create crontab task';

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

        if (strripos($controller, 'CrontabController') !== false) {
            $this->style()->error('Controller name must not start with "CrontabController"');

            return Command::FAILURE;
        }

        $controllerName = 'CrontabController'.ucfirst($controller);

        // Проверка, что имя контроллера состоит только из латинских символов
        if (!preg_match('/^CrontabController[a-zA-Z0-9]*$/', $controllerName)) {
            $output->writeln('<error>The controller name must start with "CrontabController" and consist of Latin characters only.</error>');

            return Command::FAILURE;
        }


        // Преобразование имени в нижний регистр для файла
        $fileName = $controller.'.php';
        # $fileName = strtolower($controllerName).'.php';


        $basePath = $this->scheduler->getOption('basePath');
        // Полный путь к файлу
        $filePath = $basePath.$fileName;


        if (file_exists($filePath)) {
            $output->writeln('<error>Файл с таким именем уже существует: '.$filePath.'</error>');

            return Command::FAILURE;
        }
        $sig = strtolower($controller);
        $content = $this->template($sig, $controllerName);
        $this->modx->getCacheManager()->writeFile($filePath, $content);
        if (!file_exists($filePath)) {
            $this->style()->error('Failed to create file '.$filePath);

            return Command::FAILURE;
        }

        // Ваше логика для обработки названия контроллера
        $output->writeln('Create a controller with the name: '.$controllerName.' [command: php artisan '.$sig.' --d --name=water]');

        #sleep(60);
        return self::SUCCESS;
    }


    public function template(string $sig, string $controllerName)
    {
        $controllerCode = <<<PHP
<?php
/**
 * New Command "php artisan $sig --d --name=water"
 */
class $controllerName extends modCrontabController
{
    protected \$signature = '$sig {--name}'; // no required arguments
    public function process()
    {
        \$name = \$this->input()->getArgument('name') ?? 'no name';
        \$this->info('Hello: '.\$name);
    }
}
PHP;

        return $controllerCode;
    }

}
