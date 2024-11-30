<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 08.08.2023
 * Time: 17:10
 */

namespace Webnitros\CronTabManager\Commands;

use CronTabManagerCategory;
use CronTabManagerTask;
use modCacheManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webnitros\CronTabManager\Commands\Abstracts\AbstractCrontabCommand;
use Webnitros\CronTabManager\Task;

class CrontabAdd extends AbstractCrontabCommand
{
    protected static $defaultName = 'crontab:add';
    protected static $defaultDescription = 'Create crontab task';

    protected function configure()
    {
        $this
            ->setDescription('Creates a new task in crontab')
            ->addArgument('command', InputArgument::REQUIRED, 'Enter the command name'); // Добавление аргумента
    }

    public function handle(InputInterface $input, OutputInterface $output): int
    {
        // Получение значения аргумента
        $command = trim($input->getArgument('command'));
        if (empty($command)) {
            $this->style()->error('Set name of command');

            return Command::FAILURE;
        }

        if (!$ControllerPath = (new Task($this->scheduler))->get($command)) {
            $this->style()->error('Controller for command '.$command.' not found');
            return Command::FAILURE;
        }


        $categoryName = 'Commands';
        $criteria = [
            'path_task' => $ControllerPath,
        ];
        /* @var CronTabManagerTask $Task */
        if (!$Task = $this->modx->getObject('CronTabManagerTask', $criteria)) {
            /* @var CronTabManagerCategory $Category */
            if (!$Category = $this->modx->getObject('CronTabManagerCategory', ['name' => $categoryName])) {
                /* @var CronTabManagerCategory $Category */
                $Category = $this->modx->newObject('CronTabManagerCategory');
                $Category->set('name', $categoryName);
                $Category->set('description', 'Command default category');
                $Category->save();
            }


            $Task = $this->modx->newObject('CronTabManagerTask');
            $Task->set('parent', $Category->get('id'));
            $Task->set('path_task', $ControllerPath);
            $Task->set('minutes', 1);
            $Task->set('hours', 1);
            $Task->save();

            $this->info('Task create. Manual from admin panel: [php artisan schedule:list] ['.$Task->cronTime().']');
        } else {
            $this->info('Task already exists. Manual from admin panel: [php artisan schedule:list] ['.$Task->cronTime().']');
        }

        return self::SUCCESS;
    }


}
