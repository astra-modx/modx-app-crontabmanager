<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 23.04.2023
 * Time: 13:06
 */

namespace Webnitros\CronTabManager\Event;

use modX;

interface EventSubscriber
{
    public function handleEvent($eventType, $data);
}
