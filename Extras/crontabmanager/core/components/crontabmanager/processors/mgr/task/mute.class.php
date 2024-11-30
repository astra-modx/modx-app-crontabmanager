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
            'mute' => true,
            'mute_success' => true,
        );
        return true;
    }
}
return 'CronTabManagerTaskMuteProcessor';
