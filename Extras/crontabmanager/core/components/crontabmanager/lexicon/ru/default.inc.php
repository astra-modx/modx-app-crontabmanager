<?php
include_once 'setting.inc.php';

$_lang['crontabmanager'] = 'CronTabManager';
$_lang['crontabmanager_menu_desc'] = 'Управление крон заданиями';


$_lang['crontabmanager_not_log_content'] = 'Еще не было логов';


####################################
################## fails

$_lang['crontabmanager_email_fails_subject'] = '[Crontab] превышен лимит ошибок task_id:[[+task_id]] controller: [[+task_path_task]]';
$_lang['crontabmanager_email_fails_message'] = 'Требуется внимание администратора для исправления запуска cron задания<br>
[[+add_output]]
<h2>Task Id [[+task_id]]</h2>
Категория: <em style="background-color: #eee; padding: 3px"><b>[[+task_category_name]]</b></em><br>
Путь к контроллеру: <em style="background-color: #eee; padding: 3px"><b>[[+task_path_task]]</b></em><br>
Описание задания: <em style="background-color: #eee; padding: 3px"><b>[[+task_description]]</b></em><br>
Время запуска: <em style="background-color: #eee; padding: 3px"><b>[[+task_time]]</b></em><br>
Последняя удачная попытака завершена в: <em style="background-color: #eee; padding: 3px"><b>[[+task_end_run]]</b></em><br>
Лог файл cron: <em style="background-color: #eee; padding: 3px"><b>[[+task_file_log]]</b></em><br>
Логи: <a href="[[+log_url]]?hash=[[+hash]]">открыть в браузере</a><br>
';


####################################
################## successful

$_lang['crontabmanager_email_successful_after_failed_message'] = '[Crontab] Успех, задание восстановлено task_id:[[+task_id]] controller: [[+task_path_task]]';
$_lang['crontabmanager_email_successful_subject'] = '[Crontab] Успех, задание выполнено task_id:[[+task_id]] controller: [[+task_path_task]]';
$_lang['crontabmanager_email_successful_message'] = 'На предыдущее сообщение об ошибке cron задания было получено уведомление об успешном завершении<br>
<h2>Task Id [[+task_id]]</h2>
Категория: <em style="background-color: #eee; padding: 3px"><b>[[+task_category_name]]</b></em><br>
Путь к контроллеру: <em style="background-color: #eee; padding: 3px"><b>[[+task_path_task]]</b></em><br>
Описание задания: <em style="background-color: #eee; padding: 3px"><b>[[+task_description]]</b></em><br>';

