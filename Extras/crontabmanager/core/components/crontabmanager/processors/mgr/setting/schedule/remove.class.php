<?php

/**
 * Check if cron is running
 */
class CronTabManagerScheduleAddProcessor extends modProcessor
{

    /**
     * @return array|string
     */
    public function process()
    {
        /* @var CronTabManager $CronTabManager */
        $CronTabManager = $this->modx->getService('crontabmanager', 'CronTabManager', MODX_CORE_PATH.'components/crontabmanager/model/');

        $this->modx->lexicon->load('crontabmanager:manager');
        if (cronTabManagerIsAvailable()) {
            $Schedule = $CronTabManager->addSchedule();
            $response = $Schedule->remove();
            if ($response !== true) {
                return $this->failure($response);
            }
        } else {
            return $this->failure($this->modx->lexicon('crontabmanager_check_crontab_avalable_error'));
        }

        return $this->success();
    }
}

return 'CronTabManagerScheduleAddProcessor';
