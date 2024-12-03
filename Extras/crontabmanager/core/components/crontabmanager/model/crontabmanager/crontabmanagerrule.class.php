<?php

class CronTabManagerRule extends xPDOSimpleObject
{
    /**
     * {@inheritdoc}
     */
    public function save($cacheFlag = null)
    {
        if ($this->isNew()) {
            if (empty($this->get('createdon'))) {
                $this->set('createdon', time());
            }
        } else {
            $this->set('updatedon', time());
        }
        return parent::save();
    }



    /**
     * @param array $categories
     * @return void
     */
    public function updateTasks(array $categories)
    {
        $id = $this->get('id');
        $table = $this->xpdo->getTableName('CronTabManagerRuleMemberTask');
        $this->xpdo->exec("DELETE FROM {$table} WHERE rule_id = {$id}");


        if (!empty($categories)) {
            foreach ($categories as $task_id => $check) {
                if ((bool)$check) {
                    $count = $this->xpdo->getCount('CronTabManagerTask', $task_id);

                    if ($count > 0) {
                        /* @var CronTabManagerRuleMemberTask $object */
                        $object = $this->xpdo->newObject('CronTabManagerRuleMemberTask');
                        $object->set('rule_id', $id);
                        $object->set('task_id', $task_id);
                        $object->save();
                    }
                }
            }
        }
    }

    protected $tasks;

    public function excludeTasks()
    {
        if (!$this->tasks) {
            $this->tasks = [];
            $q = $this->xpdo->newQuery('CronTabManagerRuleMemberTask');
            $q->select('task_id');
            $q->where(array(
                'rule_id' => $this->get('id'),
            ));
            if ($q->prepare() && $q->stmt->execute()) {
                while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $this->tasks[(int)$row['task_id']] = 1;
                }
            }
        }
        return $this->tasks;
    }
}
