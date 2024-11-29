<?php

class CronTabManagerNotification extends xPDOSimpleObject
{

    /**
     * {@inheritdoc}
     */
    public function save($cacheFlag = null)
    {
        if ($this->isNew()) {
            if (empty($this->get('createdon'))) {
                $this->set('createdon', time());
            }
        } else {
            $this->set('updatedon', time());
        }
        return parent::save();
    }


    public function send()
    {
        $params = $this->get('params');
        $class = $this->get('class');
        $event = $this->get('event');

        /*  $Rule = $this->getOne('Rule');
          $Task = $this->getOne('Task');
          $Log = $this->getOne('Log');*/


        if (empty($class)) {
            throw new Exception("Empty class");
        }

        switch ($class) {
            case 'Email':
                $Message = new \Webnitros\CronTabManager\Event\Providers\Email($this);
                break;
            case 'Telegram':
                $Message = new \Webnitros\CronTabManager\Event\Providers\Telegram($this);
                break;
            case 'Webhook':
                $Message = new \Webnitros\CronTabManager\Event\Providers\Webhook($this);
                break;
            default:
                break;
        }

        $delivery = false;
        try {
            $response = $Message->handleEvent($event, $params);
            if ($response === true) {
                $response = 'Успешно';
            }
        } catch (Exception $e) {
            $response = $e->getMessage();
        }
        $response = substr($response, 0, 1000);

        $this->set('response', $response);
        $this->set('send', true);
        $this->set('delivery', $delivery);


        return $this->save();
    }
}
