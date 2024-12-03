<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 03.12.2024
 * Time: 14:26
 */

namespace Webnitros\CronTabManager;


use CronTabManager;
use CrontabManagerManual;
use UnexpectedValueException;


class AddSchedule
{
    private CronTabManager $cronTabManager;
    private CrontabManagerManual $crontab;
    protected $command = 'schedule:run';
    protected $hash = 'CRONTABMANAGER';
    private string $schedulerPath;

    public function __construct(CronTabManager $cronTabManager)
    {
        $this->cronTabManager = $cronTabManager;
        if (!class_exists('CrontabManagerManual')) {
            require $this->cronTabManager->option('corePath').'/lib/crontab/CrontabManagerManual.php';
        }
        $this->crontab = new CrontabManagerManual();
        $this->schedulerPath = $this->cronTabManager->option('schedulerPath').'/artisan';
    }

    public function command()
    {
        return $this->command;
    }

    public function hash()
    {
        return $this->hash;
    }

    public function schedulerPath()
    {
        return $this->schedulerPath;
    }

    public function add()
    {
        $hash = $this->hash();
        $cli = CronTabManagerPhpExecutable($this->cronTabManager->modx)." {$this->schedulerPath()} {$this->command()} 2>&1 #{$hash}";
        try {
            $job = $this->crontab->newJob();
            $job->on('* * * * *')->doJob($cli);
            $this->crontab->add($job);
            $this->crontab->save();
        } catch (UnexpectedValueException $e) {
            return $e->getMessage();
        }

        return true;
    }

    public function remove()
    {
        // Ищим задание по ID или по контроллеру
        if ($this->crontab->deleteJob($this->hash())) {
            $this->crontab->save(false);

            return true;
        }

        return false;
    }

    public function has()
    {
        return !empty($this->find());
    }


    /**
     * Вернет список заданий
     * @return array|bool
     */
    public function getList()
    {
        $response = $this->crontab->listJobs();
        if (!empty($response)) {
            $jobs = explode("\n", $response); // get the old jobs
            $jobs = array_filter($jobs);
            if (count($jobs) > 0) {
                return $jobs;
            }
        }

        return false;
    }

    /**
     * @return false|string
     */
    public function find()
    {
        if ($jobs = $this->getList()) {
            foreach ($jobs as $oneJob) {
                if ($oneJob != '') {
                    if (strripos($oneJob, $this->hash()) !== false) {
                        return substr($oneJob, -6);
                    }
                }
            }
        }

        return false;
    }
}
