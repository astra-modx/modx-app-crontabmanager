<?php

/**
 * Check if cron is running
 */
class CronTabManagerAddArtisanProcessor extends modProcessor
{
    /**
     * @return array|string
     */
    public function process()
    {
        if (cronTabManagerIsAvailable()) {
            $CronTabManager = $this->modx->getService('crontabmanager', 'CronTabManager', MODX_CORE_PATH.'components/crontabmanager/model/');
            $Schedule = $CronTabManager->addSchedule();
            $response = $Schedule->add();
            if ($response !== true) {
                return $this->failure($response);
            }
        } else {
            return $this->failure('Linux CronTab is not available');
        }

        return $this->success();
    }
}

return 'CronTabManagerAddArtisanProcessor';
