<?php

/**
 * Check if cron is running
 */
class CronTabManagerPingCronProcessor extends modProcessor
{
    /**
     * @return array|string
     */
    public function process()
    {
        if (cronTabManagerIsAvailable()) {
            return $this->success($this->modx->lexicon('crontabmanager_crontab_available'));
        } else {
            return $this->failure($this->modx->lexicon('crontabmanager_crontab_not_available'));
        }
    }
}

return 'CronTabManagerPingCronProcessor';
