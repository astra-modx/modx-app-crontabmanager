<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 23.04.2023
 * Time: 13:06
 */

namespace Webnitros\CronTabManager\Event\Providers;


use CronTabManager;
use Exception;
use modX;
use Webnitros\CronTabManager\Event\EventSubscriber;
use Webnitros\CronTabManager\Event\Providers;
use xPDO;

class Email extends Providers implements EventSubscriber
{
    public function handleEvent($eventType, $data)
    {
        // отправка email уведомления
        /* @var CronTabManager $CronTabManager */
        $CronTabManager = $this->task->xpdo->getService('crontabmanager', 'CronTabManager', MODX_CORE_PATH.'components/crontabmanager/model/');

        $emails = $data['email'];
        if (empty($emails)) {
            throw new Exception("Empty emails");
        }

        $emails = is_array($emails) ? $emails : explode(',', $emails);
        $emails = array_map('trim', $emails);

        $emails = array_filter($emails, function ($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        });
        if (empty($emails)) {
            throw new Exception("Empty emails");
        }


        $modx = $this->task->xpdo;

        $site_url = $modx->getOption('site_url');
        $blockup_url = $site_url.'assets/components/crontabmanager/action/blockup.php';
        $log_url = $site_url.'assets/components/crontabmanager/action/log.php';


        $Log = $this->notification->getOne('Log');
        //task->
        $data = array(
            'site_url' => $site_url,
            'blockup_url' => $blockup_url,
            'task_id' => $this->task->get('id'),
            'task_category_name' => ($tmp = $this->task->loadCategory()) ? $tmp->get('name') : '',
            'task_last_run' => $this->task->get('last_run'),
            'task_end_run' => date('d-m-Y H:i:s', strtotime($this->task->get('end_run'))),
            'task_path_task' => $this->task->get('path_task'),
            #'task_max_number_attempts' => $max_number_attempts,
            'task_description' => $this->task->get('description'),
            'task_time' => implode(' ', $this->task->get(array('minutes', 'hours', 'days', 'months', 'weeks'))),
            'task_file_log' => $this->task->getFileLogPath(),
            'log_url' => $log_url,
            'hash' => $Log ? $Log->get('hash') : '',
            'add_output' => '',
        );


        switch ($eventType) {
            case 'successful':
                $subject = $CronTabManager->modx->lexicon('crontabmanager_email_successful_subject', $data);
                $message = $CronTabManager->chunk('email/successful.tpl', $data);
                break;
            case 'successful_after_failed':
                $subject = $CronTabManager->modx->lexicon('crontabmanager_email_successful_after_failed_message', $data);
                $message = $CronTabManager->chunk('email/successful.tpl', $data);
                break;
            case 'fails':
            case 'fails_after_successful':
            case 'fails_new_problem':
                $subject = $CronTabManager->modx->lexicon('crontabmanager_email_fails_subject', $data);
                $message = $CronTabManager->chunk('email/fails.tpl', $data);
                break;
        }


        $response = $CronTabManager->sendEmail($emails, $subject, $message);
        if (!$response['success']) {
            throw new Exception("[CronTabManager] Error send email notofocation");
        }


        return true;
    }
}
