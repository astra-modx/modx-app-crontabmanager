<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 30.11.2024
 * Time: 16:59
 */


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
                $php_executable = PHP_BINDIR.'/php';
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
            $LogAnalyzer->run();
            $Log = $LogAnalyzer->getLastLog();

            // Проверяем что у задания первая неудачная попытка
            $countFailedAttempts = $LogAnalyzer->getCountFailedAttempts();

            // Проверяка неудачной попытке завершения
            // Если задание не завершилось и включена опция перезапуска
            if ($countFailedAttempts === 1) {
                // Только если задание не завершилось успешно ПЕРВЫЙ раз
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
                        $sql = "UPDATE {$table} SET ignore_action = '1'  WHERE id = ".$Log['id'];
                        $task->xpdo->exec($sql);

                        // Проверяем что у задания есть путь
                        $path = $task->getPathCli();
                        $path_log = $task->getFileLogPath();

                        // Запуская задание повторно
                        $command = $path.' > '.$path_log.' 2>&1';

                        // делаем задержку иначе почему то функция shell_exec отрабатывает не верно с логом
                        sleep(1);

                        shell_exec($command);

                        $run = false;
                    }
                }
            }

            if ($run) {
                //  Проверяем что у задания есть флаг перезапуска
                if ($task->get('restart')) {
                    // Сбрасываем флаг перезапуска у задания
                    $task->updateFlag('restart', false);
                }


                $events = $LogAnalyzer->process();


                $isMute = \Webnitros\CronTabManager\Mute::check($LogAnalyzer, $task);


                if (!$isMute) {
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
                                'rule_id' => $ruleId,
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
}

if (!function_exists('cronTabManagerIsAvailable')) {
    /**
     * Проверка доступности crontab
     * @return bool
     */
    function cronTabManagerIsAvailable()
    {
        // Проверяем, доступна ли функция exec
        if (function_exists('exec') && is_callable('exec')) {
            // Проверяем, не отключена ли функция в php.ini
            $disabled_functions = explode(',', ini_get('disable_functions'));
            if (!in_array('exec', $disabled_functions)) {
                exec('crontab -l', $output, $returnVar);

                return $returnVar === 0;
            }
        }

        return false;
    }
}

if (!function_exists('cronTabManagerCurrentUser')) {
    /**
     * Вернет текущего пользователя
     * @return bool
     */
    function cronTabManagerCurrentUser()
    {
        if (function_exists('get_current_user') && is_callable('get_current_user')) {
            return get_current_user();
        }

        return !empty($_SERVER['USER']) ? $_SERVER['USER'] : null;
    }
}
