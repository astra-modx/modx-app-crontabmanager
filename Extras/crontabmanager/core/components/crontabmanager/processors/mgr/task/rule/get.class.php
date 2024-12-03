<?php

/**
 * Get an Task
 */
class CronTabManagerRuleGetProcessor extends modObjectGetProcessor
{
    public $objectType = 'CronTabManagerRule';
    public $classKey = 'CronTabManagerRule';
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

        return parent::process();
    }

    /**
     * Return the response
     * @return array
     */
    public function cleanup()
    {
        $array = $this->object->toArray('', true);
        $cat = $this->object->excludeTasks();
        $array['categories'] = empty($cat) ? '{}' : $this->toJSON($this->object->excludeTasks());
        return $this->success('', $array);
    }
}

return 'CronTabManagerRuleGetProcessor';
