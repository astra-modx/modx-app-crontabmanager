<?php
require_once(dirname(__FILE__) . '/update.class.php');
/**
 * Disable an Task
 */
class CronTabManagerTaskAddCronProcessor extends CronTabManagerTaskUpdateProcessor
{
    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->properties = array(
            'cron_enable' => true,
        );
        return true;
    }
}
return 'CronTabManagerTaskAddCronProcessor';
