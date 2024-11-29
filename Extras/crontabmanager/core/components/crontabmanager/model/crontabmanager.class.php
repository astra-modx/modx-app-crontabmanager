<?php

include_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';


if (!function_exists('CronTabManagerPhpExecutable')) {

    function CronTabManagerPhpExecutable($modx)
    {
        $php_executable = trim($modx->getOption('crontabmanager_php_command'));
        if (empty($php_executable)) {
            if (php_sapi_name() === 'cli') {
                // путь до интерпретатора PHP в командной строке
                $php_executable = PHP_BINARY;
            } else {
                // путь до интерпретатора PHP в веб-сервере
                $php_executable = PHP_BINDIR . '/php';
            }
        }
        return $php_executable;
    }
}

if (!function_exists('shutdownHandlerTask')) {

    function shutdownHandlerTask(CronTabManagerTask $task)
    {


        // Ваш код, который будет выполнен после завершения скрипта
        $run = true;
        if (!$task->get('mode_develop')) {

            $taskId = $task->get('id');
            // Проверяем что у задания небыло рестата
            $LogAnalyzer = new \Webnitros\CronTabManager\Analyzer\LogAnalyzer($task);
            $Log = $LogAnalyzer->getLastLog();


            // Проверяка неудачной попытке завершения
            // Если задание не завершилось и включена опция перезапуска
            if (!$task->get('restart') && $task->get('restart_after_failure')) {
                // Проверяем что задание не завершилось успешно
                if (!$Log['completed']) {
                    // Устанавливаем флаг перезапуска у задания
                    $task->updateFlag('restart', true);

                    if ($task->isLock()) {
                        // Разблокируем задание иначе оно не запуститься повторно
                        $task->unlock();
                    }

                    // Помечаем задание как игнорируемое чтобы при следующем запуске не учитывалось для создания уведомления
                    $table = $task->xpdo->getTableName('CronTabManagerTaskLog');
                    $sql = "UPDATE {$table} SET ignore_action = '1'  WHERE id = " . $Log['id'];
                    $task->xpdo->exec($sql);

                    // Проверяем что у задания есть путь
                    $path = $task->getPathCli();
                    $path_log = $task->getFileLogPath();

                    // Запуская задание повторно
                    $command = $path . ' > ' . $path_log . ' 2>&1';

                    // делаем задержку иначе почему то функция shell_exec отрабатывает не верно с логом
                    sleep(1);

                    shell_exec($command);
                    echo PHP_EOL . 'restart' . PHP_EOL;
                    $run = false;
                }
            }


            if ($run) {


                //  Проверяем что у задания есть флаг перезапуска
                if ($task->get('restart')) {
                    // Сбрасываем флаг перезапуска у задания
                    $task->updateFlag('restart', false);
                }


                $events = $LogAnalyzer->process();
                $Rules = new \Webnitros\CronTabManager\Event\Rules($task, $events);
                $results = $Rules->process();
                $logId = $Log['id'];
                $createMessage = 0;
                if (!empty($results)) {
                    foreach ($results as $result) {
                        $event = trim($result['event']);
                        if (empty($event)) {
                            continue;
                        }
                        $createMessage++;
                        $params = [];


                        if (!empty($result['params'])) {
                            $params = is_array($result['params']) ? $result['params'] : $task->xpdo->fromJSON($result['params']);
                        }
                        switch ($result['class']) {
                            case 'Email':
                                $params['email'] = @$result['email'];
                                break;
                            case 'Telegram':

                                $params['token'] = @$result['token'];
                                $params['chat_id'] = @$result['chat_id'];
                                break;
                            case 'Webhook':
                                $params['url'] = @$result['url'];
                                break;
                            default:
                                break;
                        }

                        $ruleId = $result['id'];
                        $criteria = [
                            'log_id' => $logId,
                            'rule_id' => $ruleId
                        ];

                        /* @var CronTabManagerNotification $object */
                        if (!$Notice = $task->xpdo->getObject('CronTabManagerNotification', $criteria)) {
                            /* @var CronTabManagerNotification $object */
                            $Notice = $task->xpdo->newObject('CronTabManagerNotification');
                            $Notice->set('task_id', $taskId);
                            $Notice->set('log_id', $logId);
                            $Notice->set('rule_id', $ruleId);
                            $Notice->set('processing', true);
                            $Notice->set('message', $result['message']);
                            $Notice->set('class', $result['class']);
                            $Notice->set('event', $event);
                            $Notice->set('params', $params);

                            if (!$Notice->save()) {
                                echo 'Error save notice';
                            }

                            $Notice->send();

                        }

                    }
                }
            }
        }
    }

}

class CronTabManager
{
    /** @var modX $modx */
    public $modx;

    /** @var array $config */
    public $config = array();

    /** @var array $initialized */
    public $initialized = array();

    /** @var modError|null $error = */
    public $error = null;


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;
        $corePath = MODX_CORE_PATH . 'components/crontabmanager/';
        $assetsUrl = MODX_ASSETS_URL . 'components/crontabmanager/';

        $this->config = array_merge([
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',
            'customPath' => $corePath . 'custom/',
            'json_response' => false,

            'connectorUrl' => $assetsUrl . 'connector.php',
            'connectorCronUrl' => $assetsUrl . 'cron_connector.php',
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',


            'schedulerPath' => $this->modx->getOption('crontabmanager_scheduler_path', $config, ''),
            'logPath' => $this->modx->getOption('crontabmanager_log_path', $config, ''),
            'lockPath' => $this->modx->getOption('crontabmanager_lock_path', $config, ''),
            'linkPath' => $this->modx->getOption('crontabmanager_link_path', $config, ''),
            'log_storage_time' => $this->modx->getOption('crontabmanager_log_storage_time', $config, ''),
            'php_command' => CronTabManagerPhpExecutable($modx),

            'scheduler' => $corePath . 'lib/schedulercontroller/',
        ], $config);

        $this->modx->addPackage('crontabmanager', $this->config['modelPath']);
        $this->modx->lexicon->load('crontabmanager:default');
    }

    /**
     * Создание дополнительных категорий
     */
    public function createDirs()
    {
        // Создадим папку где будет храниться блокировка
        $lockPath = $this->config['lockPath'];
        if (!file_exists($lockPath)) {
            if (!mkdir($lockPath, 0777, true)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, "Error create dir {$lockPath}", '', __METHOD__, __FILE__, __LINE__);
            }
        }
        // Создадим папку где будет храниться блокировка
        $logPath = $this->config['logPath'];
        if (!file_exists($logPath)) {
            if (!mkdir($logPath, 0777, true)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, "Error create dir {$logPath}", '', __METHOD__, __FILE__, __LINE__);
            }
        }
        // Создадим папку где будет храниться блокировка
        $linkPath = $this->config['linkPath'];
        if (!file_exists($linkPath)) {
            if (!mkdir($linkPath, 0777, true)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, "Error create dir {$linkPath}", '', __METHOD__, __FILE__, __LINE__);
            }
        }
    }


    /**
     * Загрузка планировщика заданий
     * @return SchedulerService
     */
    public function loadSchedulerService($config = array())
    {
        $this->modx->setLogLevel(modX::LOG_LEVEL_ERROR);
        $date_time = $this->modx->getOption('date_timezone', null, 'Europe/Moscow');
        if (empty($date_time)) {
            $date_time = 'Europe/Moscow';
        }
        date_default_timezone_set($date_time);

        $targetFILE = false;
        if ($targetFILE) {
            // Записывать логи в планировщик заданий
            $this->modx->setLogTarget(array(
                'target' => 'FILE',
                'options' => array(
                    'filename' => 'error.log.crontab',
                    'filepath' => $this->config['logPath']
                ),
            ));
        } else {
            // Печатать ошибка сразу
            $this->modx->setLogTarget('ECHO');
        }

        $this->modx->error->message = null;
        $this->createDirs();
        $config = array_merge($this->config, $config);
        $this->modx->setOption('log_deprecated', false);
        if (!class_exists('SchedulerService')) {
            include $this->config['scheduler'] . 'schedulerservice.class.php';
        }

        if (!class_exists('modCrontabController')) {
            include $this->config['scheduler'] . 'modcrontabcontrollerinterface.class.php';
        }

        $scheduler = new SchedulerService($this,
            array_merge($config, array(
                'basePath' => $this->config['schedulerPath'] . '/Controllers/',
                'controllerClassSeparator' => '',
                'controllerClassPrefix' => 'CrontabController',
            ))
        );
        return $scheduler;
    }


    /* @var CrontabManagerHandler $ctm */
    protected $ctm = null;


    /**
     * Class loading for job management
     * @return bool|null|CrontabManagerHandler
     */
    public function loadManager()
    {
        if (is_null($this->ctm)) {
            // Default classes
            if (!class_exists('CrontabManagerHandler')) {
                require_once dirname(__FILE__) . '/crontabmanagerhandler.class.php';
            }

            // Custom ctm class
            $ctm_class = $this->modx->getOption('crontabmanager_handler_class', null, 'CrontabManagerHandler');
            if ($ctm_class != 'CrontabManagerHandler') {
                $this->loadCustomClasses('crontab');
            }
            if (!class_exists($ctm_class)) {
                $ctm_class = 'CrontabManagerHandler';
            }

            $this->ctm = new $ctm_class($this, $this->config);
            if (!($this->ctm instanceof CrontabManagerHandlerInterface) || $this->ctm->initialize() !== true) {
                $this->modx->log(modX::LOG_LEVEL_ERROR,
                    'Could not initialize CrontabManager ctm handler class: "' . $ctm_class . '"');
                return false;
            }

        }
        return $this->ctm;
    }




#loadCrontabManagerManual


    /**
     * Method loads custom classes from specified directory
     *
     * @return void
     * @var string $dir Directory for load classes
     *
     */
    public function loadCustomClasses($dir)
    {
        $files = scandir($this->config['customPath'] . $dir);
        foreach ($files as $file) {
            if (preg_match('/.*?\.class\.php$/i', $file)) {
                include_once($this->config['customPath'] . $dir . '/' . $file);
            }
        }
    }


    /**
     * Генерация ссылок на контроллеры
     */
    public function generateCronLink()
    {
        $scheduler = $this->loadSchedulerService();
        if ($scheduler instanceof SchedulerService) {
            $scheduler->generateCronLink();
        }
    }

    /**
     * Генерация ссылок на контроллеры
     */
    public function createIndexFile()
    {
        $pathIndex = $this->config['schedulerPath'] . '/index.php';
        if (!file_exists($pathIndex)) {
            $cache = $this->modx->getCacheManager();
            $content = $this->getTemplateIndex();
            if (!$cache->writeFile($this->config['schedulerPath'] . '/index.php', $content)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, "Не удалось создать файл индекс", '', __METHOD__, __FILE__, __LINE__);
            }
        }
    }


    /**
     * Return the class platform template for the class files.
     *
     * @access public
     * @return string The class platform template.
     */
    private function getTemplateIndex()
    {
        return '<?php
require_once dirname(dirname(dirname(__FILE__))) . "/config.core.php";
require_once MODX_CORE_PATH . "model/modx/modx.class.php";
$modx = new modX();
$modx->initialize("mgr");
$modx->getService("error", "error.modError");
$modx->getRequest();

/* @var CronTabManager $CronTabManager */
$CronTabManager = $modx->getService("crontabmanager", "CronTabManager", MODX_CORE_PATH . "components/crontabmanager/model/");
$scheduler = $CronTabManager->loadSchedulerService();
if (!defined("MODX_CRONTAB_MODE") OR !MODX_CRONTAB_MODE) {
    $scheduler->getPath();
    $scheduler->process();
}';
    }


    /**
     * Shorthand for the call of processor
     *
     * @access public
     *
     * @param string $action Path to processor
     * @param array $data Data to be transmitted to the processor
     *
     * @return mixed The result of the processor
     */
    public function runProcessor($action = '', $data = array())
    {
        if (empty($action)) {
            return false;
        }
        #$this->modx->error->reset();
        $processorsPath = !empty($this->config['processorsPath'])
            ? $this->config['processorsPath']
            : MODX_CORE_PATH . 'components/crontabmanager/processors/';

        return $this->modx->runProcessor($action, $data, array(
            'processors_path' => $processorsPath,
        ));
    }


    /* This method returns an error response
	 *
	 * @param string $message A lexicon key for error message
	 * @param array $data Additional data, for example cart status
	 * @param array $placeholders Array with placeholders for lexicon entry
	 *
	 * @return array|string $response
	 * */
    public function error($message = '', $data = array(), $placeholders = array())
    {
        $response = array(
            'success' => false
        , 'message' => $this->modx->lexicon($message, $placeholders)
        , 'data' => $data
        );

        return $this->config['json_response'] ? $this->modx->toJSON($response) : $response;
    }

    /* This method returns an success response
     *
     * @param string $message A lexicon key for success message
     * @param array $data Additional data, for example cart status
     * @param array $placeholders Array with placeholders for lexicon entry
     *
     * @return array|string $response
     * */
    public function success($message = '', $data = array(), $placeholders = array())
    {
        $response = array(
            'success' => true
        , 'message' => $this->modx->lexicon($message, $placeholders)
        , 'data' => $data
        );
        return $this->config['json_response'] ? $this->modx->toJSON($response) : $response;
    }


    /**
     * Function for sending email
     *
     * @param string|array $emails
     * @param string $subject
     * @param string $body
     *
     * @return array
     */
    public function sendEmail($emails, $subject, $body = 'no body set')
    {

        /** @var modPHPMailer $mail */
        $this->modx->getService('mail', 'mail.modPHPMailer');
        $this->modx->mail->set(modMail::MAIL_FROM, $this->modx->getOption('emailsender'));
        $this->modx->mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('site_name'));
        $this->modx->mail->setHTML(true);
        $this->modx->mail->set(modMail::MAIL_SUBJECT, trim($subject));
        $this->modx->mail->set(modMail::MAIL_BODY, $body);


        if (is_array($emails)) {
            foreach ($emails as $e) {
                $this->modx->mail->address('to', trim($e));
            }
        } else {
            $this->modx->mail->address('to', trim($emails));
        }

        $response = true;
        if (!$this->modx->mail->send()) {
            $response = false;
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'An error occurred while trying to send the email: ' . $this->modx->mail->mailer->ErrorInfo);
        }
        $this->modx->mail->reset();

        return $response == false
            ? $this->error('Не удалось отправить ежедневную рассылку на email ' . print_r($emails, 1) . '. ' . $this->modx->mail->mailer->ErrorInfo)
            : $this->success('Успешно отправлена');
    }


    /**
     * Вернет список заданий в случае если они были сохранены в файл в место crontab
     * @param bool $isArray
     * @return array|bool|null|string
     */
    public function getCronTasks($isArray = false)
    {
        $path = $this->config['schedulerPath'] . '/crontabs/' . $_SERVER['USER'];
        $out = (file_exists($path)) ? file_get_contents($path) : false;
        $jobs = null;
        if (!empty($out) and $isArray) {
            $jobs = explode("\n", $out);
        } else {
            $jobs = $out;
        }
        return $jobs;
    }

}
