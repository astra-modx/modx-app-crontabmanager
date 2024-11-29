<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 29.11.2024
 * Time: 20:06
 */

namespace Webnitros\CronTabManager\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Webnitros\CronTabManager\Commands\Abstracts\AbstractCrontabCommand;


class TaskRun extends AbstractCrontabCommand
{
    protected function configure()
    {
        $name = $this->getName();

        $this
            #->setHelp('This command allows you to create a user...')
            ->setName($name)
            ->setDescription('Mode development')
            ->addArgument('d', InputArgument::REQUIRED, 'Mode development')#  ->addOption('d', null, InputOption::VALUE_NONE, 'Режим разработки')
        ;
    }

    protected function handle(InputInterface $input, OutputInterface $output)
    {
        global $modx;

        $this->initModx($modx);

        /* @var string $name */
        $name = $this->getName();

        $name = str_ireplace(':', '/', $name);
        if ($input->hasArgument('d')) {
            $this->scheduler->setArgs([
                'develop' => true,
            ]);
        }

        $this->scheduler
            ->php($name)
            ->process(null, true);

        return 1;
    }
}
