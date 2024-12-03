<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 29.11.2024
 * Time: 20:47
 */

namespace Webnitros\CronTabManager\Commands\Abstracts;

use CronTabManager;
use SchedulerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Webnitros\CronTabManager\Traits\StyleTrait;

class AbstractCrontabCommand extends Command
{
    use StyleTrait;

    public ?\modX $modx;
    public ?CronTabManager $CronTabManager = null;
    public ?SchedulerService $scheduler = null;

    public function initModx(\modX $modX)
    {
        $this->modx = $modX;
        $this->CronTabManager = $modX->getService('crontabmanager', 'CronTabManager', MODX_CORE_PATH.'components/crontabmanager/model/');
        $this->scheduler = $this->CronTabManager->loadSchedulerService();
        $modX->lexicon->load('crontabmanager:manager');
    }

    protected function configure()
    {
        $this->addArgument('argument', InputArgument::OPTIONAL, 'The argument description');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->style($input, $output);
        $this->handle($input, $output);

        return self::SUCCESS;
    }
}
