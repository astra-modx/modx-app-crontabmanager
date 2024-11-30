<?php

include_once 'setting.inc.php';

$_lang['crontabmanager'] = 'CronTabManager';
$_lang['crontabmanager_menu_desc'] = 'Cron job management';

$_lang['crontabmanager_intro_msg'] = 'You can select multiple tasks using Shift or Ctrl.';

$_lang['crontabmanager_grid_search'] = 'Search';
$_lang['crontabmanager_grid_actions'] = 'Actions';
$_lang['crontabmanager_rules'] = 'Rules';
$_lang['crontabmanager_rules_intro_msg'] = 'List of cron job rules for notifications in case of errors or successful task execution.';

# Tab
$_lang['crontabmanager_task'] = 'Task';
$_lang['crontabmanager_task_log'] = 'Logs';
$_lang['crontabmanager_task_setting'] = 'Settings';
$_lang['crontabmanager_task_notice'] = 'Email Notifications';
$_lang['crontabmanager_task_tab_code'] = 'Custom Code';

# Tasks
$_lang['crontabmanager_tasks'] = 'Cron Schedule';
$_lang['crontabmanager_task_id'] = 'Id';
$_lang['crontabmanager_task_path_task_your'] = 'Custom execution file';
$_lang['crontabmanager_task_path_task_your_desc'] = 'Provide the absolute path to the file to be executed.';
$_lang['crontabmanager_task_message_desc'] = 'This message will be added at the beginning of the notification email.';
$_lang['crontabmanager_task_name'] = 'Name';
$_lang['crontabmanager_task_name_desc'] = 'Short name';
$_lang['crontabmanager_task_add_output_email'] = 'Include output in email message';
$_lang['crontabmanager_task_add_output_email_desc'] = 'Add log output to the email message';
$_lang['crontabmanager_task_next_run'] = 'Next Run';
$_lang['crontabmanager_task_createdon'] = 'Created On';
$_lang['crontabmanager_task_updatedon'] = 'Updated On';
$_lang['crontabmanager_task_date_start'] = 'Start Date';
$_lang['crontabmanager_task_description'] = 'Task Description';
$_lang['crontabmanager_task_path_task'] = 'File Path';
$_lang['crontabmanager_task_path_task_desc'] = '<em>Specify the scheduler controller path in the format: report/count.php. All controllers are located in: scheduler/Controllers/</em>';
$_lang['crontabmanager_task_lock_file'] = 'Lock File';
$_lang['crontabmanager_task_last_run'] = 'Last Run';
$_lang['crontabmanager_task_end_run'] = 'Finished';
$_lang['crontabmanager_task_status'] = 'Status';
$_lang['crontabmanager_task_pid'] = 'PID';
$_lang['crontabmanager_task_time'] = 'Run Time';
$_lang['crontabmanager_task_start_task'] = 'Start Task';
$_lang['crontabmanager_task_minutes'] = 'Minutes';
$_lang['crontabmanager_task_hours'] = 'Hours';
$_lang['crontabmanager_task_days'] = 'Days';
$_lang['crontabmanager_task_months'] = 'Months';
$_lang['crontabmanager_task_weeks'] = 'Weekdays';
$_lang['crontabmanager_task_processor'] = 'Processor Path';
$_lang['crontabmanager_task_reboot'] = 'Restart';
$_lang['crontabmanager_task_disable'] = 'Disable Task';
$_lang['crontabmanager_task_remove'] = 'Delete Task';
$_lang['crontabmanager_task_active'] = 'Active';
$_lang['crontabmanager_task_category_name'] = 'Category';
$_lang['crontabmanager_task_completed'] = 'Completed Successfully';
$_lang['crontabmanager_task_notification_enable'] = 'Send error notification on task failure';
$_lang['crontabmanager_task_notification_enable_desc'] = 'An email notification is sent to the site admin after the maximum number of failed attempts is reached.';
$_lang['crontabmanager_task_notification_emails'] = 'Additional notification emails';
$_lang['crontabmanager_task_notification_emails_desc'] = 'Comma-separated. Email addresses of administrators to notify upon exceeding the maximum number of task failures.';
$_lang['crontabmanager_task_max_number_attempts'] = 'Max Number of Failed Attempts';
$_lang['crontabmanager_task_max_number_attempts_desc'] = 'Set to 0 to disable. After the maximum number of failures is reached, the admin is notified via email.';
$_lang['crontabmanager_task_is_blocked'] = 'Blocked';
$_lang['crontabmanager_task_is_blocked_time'] = 'Blocked By Time';
$_lang['crontabmanager_task_mode_develop'] = 'Enable Development Mode for the Task';
$_lang['crontabmanager_task_mode_develop_desc'] = 'Check to activate debugging mode, allowing the task to restart without blocking or sending email notifications.';
$_lang['crontabmanager_task_log_storage_time'] = 'Log Retention Period';
$_lang['crontabmanager_task_log_storage_time_desc'] = 'Specify the log retention period. Logs older than the specified number of minutes will be automatically deleted. Set to "0" to retain logs indefinitely.';
$_lang['crontabmanager_task_blockup_minutes_add'] = 'The task is blocked for "[[+minutes]]" minutes';
$_lang['crontabmanager_task_err_add_crontab'] = 'Failed to add task controller [[+task_path]] to crontab.';
$_lang['crontabmanager_task_err_remove_crontab'] = 'Failed to remove task controller [[+task_path]] from crontab.';
$_lang['CronTabManagerTask_err_remove'] = 'Failed to delete the task because the cron job was not removed.';
$_lang['crontabmanager_show_crontabs'] = 'View crontabs';
$_lang['crontabmanager_show_pids'] = 'View pids';
$_lang['crontabmanager_task_active_desc'] = 'Uncheck to disable the cron task in the scheduler and prevent it from running in the background.';
$_lang['crontabmanager_task_path_task_desc'] = 'For example, if the controller is located at the root, its path will be: "core/scheduler/Controllers/count.php".';

// Task Log
$_lang['crontabmanager_task_log_id'] = 'id';
$_lang['crontabmanager_task_log_last_run'] = 'Start';
$_lang['crontabmanager_task_log_end_run'] = 'Stop';
$_lang['crontabmanager_task_log_completed'] = 'Completed';
$_lang['crontabmanager_task_log_ignore_action'] = 'Ignore';
$_lang['crontabmanager_task_log_notification'] = 'Notification';
$_lang['crontabmanager_task_log_memory_usage'] = 'Memory Usage';
$_lang['crontabmanager_task_log_exec_time'] = 'Exec Time';
$_lang['crontabmanager_task_log_auto_pause'] = 'Auto Pause';
$_lang['crontabmanager_task_log_pause'] = 'Pause Schedule';
$_lang['crontabmanager_task_log_createdon'] = 'Created On';
$_lang['crontabmanager_task_log_updatedon'] = 'Updated On';
$_lang['crontabmanager_task_un_look'] = 'Task Unlock';
$_lang['crontabmanager_task_err_ae_controller'] = 'Failed to find the controller file at the specified path: [[+controller]]';
$_lang['crontabmanager_task_year_err_ae_controller'] = 'Failed to find the controller file at the specified path: [[+controller]]. You are using a custom controller path, so you need to add the absolute path to the file';
$_lang['crontabmanager_task_removeLog'] = 'Delete Crontab Log File';
$_lang['crontabmanager_task_removelog_confirm'] = 'Are you sure you want to delete the Crontab log file?';
$_lang['crontabmanager_task_copyTask'] = 'Copy CLI Path';
$_lang['crontabmanager_task_copyTask_success'] = 'Path successfully copied';
$_lang['crontabmanager_time_server'] = 'Server Time';

// Action
$_lang['crontabmanager_task_create'] = 'Create Task';
$_lang['crontabmanager_task_update'] = 'Update Task';
$_lang['crontabmanager_task_enable'] = 'Enable Task';
$_lang['crontabmanager_tasks_enable'] = 'Enable Task';
$_lang['crontabmanager_tasks_disable'] = 'Disable Task';
$_lang['crontabmanager_tasks_remove'] = 'Remove Task';
$_lang['crontabmanager_task_unlock'] = 'Unlock Task';
$_lang['crontabmanager_task_start'] = 'Start Task';
$_lang['crontabmanager_task_unblockup'] = 'Reset Lock Time';
$_lang['crontabmanager_task_readlog'] = 'Log of the Last Crontab Execution';
$_lang['crontabmanager_task_manualstop'] = 'Manually Stop Task';
$_lang['crontabmanager_task_manualstop_confirm'] = 'Are you sure you want to manually stop this task execution?';

$_lang['crontabmanager_task_unblockup_confirm'] = 'Are you sure you want to reset the lock time for this task?';
$_lang['crontabmanager_task_starttask_confirm'] = 'Are you sure you want to start this task?';
$_lang['crontabmanager_task_unlock_confirm'] = 'Are you sure you want to unlock this task?';
$_lang['crontabmanager_task_remove_confirm'] = 'Are you sure you want to remove this task?';

$_lang['crontabmanager_task_err_path_task'] = 'You must specify the path to the controller.';
$_lang['crontabmanager_task_err_ae'] = 'A task with this name already exists.';
$_lang['crontabmanager_task_err_nf'] = 'Task not found.';
$_lang['crontabmanager_task_err_ns'] = 'Task not specified.';
$_lang['crontabmanager_task_err_remove'] = 'Error removing task.';
$_lang['crontabmanager_task_err_save'] = 'Error saving task.';
$_lang['crontabmanager_task_err_ns_minutes'] = 'Please specify the number of minutes for task blocking.';
$_lang['crontabmanager_task_err_ns_max_minuts_blockup'] = 'The maximum number of minutes for task blocking should not exceed: [[+max_minuts_blockup]] minutes.';
$_lang['crontabmanager_task_err_ns_allow_blocking_tasks'] = 'Task blocking is disabled!';

#  Category category
$_lang['crontabmanager_categories'] = 'Categories';
$_lang['crontabmanager_categories_intro_msg'] = 'Categories for tasks are used for filtering';

$_lang['crontabmanager_category_id'] = 'ID';
$_lang['crontabmanager_category_name'] = 'Name';
$_lang['crontabmanager_category_description'] = 'Description';
$_lang['crontabmanager_category_active'] = 'Active';
$_lang['crontabmanager_category_err_sub_id'] = 'You must select a subscriber.';
$_lang['crontabmanager_category_err_nf'] = 'Item not found.';
$_lang['crontabmanager_category_err_ns'] = 'Item not specified.';
$_lang['crontabmanager_category_err_remove'] = 'Error while removing item.';
$_lang['crontabmanager_category_err_save'] = 'Error while saving item.';

$_lang['crontabmanager_category_disable'] = 'Disable category';
$_lang['crontabmanager_category_create'] = 'Create category';
$_lang['crontabmanager_category_update'] = 'Update category';
$_lang['crontabmanager_category_enable'] = 'Enable category';
$_lang['crontabmanager_categories_enable'] = 'Enable categories';
$_lang['crontabmanager_categories_disable'] = 'Disable categories';
$_lang['crontabmanager_categories_remove'] = 'Remove categories';
$_lang['crontabmanager_category_remove'] = 'Remove category';

$_lang['crontabmanager_category_remove_confirm'] = 'Are you sure you want to delete this category?';
$_lang['crontabmanager_categories_remove_confirm'] = 'Are you sure you want to delete these categories?';

# Filter
$_lang['crontabmanager_task_parent'] = 'Category';
$_lang['crontabmanager_task_parent_empty'] = 'Select a category';
$_lang['crontabmanager_task_filter_active'] = 'Active';
$_lang['crontabmanager_task_filter_mode_develop'] = 'In development';
$_lang['crontabmanager_task_log_remove'] = 'Delete log';
$_lang['crontabmanager_task_logs_remove'] = 'Delete logs';
$_lang['crontabmanager_cron_connector_run_task_windows'] = 'Run task';
$_lang['crontabmanager_cron_connector_run_task_windows_btn'] = 'Restart';
$_lang['crontabmanager_cron_connector_unlock'] = 'Unlock task';
$_lang['crontabmanager_cron_connector_unlock_btn'] = 'Unlock';
$_lang['crontabmanager_cron_connector_read_log'] = 'Read log file';
$_lang['crontabmanager_cron_connector_read_log_btn'] = 'Read log file';
$_lang['crontabmanager_cron_connector_args'] = 'Arguments in the form user=1 resource=2';

# Notification
$_lang['crontabmanager_notifications'] = 'Notification Center';
$_lang['crontabmanager_notifications_intro_msg'] = 'List of task completion error notifications';

$_lang['crontabmanager_notification_id'] = 'Id';
$_lang['crontabmanager_notification_name'] = 'Name';
$_lang['crontabmanager_notification_event'] = 'Event';
$_lang['crontabmanager_notification_rule'] = 'Rule';
$_lang['crontabmanager_notification_rule_id'] = 'Rule Id';
$_lang['crontabmanager_notification_read'] = 'Read';
$_lang['crontabmanager_notification_send_email'] = 'Sent to email';
$_lang['crontabmanager_notification_createdon'] = 'Creation Date';
$_lang['crontabmanager_notification_rule_class'] = 'Handler';
$_lang['crontabmanager_notification_processing'] = 'Processing';
$_lang['crontabmanager_notification_send'] = 'Sent';
$_lang['crontabmanager_notification_delivery'] = 'Delivery';
$_lang['crontabmanager_notification_response'] = 'Response';
$_lang['crontabmanager_notification_action_send'] = 'Send';
$_lang['crontabmanager_notification_active'] = 'Active';
$_lang['crontabmanager_notification_err_sub_id'] = 'You must select a subscriber.';
$_lang['crontabmanager_notification_err_nf'] = 'Item not found.';
$_lang['crontabmanager_notification_err_ns'] = 'Item not specified.';
$_lang['crontabmanager_notification_err_remove'] = 'Error removing the notification.';
$_lang['crontabmanager_notification_err_save'] = 'Error saving the notification.';

$_lang['crontabmanager_notifications_remove'] = 'Remove notifications';
$_lang['crontabmanager_notification_remove'] = 'Remove notification';

$_lang['crontabmanager_notification_send_confirm'] = 'Are you sure you want to send this Notification?';
$_lang['crontabmanager_notification_remove_confirm'] = 'Are you sure you want to remove this Notification?';
$_lang['crontabmanager_notifications_remove_confirm'] = 'Are you sure you want to remove these Notifications?';
$_lang['crontabmanager_notification_filter_read'] = 'Unread';

////////////////////////
//// Install
////////////////////////
$_lang['crontabmanager_button_install'] = 'Install component';
$_lang['crontabmanager_button_download'] = 'Download component';
$_lang['crontabmanager_button_download_encryption'] = 'Download component with encryption';

$_lang['crontabmanager_when_every_day'] = 'Every day';
$_lang['crontabmanager_when_weekdays'] = 'Weekdays';
$_lang['crontabmanager_when_weekends'] = 'Weekends';
$_lang['crontabmanager_when_monday'] = 'Monday';
$_lang['crontabmanager_when_tuesday'] = 'Tuesday';
$_lang['crontabmanager_when_wednesday'] = 'Wednesday';
$_lang['crontabmanager_when_thursday'] = 'Thursday';
$_lang['crontabmanager_when_friday'] = 'Friday';
$_lang['crontabmanager_when_saturday'] = 'Saturday';
$_lang['crontabmanager_when_sunday'] = 'Sunday';
$_lang['crontabmanager_auto_pause_from'] = 'from';
$_lang['crontabmanager_auto_pause_to'] = 'to';

// Auto Pause
$_lang['crontabmanager_task_rule'] = 'Rules';
$_lang['crontabmanager_task_rule_id'] = 'ID';
$_lang['crontabmanager_task_rule_task_id'] = 'Task ID';
$_lang['crontabmanager_task_rule_createdon'] = 'Created';
$_lang['crontabmanager_task_rule_updatedon'] = 'Updated';
$_lang['crontabmanager_task_rule_message'] = 'Short description for message hint';
$_lang['crontabmanager_task_rule_message_desc'] = 'Maximum 500 characters. Hint for the user in the message';
$_lang['crontabmanager_task_rule_active'] = 'Active';
$_lang['crontabmanager_task_rule_name'] = 'Rule name';
$_lang['crontabmanager_task_rule_all'] = 'All tasks';
$_lang['crontabmanager_task_rule_all_desc'] = 'Check to apply the rule to all tasks';

####
$_lang['crontabmanager_task_rule_create'] = 'Add rule';
$_lang['crontabmanager_task_rule_update'] = 'Update';
$_lang['crontabmanager_task_rule_remove'] = 'Delete';
$_lang['crontabmanager_task_rule_remove_confirm'] = 'Are you sure you want to delete this auto-pause?';
$_lang['crontabmanager_task_rules_remove_confirm'] = 'Are you sure you want to delete this auto-pause?';
$_lang['crontabmanager_task_rules_remove'] = 'Delete';

$_lang['crontabmanager_task_rule_err_when'] = 'Specify when to run';
$_lang['crontabmanager_task_rule_err_from'] = 'Specify the hours and minutes';
$_lang['crontabmanager_task_rule_err_to'] = 'Specify the hours and minutes';

/**
 * Build
 */
$_lang['crontabmanager_notification_fail'] = 'Task Failure';
$_lang['crontabmanager_notification_notify_first_fail'] = 'Notify only on the first task failure after a successful run';
$_lang['crontabmanager_notification_notify_new_problem'] = 'Notify only about new issues or a new failed test for the task';
$_lang['crontabmanager_notification_success'] = 'Task Completed Successfully';
$_lang['crontabmanager_notification_notify_first_success'] = 'Notify only on the first successful completion after a failure';
$_lang['crontabmanager_notification_first_build_error'] = 'First occurrence of a task error';
$_lang['crontabmanager_notification_build_start'] = 'Task execution has started';
$_lang['crontabmanager_task_rule_class'] = 'Notification Type';

$_lang['crontabmanager_task_rule_categories_desc'] = 'Select the tasks for which the rule will apply';
$_lang['crontabmanager_task_rule_create'] = 'Create Rule';
$_lang['crontabmanager_task_rule_update'] = 'Update Rule';
$_lang['crontabmanager_task_rule_class'] = 'Notification Type';
$_lang['crontabmanager_task_rule_method_http'] = 'Data Sending Method';
$_lang['crontabmanager_task_rule_params'] = 'Parameters in JSON format';
$_lang['crontabmanager_task_rule_token'] = 'token - generated by the bot';
$_lang['crontabmanager_task_rule_chat_id'] = 'Chat ID - provided by the bot';
$_lang['crontabmanager_task_rule_email'] = 'E-mail';
$_lang['crontabmanager_task_rule_url'] = 'URL - where to send requests';
$_lang['crontabmanager_task_rule_notice'] = 'Notifications';

$_lang['crontabmanager_task_rule_err_categories'] = 'Select the tasks for which the rule will apply';
$_lang['crontabmanager_task_rule_err_class'] = 'Specify the notification type';
$_lang['crontabmanager_task_rule_err_api_key'] = 'Specify the API key';
$_lang['crontabmanager_task_rule_err_chat_id'] = 'Specify the chat ID';
$_lang['crontabmanager_task_rule_err_token'] = 'Specify the token';
$_lang['crontabmanager_task_rule_err_email'] = 'Specify the email';
$_lang['crontabmanager_task_rule_err_url'] = 'Specify the URL';
$_lang['crontabmanager_task_rule_err_method_http'] = 'Specify the method (GET or POST)';
$_lang['crontabmanager_task_rule_err_params'] = 'Parameters are incorrect, they must be in JSON format';

$_lang['crontabmanager_task_rule_fails'] = 'Task failed';
$_lang['crontabmanager_task_rule_fails_after_successful'] = '- Notify only about the first failure after a successful run';
$_lang['crontabmanager_task_rule_fails_new_problem'] = '- Notify only about a new issue with the task';

$_lang['crontabmanager_task_rule_successful'] = 'Task completed successfully';
$_lang['crontabmanager_task_rule_successful_after_failed'] = '- Notify only about the first successful completion after a failure';
$_lang['crontabmanager_task_rule_criteria_desc'] = 'Check the criteria that must be met for the notification to be sent.';
$_lang['crontabmanager_task_rule_tasks'] = 'Tasks';

$_lang['crontabmanager_task_restart_after_failure'] = 'Restart task after failure';
$_lang['crontabmanager_task_restart_after_failure_desc'] = 'If set, the task will be restarted after a failure. However, if the task fails again, no notification will be sent. Notifications will be sent only after the second failure.';
$_lang['crontabmanager_crontab_available'] = 'The <b>crontab</b> command is available';
$_lang['crontabmanager_crontab_not_available'] = 'The <b>crontab</b> command is not available';

$_lang['crontabmanager_next_run_human'] = 'Next: [[+minutes]] minutes[[+hours]]';
$_lang['crontabmanager_next_run_human_hours'] = ' and [[+hours]] hours';


$_lang['crontabmanager_next_run_human_seconds'] = 'through [[+seconds]] seconds';
$_lang['crontabmanager_next_run_human_minutes'] = 'through [[+minutes]] minutes';
$_lang['crontabmanager_next_run_human_hours'] = 'through [[+hours]] hours';
$_lang['crontabmanager_next_run_human_days'] = 'through [[+days]] days';
$_lang['crontabmanager_button_help'] = 'CrontabManager Documentation';
