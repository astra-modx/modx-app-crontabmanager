<?php

include_once 'setting.inc.php';

$_lang['crontabmanager'] = 'CronTabManager';
$_lang['crontabmanager_menu_desc'] = 'Управление крон заданиями';


$_lang['crontabmanager_not_log_content'] = 'Еще не было логов';


####################################
################## fails

$_lang['crontabmanager_email_fails_subject'] = '[Crontab] превышен лимит ошибок task_id:[[+task_id]] controller: [[+task_path_task]]';

####################################
################## successful
$_lang['crontabmanager_email_successful_after_failed_message'] = '[Crontab] Успех, задание восстановлено task_id:[[+task_id]] controller: [[+task_path_task]]';
$_lang['crontabmanager_email_successful_subject'] = '[Crontab] Успех, задание выполнено task_id:[[+task_id]] controller: [[+task_path_task]]';
