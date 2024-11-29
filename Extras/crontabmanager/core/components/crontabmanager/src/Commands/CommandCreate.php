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
            ->setDescription('Создаёт новый контроллер.')
            ->addArgument('name', InputArgument::REQUIRED, 'Введите название контроллера'); // Добавление аргумента
    }

    public function handle(InputInterface $input, OutputInterface $output): int
    {
        // Вывод таблицы
        # $this->style()->table($headers, $rows);

        // Получение значения аргумента
        $controller = $input->getArgument('name');

        if (strripos($controller, 'CrontabController') !== false) {
            $this->style()->error('Название контроллера Не должно начинаться с "CrontabController"');

            return Command::FAILURE;
        }

        $controllerName = 'CrontabController'.ucfirst($controller);

        // Проверка, что имя контроллера состоит только из латинских символов
        if (!preg_match('/^CrontabController[a-zA-Z0-9]*$/', $controllerName)) {
            $output->writeln('<error>Название контроллера должно начинаться с "CrontabController" и состоять только из латинских символов.</error>');

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

        $content = $this->template($controllerName); // Вызов шаблона
        $data = $this->modx->getCacheManager()->writeFile($filePath, $content);

        if (!file_exists($filePath)) {
            $this->style()->error('Не удалось создать файл '.$filePath);

            return Command::FAILURE;
        }

        // Ваше логика для обработки названия контроллера
        $output->writeln('Создание контроллера с названием: '.$controllerName);

        #sleep(60);
        return self::SUCCESS;
    }


    public function template(string $controllerName)
    {
        // Шаблон кода контроллера
        $controllerCode = <<<PHP
<?php
/**
 * New Command
 */
class $controllerName extends modCrontabController
{
    public function process()
    {
        \$this->info("Задание выполнено");
    }
}
PHP;

        return $controllerCode;
    }

}
