<?php

/**
 * Disable an Task
 */
class CronTabManagerTaskMuteProcessor extends modProcessor
{
    public $languageTopics = array('crontabmanager:manager');
    public $permission = 'crontabmanager_list';
    /**
     * @return bool
     */
    public function process()
    {
        $id = $this->getProperty('id');
        $mute_time = $this->getProperty('mute_time');

        $mute_time = strtotime($mute_time);


        /* @var CronTabManagerTask $object */
        if (!$object = $this->modx->getObject('CronTabManagerTask', $id)) {
            return $this->failure($this->modx->lexicon('crontabmanager_task_err_get_task'));
        }


        // Проверяем что текущее время меньше времени мута
        $time = time();
        if ($time > $mute_time) {
            return $this->failure($this->modx->lexicon('crontabmanager_task_err_time_current'));
        }


        $res = $object->muteTime($mute_time);
        if ($res !== true) {
            return $this->failure($res);
        }
        return $this->success();
    }
}

return 'CronTabManagerTaskMuteProcessor';
