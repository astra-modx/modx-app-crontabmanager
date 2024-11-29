<?php

use Cron\CronExpression;
use Webnitros\CronTabManager\Crontab;

if (!class_exists('CrontabManagerManual')) {
    include_once dirname(dirname(dirname(__FILE__))).'/lib/crontab/CrontabManagerManual.php';
}

class CrontabManagerManualScheduleWork extends CrontabManagerManual
{
    /**
     * Replaces cron contents
     *
     * @return CrontabManagerManual
     * @throws \UnexpectedValueException
     */
    protected function _replaceCronContents()
    {
        # $ret = file_put_contents($this->file_crontab_path, $this->cronContent);
        # if (!$ret) {
        #     throw new \UnexpectedValueException(
        #         'Не удалось записать' . "\n" . $this->cronContent, $ret
        #     );
        # }
        return $this;
    }


    /**
     * List current cron jobs
     *
     * @return string
     * @throws \UnexpectedValueException
     */
    public function listJobs()
    {
        global $modx;

        $content = [];

        $command = CronTabManagerPhpExecutable($modx);

        $Crontab = new Crontab();
        /* @var CronTabManagerTask $object */
        $q = $modx->newQuery('CronTabManagerTask');
        $q->where(array(
            'active' => 1,
        ));
        if ($objectList = $modx->getCollection('CronTabManagerTask', $q)) {
            foreach ($objectList as $object) {
                $time = $Crontab->cronTime($object, '	    ');
                #$path = $object->path_task;
                $cli = $object->getPath();
                $log = $object->getFileLogPath();
                $content[] = $time.'	'.$command.' '.$cli.' > '.$log.' 2>&1 &';
            }
        }

        return implode("\n", $content);
    }


}
