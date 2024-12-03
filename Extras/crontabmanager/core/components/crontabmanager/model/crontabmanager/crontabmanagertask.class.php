<?php

use Webnitros\CronTabManager\CrontabTask;

class CronTabManagerTask extends xPDOSimpleObject
{
    /* @var boolean $isSaveLog */
    protected $isSaveLog = false;

    /* @var CronTabManager $CronTabManager */
    protected $CronTabManager = null;

    /* @var string|null $old_path_task - записывается старый путь если произошли изменения */
    public $old_path_task = null;
    protected ?CrontabTask $crontab = null;


    public function crontab()
    {
        if (is_null($this->crontab)) {
            $this->crontab = new CrontabTask($this);
        }

        return $this->crontab;
    }

    /**
     * @return bool|CronTabManager
     */
    public function loadCronTabManager()
    {
        if (is_null($this->CronTabManager)) {
            $CronTabManager = $this->xpdo->getService('crontabmanager', 'CronTabManager', MODX_CORE_PATH.'components/crontabmanager/model/');
            if (!$CronTabManager instanceof CronTabManager) {
                throw new Exception("Error load class CronTabManager");
            }
            $this->CronTabManager = $CronTabManager;
        }

        return $this->CronTabManager;
    }

    public function set($k, $v = null, $vType = '')
    {
        switch ($k) {
            case 'path_task':
                $path = $this->get('path_task');
                if (!empty($path) and $path != $v) {
                    $this->old_path_task = $path;
                }
                break;
            default:
                break;
        }

        return parent::set($k, $v, $vType); // TODO: Change the autogenerated stub
    }

    /**
     * {@inheritdoc}
     */
    public function updateFlag(string $field, bool $value)
    {
        $value = $value ? '1' : '0';
        $table = $this->xpdo->getTableName('CronTabManagerTask');
        $this->xpdo->exec("UPDATE {$table} SET {$field} = '{$value}'  WHERE id = ".$this->get('id'));
    }

    /**
     * {@inheritdoc}
     */
    public function save($cacheFlag = null)
    {
        if ($this->isNew()) {
            if (empty($this->get('createdon'))) {
                $this->set('createdon', time());
            }
        } else {
            $this->set('updatedon', time());
        }

        $this->setLogRun();

        return parent::save();
    }


    /**
     * Устанавливаем сохранение логов
     * @param  boolean  $value
     */
    public function setSaveLog($value)
    {
        $this->isSaveLog = $value;
    }


    /**
     * Вернет требуется ли сохранять лог
     * @return bool
     */
    public function isSaveLog()
    {
        return $this->isSaveLog;
    }

    /**
     * Логирование запусков для задания
     * Контрольной точкой времени является last_run
     */
    public function setLogRun()
    {
        // Проверяем что было установлено изменение
        if ($this->isSaveLog() and !$this->isNew()) {
            #; // Отлючаем сохранение логов

            $completed = $this->get('completed');
            $last_run = strtotime($this->get('last_run'));
            $end_run = strtotime($this->get('end_run'));
            $task_id = $this->get('id');
            $max_number_attempts = $this->get('max_number_attempts');

            $criteria = array('task_id' => $task_id, 'last_run' => $last_run);


            // № 1 Создаем запись в логах при false
            if (!$completed) {
                /* @var CronTabManagerTaskLog $TaskLog */

                // Записываем hash для перехода по ссылке
                $hash = md5(time().$task_id.$last_run.$end_run);

                if (!$TaskLog = $this->xpdo->getObject('CronTabManagerTaskLog', $criteria)) {
                    $TaskLog = $this->xpdo->newObject('CronTabManagerTaskLog');
                    $TaskLog->set('last_run', $last_run);
                    $TaskLog->set('task_id', $task_id);
                    $TaskLog->set('hash', $hash);
                    $TaskLog->save();
                }

                // Если сработала автоматическая пауза
                /*$auto_pause = $this->get('auto_pause');
                if ($auto_pause === true) {
                    $completed = true;
                } else {

                    // Проверяем разрешение отправки уведомлений
                    $notification_enable = $this->get('notification_enable');

                    // Если задание завершилось то меняется  end_run
                    if ($notification_enable and $max_number_attempts > 0) {

                        // Если задание превысело максимальное количество попыток то отправляем сообщение администратору
                        $criteria_notifications = array(
                            'task_id' => $task_id,
                            'end_run' => $end_run,
                            'completed' => 0,
                            'notification' => 0,
                        );
                        if ($max_number_attempts <= $this->xpdo->getCount('CronTabManagerTaskLog', $criteria_notifications)) {

                            // Переключаем логи на вывод сообщений об ошибках в файл
                            $this->xpdo->setLogTarget('FILE');


                            // Переключаем логи на вывод сообщений об ошибках на экран
                            $this->xpdo->setLogTarget('ECHO');
                        }
                    }
                }*/
            }

            // № 2 Обновляем запись в логах true
            if ($completed) {
                if ($TaskLog = $this->xpdo->getObject('CronTabManagerTaskLog', $criteria)) {
                    $TaskLog->set('completed', true);

                    $TaskLog->set('end_run', $end_run);
                    // Статистика по заданию
                    $TaskLog->set('exec_time', $this->get('exec_time'));
                    $TaskLog->set('memory_usage', $this->get('memory_usage'));
                    $TaskLog->save();
                }
            }
        }
    }

    /**
     * Путь для запуска в ручную
     * @return string
     */
    public function getPathCli()
    {
        $php_executable = CronTabManagerPhpExecutable($this->xpdo);
        $path = $php_executable.' '.$this->getOption('crontabmanager_link_path').'/'.$this->get('path_task');

        return $path;
    }

    /* @var null|false|CronTabManagerCategory $category */
    protected $category = null;

    /**
     * @param  bool  $false  - false вернет массив объекта
     * @return bool|CronTabManagerCategory|null|object|xPDOObject
     */
    public function loadCategory($false = true)
    {
        if (!is_object($this->category) || !($this->resource instanceof CronTabManagerCategory)) {
            if (!$this->category = $this->getOne('Category')) {
                $this->category = $false ? false : $this->xpdo->newObject('CronTabManagerCategory ');
            }
        }

        return $this->category;
    }

    /**
     * Блокировка запуска задания на указаное количество минут
     * @param  int  $minutes  количество минут на которое требуется заблокировать задание
     */
    public function addBlockUpTask($minutes = 5)
    {
        $minutes = (int)$minutes;
        if ($minutes > 0) {
            $blockupdon = strtotime(date('Y-m-d H:i:s', strtotime('+'.$minutes.' minutes', time())));
            $this->set('blockupdon', $blockupdon);
        }
    }

    /**
     * Сброс времени блокировки
     */
    public function unBlockUpTask()
    {
        $this->set('blockupdon', 0);
    }

    /**
     * Вернет true в случае если задание было заблокировано на указанный срок
     * @return bool
     */
    public function isBlockUpTask()
    {
        return strtotime($this->get('blockupdon')) > time();
    }


    protected $linkFile = null;

    /**
     * Вернет путь на директорию и имя файла для создания блокировки
     * @return string
     */
    public function getLockFile()
    {
        if (is_null($this->linkFile)) {
            $path_task = $this->get('path_task');
            $lockPath = $this->getOption('crontabmanager_lock_path').'/';
            $path_task = str_ireplace('.php', '', $path_task);
            $this->linkFile = $lockPath.implode('_', explode('/', $path_task)).'.txt';
        }

        return $this->linkFile;
    }


    /**
     * Создание файла блокирующиего запуска других скриптов
     */
    public function addLock()
    {
        if ($lockFile = $this->getLockFile()) {
            $time = date('Y-m-d H:i:s', time());
            $fp = fopen($lockFile, 'w+');
            fwrite($fp, $time);
            fclose($fp);

            if (!file_exists($lockFile)) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "[".__CLASS__."][".__LINE__."] ".__FUNCTION__.": could not create lock file :".$this->lockFile);
            }
        }
    }


    /**
     * Вернет текст из лог файла
     * @return string|boolean
     */
    public function readLogFile()
    {
        $content = false;
        $path = $this->getFileLogPath();
        if (file_exists($path)) {
            // Получаем только часть строки
            $content = file_get_contents($path, false, null, 0, 10024);
        }

        return $content;
    }


    /**
     * Вернет текст из лог файла
     * @return string|boolean
     */
    public function readLogFileFormat()
    {
        $content = $this->readLogFile();
        $content = nl2br($content);
        $content = str_ireplace('✘', '❌', $content);
        $content = str_ireplace('✔', '✅', $content);
        $content = '<pre>'.$content.'</pre>';

        return $content;
    }

    /**
     * Вернет путь к файлу с логами для задания
     * @return string
     */
    public function getFileLogPath()
    {
        $logPath = $this->loadCronTabManager()->option('log_path');
        $id = $this->get('id');
        $path_task = $this->get('path_task');
        $paths = explode('/', $path_task);
        $path = array_pop($paths);
        $name = substr($path, 0, -4);

        return "{$logPath}/task_id_{$id}_{$name}.log";
    }

    /**
     * Вернет путь к блокировочному файлу
     * @return string
     */
    public function getFileManualStopPath($controller_path)
    {
        $path = $this->get('path_task');
        $task = preg_replace('/[^a-zA-Z0-9\-\._]/', '_', $path);
        $task = str_ireplace('.php', '.block', $task);

        return $controller_path.'manualstop/'.$task;
    }

    /**
     * Проверит время наступления блокировки
     * true - задание заблокировано
     * false - задание исполняется
     * @return bool
     */
    public function isLock()
    {
        $file = $this->getLockFile();
        if (file_exists($file)) {
            $minutes = $this->getOption('crontabmanager_blocking_time_minutes', null, 60);
            #$current = strtotime(date('Y-m-d H:i:s', time()) . ' - ' . $minutes . ' minutes');
            $current = strtotime(date('Y-m-d H:i:s', strtotime('-'.$minutes.' minutes', time())));

            $handle = @fopen($file, "r");
            if ($handle) {
                while (($buffer = fgets($handle, 4096)) !== false) {
                    $time = strtotime($buffer);
                    if ($current <= $time) {
                        return true;
                    }

                    return false;
                }
                if (!feof($handle)) {
                    $result = true;
                }
                fclose($handle);
            }
        }

        return false;
    }


    /**
     * Разблокировка задания
     */
    public function unLock()
    {
        if ($file = $this->getLockFile()) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }


    /**
     * Проверка существования файла блокировки
     * @return bool
     */
    public function isLockFile()
    {
        if ($file = $this->getLockFile()) {
            if (file_exists($file)) {
                return true;
            }

            return false;
        }

        return true;
    }


    /**
     * Фиксирует время начала исполнения задания
     * @return boolean
     */
    public function start()
    {
        return $this->setLogCrontab($this, 'start');
    }

    /**
     * Фиксирует время завершения задания
     * @return boolean
     */
    public function end($exec_time = 0, $memory_usage = 0)
    {
        return $this->setLogCrontab($this, 'end', $exec_time, $memory_usage);
    }

    /**
     * Логирование времени у заданий крон созданных через административную часть
     *
     * @param  CronTabManagerTask  $task
     * @param  string  $action
     * @return boolean
     */
    static function setLogCrontab(CronTabManagerTask $task, $action, $exec_time = 0, $memory_usage = 0)
    {
        switch ($action) {
            case 'start':
                $task->setSaveLog(1);
                $task->set('last_run', time());
                $task->set('completed', false); // Сбрасываем метку завершенности

                // Автоматически включаем блокировку файла
                $task->addLock();

                break;
            case 'end':
                $task->setSaveLog(1);
                $task->set('end_run', time());
                $task->set('completed', true); // Устанавливаем метку завершенности
                $task->set('exec_time', $exec_time);
                $task->set('memory_usage', $memory_usage);

                // Снятие блокировки
                $task->unLock();
                break;
            default:
                break;
        }

        return $task->save();
    }

    /**
     * Проверка режима разработки
     * @return boolean
     */
    public function isModeDevelop()
    {
        return (bool)$this->get('mode_develop');
    }

    public function remove(array $ancestors = array())
    {
        /* // Удаляем задание из крон
         $response = $this->crontab()->removeCron();
         if ($response) {
             return parent::remove($ancestors); // TODO: Change the autogenerated stub
         }*/
        return parent::remove($ancestors); // TODO: Change the autogenerated stub

        #  return false;
    }


    public function pid()
    {
        $path = $this->getPath();

        return \Webnitros\CronTabManager\Pid::status($path);
    }

    /**
     * Путь для запуска в ручную
     * @return string
     */
    public function getPath()
    {
        return $this->getOption('crontabmanager_link_path').'/'.$this->get('path_task');
    }

    public function controllerPath()
    {
        $this->loadCronTabManager()->loadSchedulerService()->getOption('basePath');

        return $this->loadCronTabManager()->loadSchedulerService()->getOption('basePath').$this->get('path_task');
    }

    public function controllerExists()
    {
        return file_exists($this->controllerPath());
    }


    public function isEnableCron()
    {
        if (!cronTabManagerIsAvailable()) {
            return true;
        }

        return !empty($this->crontab()->findCron());
    }

    public function muteSuccess()
    {
        $this->set('mute', 1);
        $this->set('mute_success', 1);

        return $this->save();
    }

    public function muteTime(int $date)
    {
        $this->set('mute', 1);
        $this->set('mute_time', $date);

        return $this->save();
    }

    /**
     * Отключение мута
     * @return bool
     */
    public function muteOff()
    {
        $this->set('mute', false);
        $this->set('mute_success', false);
        $this->set('mute_time', 0);

        $this->updateFlag('mute', false);
        $this->updateFlag('mute_success', false);
        $this->updateFlag('mute_time', 0, true);

        return true;
    }

    public function isMute()
    {
        return $this->get('mute');
    }
}
