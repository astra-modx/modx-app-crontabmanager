<?php

/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 23.04.2023
 * Time: 14:54
 */
trait CreateRule
{
    /**
     * @return bool
     */
    public function beforeSet()
    {

        // Получение значений полей
        $task_id = $this->getProperty('task_id');
        $class = $this->getProperty('class');
        $chatId = $this->getProperty('chat_id');
        $token = $this->getProperty('token');
        $email = $this->getProperty('email');
        $url = $this->getProperty('url');
        $method_http = $this->getProperty('method_http');
        $params = $this->getProperty('params');
// вверхний регистр
        $method_http = strtoupper($method_http);
        $this->setProperty('method_http', $method_http);

        // Валидация полей
        if (empty($class)) {
            $this->modx->error->addField('class', $this->modx->lexicon('crontabmanager_task_rule_err_class'));
        }

        if ($class === 'Telegram') {
            if (empty($chatId)) {
                $this->modx->error->addField('chat_id', $this->modx->lexicon('crontabmanager_task_rule_err_chat_id'));
            }

            if (empty($token)) {
                $this->modx->error->addField('token', $this->modx->lexicon('crontabmanager_task_rule_err_token'));
            }
            if (empty($method_http)) {
                $this->modx->error->addField('method_http', $this->modx->lexicon('crontabmanager_task_rule_err_method_http'));
            } else {
                switch ($method_http) {
                    case 'GET':
                    case 'POST':
                        break;
                    default:
                        $this->modx->error->addField('method_http', $this->modx->lexicon('crontabmanager_task_rule_err_method_http'));
                        break;
                }
            }
        } elseif ($class === 'Email') {
            if (empty($email)) {
                $this->modx->error->addField('email', $this->modx->lexicon('crontabmanager_task_rule_err_email'));
            }
        } elseif ($class === 'Webhook') {
            if (empty($url)) {
                $this->modx->error->addField('url', $this->modx->lexicon('crontabmanager_task_rule_err_url'));
            }
        }


        if (!empty($params)) {
            $params = $this->modx->fromJSON($params);
            if (!is_array($params)) {
                $this->modx->error->addField('params', $this->modx->lexicon('crontabmanager_task_rule_err_params'));
            }
        }


        $ar = ['active', 'active', 'fails', 'fails_after_successful', 'fails_new_problem', 'successful', 'successful_after_failed'];
        foreach ($ar as $v) {
            $this->setCheckbox($v);
        }

        $this->getTasks();
        return parent::beforeSet();
    }


    public $tasks = [];

    public function getTasks()
    {
        $this->tasks = null;
        $tmp = $this->getProperty('categories');
        if (!empty($tmp)) {
            if (!is_array($tmp)) {
                $categories = $this->modx->fromJSON($tmp);
                if (count($categories)) {
                    $this->tasks = $this->modx->fromJSON($tmp);
                }
            } else {
                $this->tasks = $tmp;
            }
        }
    }


    public function afterSave()
    {

        if ($this->tasks) {
            $this->object->updateTasks($this->tasks);
        }
        return parent::afterSave();
    }
}
