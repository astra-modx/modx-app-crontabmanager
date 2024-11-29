<?php

/* @var string $hash */
/* @var CronTabManager $CronTabManager */
/* @var CronTabManagerTaskLog $Log */
include_once dirname(__FILE__) . '/_action.php';

function isValidMd5x($md5)
{
    return preg_match('/^[a-f0-9]{32}$/', $md5);
}

$hash = $modx->getOption('hash', $_GET, '');
if (empty($hash)) {
    echo 'Empty hash';
}

if (isValidMd5x($hash)) {
    /* @var CronTabManagerTaskLog $object */
    if ($Log = $modx->getObject('CronTabManagerTaskLog', ['hash' => $hash])) {
        $response = $CronTabManager->runProcessor('mgr/task/readlog', array(
            'return' => true,
            'id' => $Log->get('task_id'),
        ));
        $log = $response->response['object']['content'];
        if (!empty($response->response['message'])) {
            $log = $response->response['message'];
        }
        echo $log;
    } else {
        die('Not found log');
    }
} else {
    echo "Строка НЕ является корректным MD5 хешем.";
}

@session_write_close();
exit();
