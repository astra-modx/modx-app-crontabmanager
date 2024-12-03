<?php

/**
 * Remove an Task
 */
class CronTabManagerRuleRemoveProcessor extends modObjectRemoveProcessor
{
    public $objectType = 'CronTabManagerRule';
    public $classKey = 'CronTabManagerRule';
    public $languageTopics = array('crontabmanager:manager');
    public $permission = 'crontabmanager_remove';

    /** {@inheritDoc} */
    public function initialize()
    {
        if (!$this->modx->hasPermission($this->permission)) {
            return $this->modx->lexicon('access_denied');
        }
        return parent::initialize();
    }
}

return 'CronTabManagerRuleRemoveProcessor';
