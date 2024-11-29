<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 29.11.2024
 * Time: 22:51
 */

namespace Webnitros\CronTabManager\Traits;


use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

trait StyleTrait
{
    protected ?SymfonyStyle $style = null;

    public function style(?InputInterface $input = null, ?OutputInterface $output = null)
    {
        if ($this->style === null) {
            if (null === $input) {
                $input = new ArgvInput();
            }

            if (null === $output) {
                $output = new ConsoleOutput();
            }
            $this->style = new SymfonyStyle($input, $output);
        }

        return $this->style;
    }


    public function info($message)
    {
        $this->style()->block($message, 'INFO', 'fg=green', ' ', false);
    }


}
