<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 23.04.2023
 * Time: 20:25
 */

namespace Webnitros\CronTabManager\Event;


use CronTabManagerNotification;
use CronTabManagerTask;

abstract class Providers
{
    /* @var CronTabManagerNotification $task */
    public $notification;
    /* @var CronTabManagerTask $task */
    public $task;

    public function __construct(CronTabManagerNotification $notification)
    {
        $this->task = $notification->getOne('Task');
        $this->notification = $notification;
    }

}
