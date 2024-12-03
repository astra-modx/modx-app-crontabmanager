<?php

/**
 * Check if cron is running
 */
class CronTabManagerAddScheduleProcessor extends modProcessor
{
    /**
     * @return array|string
     */
    public function process()
    {
        /* @var CronTabManager $CronTabManager */
        $CronTabManager = $this->modx->getService('crontabmanager', 'CronTabManager', MODX_CORE_PATH.'components/crontabmanager/model/');
        $response = $CronTabManager->artisan()->copyBasePath();
        if ($response !== true) {
            return $this->failure($response);
        }

        return $this->success();
    }
}

return 'CronTabManagerAddScheduleProcessor';
