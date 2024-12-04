<?php

$_lang['area_crontabmanager_main'] = 'Main';
$_lang['area_crontabmanager_path'] = 'Directories with scheduler';
$_lang['area_crontabmanager_blocking'] = 'Locking';
$_lang['area_crontabmanager_rest'] = 'REST for the application';

$_lang['setting_crontabmanager_scheduler_path'] = 'Main directory with classes';
$_lang['setting_crontabmanager_scheduler_path_desc'] = 'This is where all classes are stored';



$_lang['setting_crontabmanager_link_path'] = 'Directory for controller links';
$_lang['setting_crontabmanager_link_path_desc'] = 'Directory for storing controller links';

$_lang['setting_crontabmanager_lock_path'] = 'Directory with lock files';
$_lang['setting_crontabmanager_lock_path_desc'] = 'Directory for storing lock files to avoid repeated launches';

$_lang['setting_crontabmanager_log_path'] = 'Directory with logs';
$_lang['setting_crontabmanager_log_path_desc'] = 'During cron runs, task logs will be saved in this directory';

// Main
$_lang['setting_crontabmanager_php_command'] = 'PHP command to run cron tasks';
$_lang['setting_crontabmanager_php_command_desc'] = 'Empty by default. Depending on the version and file location, you can specify your custom file path. Leave empty to allow automatic detection of the path';

$_lang['setting_crontabmanager_set_completion_time'] = 'Record task start and stop time';
$_lang['setting_crontabmanager_set_completion_time_desc'] = 'Default is Yes. If set to No, times and logs will not be recorded';
$_lang['setting_crontabmanager_allow_blocking_tasks'] = 'Allow time-based blocking';
$_lang['setting_crontabmanager_allow_blocking_tasks_desc'] = 'Default is Yes. If set to No, tasks cannot be blocked for a specific time period';
$_lang['setting_crontabmanager_max_minuts_blockup'] = 'Maximum number of minutes for blocking';
$_lang['setting_crontabmanager_max_minuts_blockup_desc'] = 'Default is 1440 minutes (a day). Tasks cannot be blocked for more than the specified number of minutes';

$_lang['setting_crontabmanager_user_id'] = 'Run tasks as user';
$_lang['setting_crontabmanager_user_id_desc'] = 'Default is 1. During task execution, all operations will be performed under this user';

$_lang['setting_crontabmanager_log_storage_time'] = 'Default log storage duration';
$_lang['setting_crontabmanager_log_storage_time_desc'] = 'By default, all tasks will store logs for 10080 minutes. The log storage duration can be customized for each task individually.';

$_lang['setting_crontabmanager_email_administrator'] = 'Administrator email for notifications';
$_lang['setting_crontabmanager_email_administrator_desc'] = 'If the set number of task run attempts is exceeded, an email will be automatically generated and sent to the listed addresses';

$_lang['setting_crontabmanager_blocking_time_minutes'] = 'Timeout before automatic unlocking';
$_lang['setting_crontabmanager_blocking_time_minutes_desc'] = 'Default is 1 minute. If the task was not completed during execution, on the next run the blocking file start time will be checked. If it exceeds the specified number of minutes, the blocking file will be automatically removed';

$_lang['setting_crontabmanager_rest_enable'] = 'Enable REST for the application';
$_lang['setting_crontabmanager_rest_enable_desc'] = 'Set Yes if you want the CronTabManager application to be able to interact with the site';

$_lang['setting_crontabmanager_rest_client_id'] = 'Client ID';
$_lang['setting_crontabmanager_rest_client_id_desc'] = 'Unique application identifier for site authorization from the application';

$_lang['setting_crontabmanager_rest_controller'] = 'Path to the REST controller';
$_lang['setting_crontabmanager_rest_controller_desc'] = 'You can specify a custom path. Default is assets/components/crontabmanager/rest.php';

$_lang['setting_crontabmanager_save_to_file'] = 'Allow saving crons to a file';
$_lang['setting_crontabmanager_save_to_file_desc'] = 'Set Yes to allow all saved cron tasks to be written to the file core/scheduler/crontabs/USER';
