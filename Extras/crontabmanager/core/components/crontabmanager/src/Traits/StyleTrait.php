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
use Symfony\Component\Console\Output\StreamOutput;
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
        if ($this->isBrowser()) {
            $this->print_msg('<info class="info">'.$message.'</info>');
        } else {
            $this->style()->block($message, 'INFO', 'fg=green', ' ', false);
        }
    }

    public function isBrowser()
    {
        return ($this->service && $this->service->isBrowser());
    }

    public function error($message)
    {
        if ($this->service && $this->isBrowser()) {
            $this->print_msg('<error class="error">'.$message.'</error>');
        } else {
            $this->style()->block($message, 'ERROR', 'fg=white;bg=red', ' ', false);
        }
    }

    public function success($message)
    {
        if ($this->isBrowser()) {
            $this->print_msg('<error class="success">'.$message.'</error>');
        } else {
            $this->style()->block($message, 'OK', 'fg=black;bg=green', ' ', false);
        }
    }

    public function comment($message)
    {
        if ($this->isBrowser()) {
            $this->print_msg('<comment class="comment">'.$message.'</comment>');
        } else {
            $this->style()->comment($message);
        }
    }
}
