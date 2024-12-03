<?php

/**
 * Get an Task
 */
class CronTabManagerTaskGetProcessor extends modObjectGetProcessor
{
    public $objectType = 'CronTabManagerTask';
    public $classKey = 'CronTabManagerTask';
    public $languageTopics = array('crontabmanager:default', 'crontabmanager:cron');
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

    public function cleanup()
    {
        /* @var CronTabManager $CronTabManager */
        $CronTabManager = $this->modx->getService('CronTabManager', 'CronTabManager', MODX_CORE_PATH.'components/crontabmanager/model/');

        $lexicon = $CronTabManager->lexiconCronTime();
        $values = $this->object->toArray();
        $time = [
            'minutes',
            'hours',
            'days',
            'months',
            'weeks',
        ];
        foreach ($time as $t) {
            if (!empty($lexicon[$t])) {
                $data = $lexicon[$t];
                $value = $values[$t];

                $find = false;
                foreach ($data as $d) {
                    if ($d['value'] == $value) {
                        $find = true;
                    }
                }

                if ($find) {
                    $values[$t.'_condition'] = $values[$t];
                } else {
                    $values[$t.'_condition'] = 'my';
                }
            }
        }

        if ($values['parent'] == 0) {
            $values['parent'] = null;
        }

        return $this->success('', $values);
    }
}

return 'CronTabManagerTaskGetProcessor';
