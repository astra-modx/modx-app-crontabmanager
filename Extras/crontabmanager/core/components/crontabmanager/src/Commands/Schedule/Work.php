<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 08.08.2023
 * Time: 17:10
 */

namespace Webnitros\CronTabManager\Commands\Schedule;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Work extends Run
{
    protected static $defaultName = 'schedule:work';
    protected static $defaultDescription = 'Run scheduled tasks';

    public function handle(InputInterface $input, OutputInterface $output): int
    {
        parent::handle($input, $output);
        sleep(60);

        $this->handle($input, $output);

        return self::SUCCESS;
    }

}
