<?php

/**
 * Get a list of Tasks
 */
class CronTabManagerRuleGetListProcessor extends modObjectGetListProcessor
{
    /* @var CronTabManager $CronTabManager */
    public $CronTabManager = null;
    public $objectType = 'CronTabManagerRule';
    public $classKey = 'CronTabManagerRule';
    public $defaultSortField = 'createdon';
    public $defaultSortDirection = 'DESC';
    public $permission = 'crontabmanager_list';
    public $languageTopics = array('crontabmanager:manager');

    /** {@inheritDoc} */
    public function initialize()
    {
        if (!$this->modx->hasPermission($this->permission)) {
            return $this->modx->lexicon('access_denied');
        }
        return parent::initialize();
    }

    /**
     * * We doing special check of permission
     * because of our objects is not an instances of modAccessibleObject
     *
     * @return boolean|string
     */
    public function beforeQuery()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }
        return true;
    }


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $orderColumns = $this->modx->getSelectColumns('CronTabManagerRule', 'CronTabManagerRule', '', array(), false);
        $c->select($orderColumns);

        $completed = $this->setCheckbox('active');
        if (!empty($completed)) {
            $c->where(array('active' => 0));
        }

        $task_id = (int)$this->getProperty('task_id');
        if (!empty($task_id)) {
            $c->innerJoin('CronTabManagerRuleMemberTask', 'CronTabManagerRuleMemberTask', 'CronTabManagerRuleMemberTask.rule_id = CronTabManagerRule.id');
            $c->where(array('CronTabManagerRuleMemberTask.task_id' => $task_id));
        }
        return $c;
    }


    public function prepareRow(xPDOObject $object)
    {
        /* @var CronTabManagerRule $object */
        $array = $object->toArray();
        $array['actions'] = array();


        /*
         *     <field key="fails" dbtype="tinyint" precision="1" phptype="boolean" null="true" default="1"/>
        <field key="fails_after_successful" dbtype="tinyint" precision="1" phptype="boolean" null="true" default="0"/>
        <field key="fails_new_problem" dbtype="tinyint" precision="1" phptype="boolean" null="true" default="0"/>
        <field key="successful" dbtype="tinyint" precision="1" phptype="boolean" null="true" default="0"/>
        <field key="successful_after_failed" dbtype="tinyint" precision="1" phptype="boolean" null="true" default="0"/>



        */
        $arrays = $object->get(['fails', 'fails_after_successful', 'fails_new_problem', 'successful', 'successful_after_failed']);

        $notice = null;
        foreach ($arrays as $k => $value) {
            $lex = $this->modx->lexicon('crontabmanager_task_rule_' . $k);
            if ($value == 1) {
                $notice[$lex] = '<i class="icon icon-check"></i>';
            }
        }

        $array['notice'] = $notice;


        // Edit
        $array['actions'][] = array(
            'cls' => '',
            'icon' => 'icon icon-edit',
            'title' => $this->modx->lexicon('crontabmanager_task_rule_update'),
            'action' => 'updateItem',
            'button' => true,
            'menu' => true,
        );


        // Remove
        $array['actions'][] = array(
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('crontabmanager_task_rule_remove'),
            'multiple' => $this->modx->lexicon('crontabmanager_task_rule_remove'),
            'action' => 'removeItem',
            'button' => false,
            'menu' => true,
        );

        return $array;
    }

}

return 'CronTabManagerRuleGetListProcessor';
