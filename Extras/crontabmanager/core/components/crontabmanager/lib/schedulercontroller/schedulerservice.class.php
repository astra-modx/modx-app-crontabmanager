<?php

class SchedulerService
{
    /* @var modX $modx */
    public $modx;

    /* @var CronTabManager $CronTabManager */
    public $CronTabManager;

    /* @var string $action */
    public $action;
    /* @var string $task */
    public $task;
    protected $enabledException = false;
    public $recordsCount = 0;
    public $recordsLimit = 0;
    public $recordsOffset = 0;
    public $ForcedStop = false;


    /* @var array $config */
    public $config;

    /* @var boolean $isSetCompletionTime - будет писать логи для задания */
    public $isSetCompletionTime = true;
    /* @var int $user_id */
    public $user_id = null;

    /* @var CronTabManagerTask $CronTabManagerTask */
    public $CronTabManagerTask = null;

    /* @var boolean $defaultModeDevelop - запись информациюю о включеном режиме разработке */
    public $defaultModeDevelop = false;

    /** @var int|string $requestPrimaryKey The primary key requested on the object/id route */
    public $requestPrimaryKey;

    /* @var boolean|null $mode */
    public $mode = null;

    /**
     * @param  CronTabManager  $CronTabManager
     * @param  array  $config
     */
    function __construct(CronTabManager &$CronTabManager, array $config = array())
    {
        $this->modx =& $CronTabManager->modx;
        $this->config = array_merge(array(
            'basePath' => dirname(__FILE__).'/Controllers/',
            'start_time' => microtime(true),
            'max_exec_time' => @ini_get("max_execution_time") - 5,
            'blocking_time_minutes' => $this->modx->getOption('crontabmanager_blocking_time_minutes', $config, 60),
            'controllerClassPrefix' => 'modController',
            'controllerClassSeparator' => '_',
            'controllerClassFilePostfix' => '.php',
        ), $config);

        // Включения механизма блокирования
        $this->isSetCompletionTime = (bool)$this->modx->getOption('crontabmanager_set_completion_time', $config, true);

        // Авторизоваться под пользователем
        $this->user_id = (int)$this->modx->getOption('crontabmanager_user_id', $config, 0);
        if ($this->user_id != 0) {
            if ($User = $this->modx->getObject('modUser', $this->user_id)) {
                $this->modx->user = $User;
            }
        }
    }

    public $isCommand = false;

    public function isCommand()
    {
        return $this->isCommand;
    }

    private $_browser = false;

    public function isBrowser()
    {
        return $this->_browser;
    }

    public function browser(bool $browser = true)
    {
        $this->_browser = $browser;

        return $this;
    }

    public $name;

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $name = str_ireplace(':', '/', $name);
        $this->name = $name;

        return $this;
    }

    public function getPath()
    {
        if (!empty($_REQUEST['_scheduler']) && is_string($_REQUEST['_scheduler'])) {
            $this->setName($_REQUEST['_scheduler']);
        }
    }

    /**
     * Process the response and format in the proper response format.
     */
    public function process(string $name = null, bool $command = false, $input = null)
    {
        if (!empty($name)) {
            $this->setName($name);
        }
        $name = $this->getName();

        $this->isCommand = $command;
        $this->CronTabManagerTask = null;
        $this->setMode();
        if ($controllerName = $this->getController($name)) {
            if (null == $controllerName) {
                throw new Exception('Method not allowed', 405);
            }

            /** @var modCrontabController $controller */
            try {
                $controller = new ReflectionClass($controllerName);
                if (!$controller->isInstantiable()) {
                    throw new Exception('Bad Request', 400);
                }

                try {
                    /** @var ReflectionMethod $method */
                    $method = $controller->getMethod('run');
                } catch (ReflectionException $e) {
                    throw new Exception('Unsupported HTTP method process', 405);
                }

                if (!$method->isStatic()) {
                    $controller = $controller->newInstance($this->modx, $this->config);
                    $controller->service = $this;


                    if ($input instanceof \Symfony\Component\Console\Input\InputInterface) {
                        // Для аргументов
                        $this->createInput($input);
                    }

                    $taskPath = $name.'.php';

                    $criteria = ['path_task' => $taskPath];
                    if ($taskPath === 'snippet.php') {
                        $snippet = $input->getArgument('snippet');

                        /* @var modSnippet $object */
                        if ($Snippet = $this->modx->getObject('modSnippet', ['name' => $snippet])) {
                            $snippet_id = $Snippet->get('id');
                            $criteria['snippet'] = $snippet_id;
                        }
                    }


                    if ($command && !$this->modx->getCount('CronTabManagerTask', $criteria)) {
                        // For command run
                        $controller->process();
                    } else {
                        // Only for task run
                        if ($task = $this->getTask($criteria)) {
                            // Записываем путь перед стартом
                            if (is_null($this->manualStopExecutionPath)) {
                                $this->manualStopExecutionPath = $task->getFileManualStopPath($this->config['basePath']);
                                if (file_exists($this->manualStopExecutionPath)) {
                                    unlink($this->manualStopExecutionPath);
                                }
                            }

                            $this->defaultModeDevelop = $task->get('mode_develop');

                            // Добавить указатель что запуск в режиме dev
                            if ($this->getArgument('d') !== null) {
                                $task->set('mode_develop', true);
                            }

                            // Добавить указатель что запуск в режиме dev
                            if ($this->getArgument('dev-browser') !== null) {
                                $this->browser();
                            }

                            $this->runProcess($task, $method, $controller);
                        }
                    }
                } else {
                    throw new Exception('Static methods not supported in Controllers', 500);
                }
            } catch (ReflectionException $e) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[Crontab] '.$e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
            }
        }
    }


    public function createInput(\Symfony\Component\Console\Input\InputInterface $input)
    {
        $this->input = $input;

        return $this;
    }

    public ?\Symfony\Component\Console\Input\InputInterface $input = null;

    public function input()
    {
        if ($this->input === null) {
            $this->input = new \Symfony\Component\Console\Input\ArgvInput();
        }

        return $this->input;
    }


    public function getArgument(string $name, $default = null)
    {
        if ($this->input()->hasArgument($name)) {
            return $this->input()->getArgument($name);
        }

        return $default;
    }

    public function hasArgument(string $name)
    {
        return $this->input()->hasArgument($name);
    }


    /**
     * Получение задания и проверка блокировки
     * @return CronTabManagerTask|object|null
     * @throws Exception
     */
    public function getTask($criteria = null)
    {
        if (!is_null($this->CronTabManagerTask)) {
            return $this->CronTabManagerTask;
        }


        /* @var CronTabManagerTask $CronTabManagerTask */
        if (!$this->CronTabManagerTask = $this->modx->getObject('CronTabManagerTask', $criteria)) {
            $this->response('Error get task: '.print_r($criteria, 1));
        }

        // Проверка срока хранения логов, чтобы не забивать логами всю базу автоматически требуется отчищать логи
        $log_storage_time = (int)$this->CronTabManagerTask->get('log_storage_time');
        if ($log_storage_time > 0) {
            $task_id = $this->CronTabManagerTask->get('id');
            $createdon = strtotime(date('Y-m-d H:i:s', strtotime('-'.$log_storage_time.' minutes', time())));
            $criteria = array(
                'task_id' => $this->CronTabManagerTask->get('id'),
                'createdon:<' => $createdon,
            );
            if ($count = (bool)$this->modx->getCount('CronTabManagerTaskLog', $criteria)) {
                $sql = "DELETE FROM {$this->modx->getTableName('CronTabManagerTaskLog')} WHERE task_id = {$task_id} and createdon <= {$createdon}";
                $this->modx->exec($sql);
            }
        }

        return $this->CronTabManagerTask;
    }


    public function isLook(CronTabManagerTask $task)
    {
        if (!$task->isModeDevelop()) {
            if (!$task->get('active')) {
                $this->response('job deactivated id:'.$task->get('id'));
            }

            if ($task->isBlockUpTask()) {
                $this->response('task blocked until: '.$task->get('blockupdon'));
            }


            // Проверям время блокировки файла
            if (!$task->isLock()) {
                $task->unLock();
            }

            // Проверка существование файла
            if ($task->isLock()) {
                $this->response($this->modx->lexicon('crontabmanager_task_execution_not_complete'));
            }
        }
    }

    /**
     * Исполнение контраллера с фиксацией времени
     * @param  ReflectionMethod  $method
     * @param  CronTabManagerTask  $task
     * @param  modCrontabController  $controller
     */
    public function runProcess(CronTabManagerTask $task, ReflectionMethod $method, modCrontabController $controller)
    {
        if ($this->isSetCompletionTime) {
            $task->set('mode_develop', $this->defaultModeDevelop);
            $task->start();
        }

        // 2.1 Запуск контроллера
        $response = $method->invoke($controller);

        // 3. Остановка задания
        if ($this->isSetCompletionTime) {
            $task->set('mode_develop', $this->defaultModeDevelop);

            $exec_time = microtime(true) - $this->getOption('start_time');
            $memory = round(memory_get_usage(true) / 1024 / 1024, 4);
            $task->end($exec_time, $memory);
        }

        $this->response($this->GetUsage());
    }

    public function response($data = '')
    {
        if ($this->isEnabledException()) {
            throw new Exception($data);
        } else {
            @session_write_close();
            exit($data);
        }
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
        ,
            'message' => $this->modx->lexicon($message, $placeholders)
        ,
            'data' => $data,
        );

        $this->response($response);
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
        ,
            'message' => $this->modx->lexicon($message, $placeholders)
        ,
            'data' => $data,
        );
        $this->response($response);
    }


    /**
     * Вермен логировние времени
     * @return string
     */
    private function GetUsage()
    {
        global $tstart, $modx;


        $exec_time = microtime(true) - $this->getOption('start_time');

        $out = '';
        $memory = round(memory_get_usage(true) / 1024 / 1024, 4).' Mb';


        if (isset($_GET['connector_base_path_url'])) {
            $prefix = '<br>';
        } else {
            $prefix = PHP_EOL;
        }


        if ($this->ForcedStop) {
            $out .= "Forced stop, max_exec_time: {$this->getOption('max_exec_time')} s".$prefix;
        }
        $out .= "Time all: {$exec_time}".$prefix;
        $out .= "Records process: {$this->recordsCount}".$prefix;
        $out .= "Memory: {$memory}".$prefix;
        $totalTime = (microtime(true) - $tstart);
        $totalTime = sprintf("%2.4f s", $totalTime);
        if (!empty($modx)) {
            $queryTime = $modx->queryTime;
            $queryTime = sprintf("%2.4f s", $queryTime);
            $queries = isset($modx->executedQueries) ? $modx->executedQueries : 0;

            $phpTime = $totalTime - $queryTime;
            $phpTime = sprintf("%2.4f s", $phpTime);
            $out .= "queries: {$queries}".$prefix;
            $out .= "queryTime: {$queryTime}".$prefix;
            $out .= "phpTime: {$phpTime}".$prefix;
        }

        $out .= "TotalTime: {$totalTime}".$prefix;


        return $out;
    }


    public function setMode()
    {
        if (isset($_GET['mode'])) {
            $this->mode = (bool)$_GET['mode'];
        }
    }

    /**
     * Првоерка выброса ответа в место возвращения массива
     * @return bool
     */
    public function isEnabledException()
    {
        return $this->enabledException;
    }

    /**
     * Get a configuration option for this service
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function getOption($key, $default = null)
    {
        return array_key_exists($key, $this->config) ? $this->config[$key] : $default;
    }

    /**
     * Get the correct controller path for the class
     *
     * @return string
     */
    protected function getController(string $expectedFile)
    {
        $basePath = $this->getOption('basePath');
        $controllerClassPrefix = $this->getOption('controllerClassPrefix', 'modController');
        $controllerClassSeparator = $this->getOption('controllerClassSeparator', '_');
        $controllerClassFilePostfix = $this->getOption('controllerClassFilePostfix', '.php');

        /* handle [object]/[id] pathing */
        $expectedArray = explode('/', $expectedFile);


        if (empty($expectedArray)) {
            $expectedArray = array(rtrim($expectedFile, '/').'/');
        }
        $id = array_pop($expectedArray);
        if (!file_exists($basePath.$expectedFile.$controllerClassFilePostfix) && !empty($id)) {
            $expectedFile = implode('/', $expectedArray);
            if (empty($expectedFile)) {
                $expectedFile = $id;
                $id = null;
            }
            $this->requestPrimaryKey = $id;
        }

        foreach ($this->iterateDirectories($basePath.'/*'.$controllerClassFilePostfix, GLOB_NOSORT) as $controller) {
            $controller = $basePath != '/' ? str_replace($basePath, '', $controller) : $controller;
            $controller = trim($controller, '/');
            $controllerFile = str_replace(array($controllerClassFilePostfix), array(''), $controller);
            $controllerClass = str_replace(array('/', $controllerClassFilePostfix), array($controllerClassSeparator, ''), $controller);
            if (strnatcasecmp($expectedFile, $controllerFile) == 0) {
                require_once $basePath.$controller;

                return $controllerClassPrefix.$controllerClassSeparator.$controllerClass;
            }
        }
        $this->modx->log(modX::LOG_LEVEL_INFO, 'Could not find expected controller: '.$expectedFile);

        return null;
    }

    /**
     * Iterate across directories looking for files based on a pattern
     *
     * @param  string  $pattern
     * @param  int  $flags
     * @return array
     */
    public function iterateDirectories($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);
        $dirs = glob(dirname($pattern).'/*', GLOB_ONLYDIR | GLOB_NOSORT);

        if ($dirs) {
            foreach ($dirs as $dir) {
                $files = array_merge($files, $this->iterateDirectories($dir.'/'.basename($pattern), $flags));
            }
        }

        return $files;
    }


    /**
     * @param $message
     */
    public function log_error($message)
    {
        $backtrace = debug_backtrace();
        $FILE = isset($backtrace[0]['file']) ? $backtrace[0]['file'] : __FILE__;
        $LINE = isset($backtrace[0]['line']) ? $backtrace[0]['line'] : __LINE__;
        $this->modx->log(modX::LOG_LEVEL_ERROR, '[Crontab] '.$message, '', '', $FILE, $LINE);
    }


    /**
     * Проверка завершения времени
     * @return boolean;
     */
    public function timeIsOver()
    {
        $max_exec_time = $this->config['max_exec_time'];
        $exec_time = microtime(true) - $this->getOption('start_time');
        if ($exec_time + 1 >= $max_exec_time) {
            $this->ForcedStop = true;

            return true;
        }

        return false;
    }


    private $manualStopExecutionPath = null;

    /**
     * Вызвать прерывание задания
     */
    public function manualStopExecution()
    {
        if ($this->manualStopExecutionPath) {
            if (file_exists($this->manualStopExecutionPath)) {
                echo 'Ручная остановка выполнения задания<br>';
                unlink($this->manualStopExecutionPath);
                $this->response($this->GetUsage());
            }
        }
    }

    /**
     * Велючаем выброс
     */
    public function enableEnabledException()
    {
        $this->enabledException = true;
    }


    public function version()
    {
        return $this->option('version');
    }

    public function option($key, $options = null, $default = null, $skipEmpty = false)
    {
        $key = 'crontabmanager_'.$key;

        return $this->modx->getOption($key, $options, $default, $skipEmpty);
    }
}
