<?php


use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;

define('MODX_CRONTAB_MODE', true);
define('MODX_CRONTAB_MAX_TIME', 33);

$TaskId = (integer)$_REQUEST['task_id'];
$scheduler_path = preg_replace('/[^a-zA-Z0-9\-\.:_]/', DIRECTORY_SEPARATOR, $_REQUEST['scheduler_path']);


if (!file_exists($scheduler_path)) {
    exit('Контроллер не найден');
}

require_once $scheduler_path.'/index.php';

/* @var modX $modx */
/* @var CronTabManager $CronTabManager */
if (!$CronTabManager instanceof CronTabManager) {
    exit('Error load class CronTabManager');
}

if (!$modx->hasPermission('crontabmanager_task_run')) {
    exit($modx->lexicon('access_denied'));
}

/* @var CronTabManagerTask $scheduler */
/* @var CronTabManagerTask $Task */
if (!$Task = $modx->getObject('CronTabManagerTask', $TaskId)) {
    exit($modx->lexicon('Task not found id:'.$TaskId));
}

$modx->lexicon->load('crontabmanager:manager');
$windows = $modx->lexicon('crontabmanager_cron_connector_run_task_windows');
$windows_btn = $modx->lexicon('crontabmanager_cron_connector_run_task_windows_btn');

$unlock = $modx->lexicon('crontabmanager_cron_connector_unlock');
$unlock_btn = $modx->lexicon('crontabmanager_cron_connector_unlock_btn');

$read_log = $modx->lexicon('crontabmanager_cron_connector_read_log');
$read_log_btn = $modx->lexicon('crontabmanager_cron_connector_read_log');

$connector_args = $modx->lexicon('crontabmanager_cron_connector_args');


$connector_args_value = !empty($_GET['connector_args']) ? $_GET['connector_args'] : '';
echo '<button class="crontabmanager-btn crontabmanager-btn-default icon icon-play" onclick="runTaskWindow()" title="'.$windows.'"> <small > '.$windows_btn.'</small></button>';
echo '<button class="crontabmanager-btn crontabmanager-btn-default icon icon-unlock" onclick="unlockTask()" title="'.$unlock.'"> <small> '.$unlock_btn.'</small></button>';
echo '<button class="crontabmanager-btn crontabmanager-btn-default icon icon-eye" onclick="readLogFileBody()" title="'.$read_log.'"> <small> '.$read_log_btn.'</small></button>';
echo '<input type="text" placeholder="'.$connector_args.'" class="crontabmanager-cron-args x-form-text x-form-field " id="crontabmanager_connector_args" name="connector_args" value="'.$connector_args_value.'">';
echo '<hr>';

$Artisan = $CronTabManager->artisan();

$command = $Artisan->parseCommand($Task->crontab()->command());

#$arg = substr($Task->crontab()->command(), strlen($command));
#$args = $Artisan->parseArgs($arg);
#if (!empty($connector_args_value)) {
#    $args = array_merge($args, $Artisan->parseArgs($connector_args_value));
#}
$Artisan->shellTask($Task, $connector_args_value);
sleep(1);
// Получаем путь к файлу
$filePath = $Task->getFileLogPath();

echo "Task run<br>";
if (file_exists($filePath)) {
// Открываем файл для чтения
    if ($file = fopen($filePath, 'r')) {
        // Читаем файл построчно
        while ($line = fgets($file)) {
            echo $line.'<br>'; // Выводим строку с добавлением новой строки
        }

        // Закрываем файл
        fclose($file);
    }
}
