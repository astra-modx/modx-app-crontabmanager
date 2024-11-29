<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 23.04.2023
 * Time: 17:26
 */

namespace Webnitros\CronTabManager\Event;


use CronTabManagerTask;
use PDO;
use Webnitros\CronTabManager\Analyzer\LogAnalyzer;

class Rules
{

    public function __construct(CronTabManagerTask $task, array $rules)
    {
        $this->task = $task;
        $this->rules = $rules;
    }

    public function process()
    {
        $ruleIds = [];
        $q = $this->task->xpdo->newQuery('CronTabManagerRuleMemberTask');
        $q->select('rule_id');
        $q->where(array(
            'task_id' => $this->task->get('id'),
        ));
        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $ruleIds[] = $row['rule_id'];
            }
        }


        $rules = null;
        $fields = ['fails', 'fails_after_successful', 'fails_new_problem', 'successful', 'successful_after_failed'];


        if ($ruleIds) {
            $cr = [
                'id:IN' => $ruleIds,
                'OR:all:=' => 1,
            ];
        } else {
            $cr = [
                'all' => 1,
            ];
        }

        $q = $this->task->xpdo->newQuery('CronTabManagerRule');
        $q->select($this->task->xpdo->getSelectColumns('CronTabManagerRule', 'CronTabManagerRule'));
        $q->where([
            $cr,
            array(
                'active' => 1,
            )
        ]);
        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $c = [];
                foreach ($fields as $criterion) {
                    $c[$criterion] = (bool)$row[$criterion];
                }
                if ($c['fails_after_successful'] || $c['fails_new_problem']) {
                    $c['fails'] = false;
                }

                if ($c['successful_after_failed']) {
                    $c['successful'] = false;
                }

                $row['criteria'] = $c;
                $rules[] = $row;
            }
        }

        if ($rules && is_array($rules)) {

            foreach ($rules as &$rule) {
                $criteria = $rule['criteria'];
                $event = null;
                foreach ($fields as $field) {
                    $val1 = (bool)$criteria[$field];
                    $val2 = (bool)$this->rules[$field];
                    if ($val1 === true && $val2 === true) {
                        $event = $field;
                    }
                }
                $rule['event'] = $event;
                $rule['task_id'] = $this->task->get('id');
            }
        }
        return $rules;
    }
}
