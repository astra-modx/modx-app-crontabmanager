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

class Webhook extends Providers implements EventSubscriber
{
    public function handleEvent($eventType, $data)
    {
        // отправка email уведомления
        $url = $data['url'];
        if (empty($url)) {
            throw new Exception("Empty url");
        }


        $modx = $this->task->xpdo;
        // отправка email уведомления
        $site_url = $modx->getOption('site_url') . 'manager/?a=home&namespace=crontabmanager';

        $data['site_url'] = $site_url;
        $data['message'] = $this->notification->get('message');
        $data['path_task'] = $this->task->get('path_task');

        $data['output_log'] = '';
        /* @var CronTabManager $CronTabManager */
        $CronTabManager = $modx->getService('crontabmanager', 'CronTabManager', MODX_CORE_PATH . 'components/crontabmanager/model/');

        if ($Log = $this->notification->getOne('Log')) {
            $response = $CronTabManager->runProcessor('mgr/task/readlog', array(
                'return' => true,
                'id' => $Log->get('task_id'),
            ));
            $log = $response->response['object']['content'];

            if (!empty($log)) {
                if ($log != '<pre></pre>') {
                    // Ограничиваем до 1000 символов
                    $log = mb_substr($log, 0, 1000);

                    // Разбиваем текст на строки по символу переноса строки
                    $lines = explode("\n", wordwrap($log, 100));

                    // Ограничиваем количество строк до 15
                    if (count($lines) > 15) {
                        $lines = array_slice($lines, 0, 15);
                    }

                    // Собираем строки обратно в текст
                    $log = implode("\n", $lines);

                    $start = strpos($log, "<pre>");
                    $end = strpos($log, "</pre>");

                    // Если теги найдены, вырезаем текст между ними
                    if ($start !== false && $end !== false) {
                        $log = substr($log, 0, $start) . substr($log, $end + 6);
                    }

                    $data['output_log'] = (string)$log;
                }
            }
        }


        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);

        curl_close($curl);


        if ($err) {
            throw new Exception("cURL Error #:" . $err);
        }

        if ($http_code !== 200) {
            throw new Exception("cURL status code #:" . $http_code);
        }

        return $response;
    }
}
