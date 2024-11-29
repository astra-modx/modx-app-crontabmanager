<?php

/**
 * Get an Task
 */
class CronTabManagerTaskSendProcessor extends modObjectGetProcessor
{
    /* @var CronTabManagerTask $object */
    public $object;
    public $objectType = 'CronTabManagerTask';
    public $classKey = 'CronTabManagerTask';
    public $languageTopics = array('crontabmanager:default');
    public $permission = 'crontabmanager_view';

    /**
     * We doing special check of permission
     * because of our objects is not an instances of modAccessibleObject
     *
     * @return mixed
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }


        $id = $this->getProperty('id');

        return parent::process();
    }

}

return 'CronTabManagerTaskSendProcessor';
