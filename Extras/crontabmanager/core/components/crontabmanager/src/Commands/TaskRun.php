<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 29.11.2024
 * Time: 20:06
 */

namespace Webnitros\CronTabManager\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webnitros\CronTabManager\Commands\Abstracts\AbstractCrontabCommand;


class TaskRun extends AbstractCrontabCommand
{
    protected function configure()
    {
    }

    protected function handle(InputInterface $input, OutputInterface $output)
    {
        global $modx;

        $this->initModx($modx);

        /* @var string $name */
        $name = $this->getName();
        if ($aliases = $this->getAliases()) {
            if (is_array($aliases) && !empty($aliases)) {
                $name = $aliases[0];
            }
        }

        $name = str_ireplace(':', '/', $name);
        $this->scheduler->php($name)->process(null, true, $input);

        return 1;
    }
}
