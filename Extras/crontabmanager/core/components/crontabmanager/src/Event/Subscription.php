<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 23.04.2023
 * Time: 13:05
 */

namespace Webnitros\CronTabManager\Event;


class Subscription
{
    private $subscribers = [];

    public function subscribe($eventType, $subscriber)
    {
        if (!isset($this->subscribers[$eventType])) {
            $this->subscribers[$eventType] = [];
        }

        $this->subscribers[$eventType][] = $subscriber;
    }

    public function notify($eventType, $data)
    {
        if (isset($this->subscribers[$eventType])) {
            foreach ($this->subscribers[$eventType] as $subscriber) {
                $subscriber->handleEvent($eventType, $data);
            }
        }
    }

    public function getSubscribers($eventType)
    {
        return $this->subscribers[$eventType];
    }

    public function getSubscribersCount($eventType)
    {
        return count($this->subscribers[$eventType]);
    }

    public function getSubscribersList($eventType)
    {
        $list = [];
        foreach ($this->subscribers[$eventType] as $subscriber) {
            $list[] = get_class($subscriber);
        }
        return $list;
    }

    public function subscribers()
    {
        return $this->subscribers;
    }
}
