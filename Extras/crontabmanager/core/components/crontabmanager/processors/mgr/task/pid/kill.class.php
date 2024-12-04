<?php

/**
 * Kill pid
 */
class CronTabManagerTaskPidKillProcessor extends modObjectProcessor
{
    /* @var CronTabManager $CronTabManager */
    public $CronTabManager = null;
    public $objectType = 'CronTabManagerTask';
    public $classKey = 'CronTabManagerTask';
    public $languageTopics = array('crontabmanager:manager');
    #public $permission = 'crontabmanager_add_blocked';


    /** {@inheritDoc} */
    public function initialize()
    {
        if (!$this->modx->hasPermission($this->permission)) {
            return $this->modx->lexicon('access_denied');
        }
        $this->CronTabManager = $this->modx->getService('crontabmanager', 'CronTabManager', MODX_CORE_PATH.'components/crontabmanager/model/');

        return parent::initialize();
    }


    /**
     * @return array|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $id = (int)$this->getProperty('id');

        if (empty($id)) {
            return $this->failure($this->modx->lexicon('crontabmanager_task_err_ns'));
        }

        /** @var CronTabManagerTask $object */
        if ($object = $this->modx->getObject($this->classKey, $id)) {
            $res = $object->pid()->kill();
            if ($res !== true) {
                return $this->failure($res);
            }
        } else {
            return $this->failure($this->modx->lexicon('crontabmanager_task_err_nf'));
        }

        return $this->success($this->modx->lexicon('crontabmanager_task_pid_kill_success'));
    }
}

return 'CronTabManagerTaskPidKillProcessor';
