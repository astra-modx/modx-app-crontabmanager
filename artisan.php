#!/usr/bin/env php
<?php
/* @var SchedulerService $scheduler */
define('MODX_CRONTAB_MODE', true);
require_once __DIR__.'/core/scheduler/index.php';


$Artisan = new \Webnitros\CronTabManager\Builder($scheduler);
$application = $Artisan->application();
$Artisan->addContainers($Artisan->commands());
$application->run(empty($argv[1]));



