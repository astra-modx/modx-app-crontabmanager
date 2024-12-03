<?php

if (!class_exists('CrontabManagerManual')) {
    include_once dirname(dirname(dirname(__FILE__))).'/model/crontabmanagerhandler.class.php';
}

class CrontabManagerHandlerFile extends CrontabManagerHandler implements CrontabManagerHandlerInterface
{
    protected $loadClass = 'File';
}
