<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 04.12.2024
 * Time: 13:01
 */

namespace Webnitros\CronTabManager\Task;


use CronTabManagerTask;

class Status
{
    private CronTabManagerTask $task;

    public function __construct(CronTabManagerTask $task)
    {
        $this->task = $task;
    }

    public function get()
    {
        $Pid = $this->task->pid();
        #$completed = $this->task->get('completed');
        $status = $Pid->status();


        /*switch ($status) {
            case 'completed':
                if (!$completed) {
                    return 'completed_error';
                }
                break;
            default:
                break;
        }*/

        return $status;
    }

}
