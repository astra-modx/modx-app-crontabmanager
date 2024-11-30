<?php
require_once(dirname(__FILE__) . '/update.class.php');
/**
 * Disable an Task
 */
class CronTabManagerTaskMuteProcessor extends CronTabManagerTaskUpdateProcessor
{
    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->properties = array(
            'mute' => false,
            'mute_success' => false,
            'mute_time' => 0,
        );
        return true;
    }
}
return 'CronTabManagerTaskMuteProcessor';
