<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 29.11.2024
 * Time: 20:06
 */

namespace Webnitros\CronTabManager\Commands;

use CronTabManagerTask;
use modSnippet;
use Symfony\Component\Console\Command\Command;
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


        if ($input->getArgument('no-interaction') === null) {
            if (!$this->interaction($name, $input)) {
                return Command::FAILURE;
            }
        }

        $this->scheduler->process($name, true, $input);

        return 1;
    }

    /**
     * Только для интерактивного запуска
     * @param  string  $name
     * @param $input
     * @return int|void
     */
    public function interaction(string $name, $input)
    {
        $name = str_ireplace(':', '/', $name);
        if ($Task = $this->getTask($name, $input)) {
            // Фиксируем процесс запуска в задании чтобы он не исполнялось автоматически до завершения
            if ($Task->isLock()) {
                if (!is_null($input->getArgument('d'))) {
                    $this->comment('Development: Kill pid: '.$Task->pid()->id());
                    $Task->unLock();
                } elseif (is_null($input->getArgument('dev-browser'))) {
                    $command = $Task->crontab()->command(null, true);
                    $this->comment(
                        'Обнаружено активное крон задание которое было запущено в автоматическом режиме. Необходимо выполнить kill PID для продолжения работы. '
                    );

                    $this->comment(
                        'Или запустить команду ['.$command.' --d] c аргументом "--d" для автоматического завершения процесс, и запуска команды задачи.'
                    );

                    return false;
                }
            }
            $Task->pid()->update();
        }

        return true;
    }

    public function getTask(string $name, $input)
    {
        $path_task = $name.'.php';
        $criteria = [
            'active' => 1,
            'path_task' => $name.'.php',
        ];
        if ($path_task === 'snippet.php') {
            $snippet = $input->getArgument('snippet');

            /* @var modSnippet $object */
            if ($Snippet = $this->modx->getObject('modSnippet', ['name' => $snippet])) {
                $snippet_id = $Snippet->get('id');
                $criteria['snippet'] = $snippet_id;
            }
        }
        /* @var CronTabManagerTask $Task */
        if ($Task = $this->modx->getObject('CronTabManagerTask', $criteria)) {
            return $Task;
        }

        return null;
    }
}
