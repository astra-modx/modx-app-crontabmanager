<?php
include_once MODX_CORE_PATH . 'components/crontabmanager/processors/mgr/task/rule/CreateRule.php';
/**
 * Create an Task
 */
class CronTabManagerRuleCreateProcessor extends modObjectCreateProcessor
{
    use CreateRule;

    /* @var CronTabManagerRule $object */
    public $object = 'CronTabManagerRule';
    public $objectType = 'CronTabManagerRule';
    public $classKey = 'CronTabManagerRule';
    public $languageTopics = array('crontabmanager:manager');
    public $permission = 'crontabmanager_create';


    /** {@inheritDoc} */
    public function initialize()
    {
        if (!$this->modx->hasPermission($this->permission)) {
            return $this->modx->lexicon('access_denied');
        }
        return parent::initialize();
    }
}

return 'CronTabManagerRuleCreateProcessor';
