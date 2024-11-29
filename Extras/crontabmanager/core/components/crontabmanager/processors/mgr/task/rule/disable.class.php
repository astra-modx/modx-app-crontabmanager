<?php
require_once(dirname(__FILE__) . '/update.class.php');
/**
 * Disable an Task
 */
class CronTabManagerRuleDisableProcessor extends CronTabManagerRuleUpdateProcessor
{
    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->properties = array(
            'active' => false,
        );
        return true;
    }
}
return 'CronTabManagerRuleDisableProcessor';
