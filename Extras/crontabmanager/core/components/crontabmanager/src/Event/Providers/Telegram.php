<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 23.04.2023
 * Time: 13:06
 */

namespace Webnitros\CronTabManager\Event\Providers;


use Exception;
use modX;
use Webnitros\CronTabManager\Event\EventSubscriber;
use Webnitros\CronTabManager\Event\Providers;

class Telegram extends Providers implements EventSubscriber
{
    public function handleEvent($eventType, $data)
    {
        $modx = $this->task->xpdo;
        // отправка email уведомления
        $site_url = $modx->getOption('site_url') . 'manager/?a=home&namespace=crontabmanager';
        $path = $this->task->get('path_task');

        $token = $data['token'];
        $chatId = $data['chat_id'];
        $message = $data['message'];

        // Сообщения для Telegram
        $prefix = PHP_EOL . 'Контролер:' . $path . PHP_EOL . 'Site: ' . $site_url;
        switch ($eventType) {
            case 'successful':
                $message = 'Фоновое задание завершено успешно';
                break;
            case 'successful_after_failed':
                $message = 'Фоновое задание завершено успешно после провального выполнения';
                break;
            case 'fails':
                $message = 'Не удалось выполнить фоновое задание';
                break;
            case 'fails_after_successful':
                $message = 'Первая ошибка после успешного выполнения';
                break;
            case 'fails_new_problem':
                $message = 'Ошибка, новая проблема с фоновым заданием';
                break;
        }
        $message .= $prefix;
        $msg = $this->notification->get('message');
        if (!empty($msg)) {
            $message .= PHP_EOL . $msg;
        }


        $url = 'https://api.telegram.org/bot' . $token . '/sendMessage';
        $data = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'Markdown',
        ];

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type:application/x-www-form-urlencoded\r\n",
                'content' => http_build_query($data),
            ],
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $result = json_decode($result, true);


        if ($result['ok'] === false) {
            throw new Exception('Telegram notification failed:' . $result['text'] ?? '');
        }
        return 'date:' . $result['result']['date'];
    }
}
