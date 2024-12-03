<?php

include_once 'setting.inc.php';

$_lang['crontabmanager'] = 'CronTabManager';
$_lang['crontabmanager_menu_desc'] = 'Управление крон заданиями';

$_lang['crontabmanager_intro_msg'] = 'Вы можете выделять сразу несколько заданий при помощи Shift или Ctrl.';

$_lang['crontabmanager_grid_search'] = 'Поиск';
$_lang['crontabmanager_grid_actions'] = 'Действия';
$_lang['crontabmanager_rules'] = 'Правила';
$_lang['crontabmanager_rules_intro_msg'] = 'Список правил для крон заданий для уведомлений в случае ошибки или успешного выполнения задания.';


# Tab
$_lang['crontabmanager_task'] = 'Задание';
$_lang['crontabmanager_task_log'] = 'Логи';
$_lang['crontabmanager_task_setting'] = 'Настройки';
$_lang['crontabmanager_task_notice'] = 'Уведомления на E-mail';
$_lang['crontabmanager_task_tab_code'] = 'Свой код';

# Taks

$_lang['crontabmanager_tasks'] = 'Расписание крон';
$_lang['crontabmanager_task_id'] = 'Id';
$_lang['crontabmanager_task_path_task_your'] = 'Свой файл для исполнения';
$_lang['crontabmanager_task_path_task_your_desc'] = 'Укажите абсолютный путь к файл который должен исполниться';
$_lang['crontabmanager_task_message_desc'] = 'В начале письма с уведомлением добавиться это сообщение';
$_lang['crontabmanager_task_name'] = 'Наименование';
$_lang['crontabmanager_task_name_desc'] = 'Короткое наименование';
$_lang['crontabmanager_task_add_output_email'] = 'Добавить вывод в сообщение на email';
$_lang['crontabmanager_task_add_output_email_desc'] = 'В сообщение на email добавиться вывод из лог файла';
$_lang['crontabmanager_task_next_run'] = 'Следующий запуск';
$_lang['crontabmanager_task_createdon'] = 'Дата создания';
$_lang['crontabmanager_task_updatedon'] = 'Дата обновления';
$_lang['crontabmanager_task_date_start'] = 'Дата запуска';
$_lang['crontabmanager_task_description'] = 'Описание задания';
$_lang['crontabmanager_task_description_placeholder'] = 'Краткое описание что делает задание';
$_lang['crontabmanager_task_path_task'] = 'Путь к файлу';
$_lang['crontabmanager_task_path_task_desc'] = '<em>Укажите путь к контроллеру в планировщик в виде: report/count.php. Все контроллеры расположены в: core/scheduler/Controllers/</em>';
$_lang['crontabmanager_task_path_task_placeholder'] = 'например demo.php (относительно пути core/scheduler/Controllers/)';
$_lang['crontabmanager_task_lock_file'] = 'Файл блокировки';
$_lang['crontabmanager_task_last_run'] = 'Последний запуск';
$_lang['crontabmanager_task_end_run'] = 'Завершен';
$_lang['crontabmanager_task_status'] = 'Статус';
$_lang['crontabmanager_task_pid'] = 'PID';
$_lang['crontabmanager_task_time'] = 'Время запуска';
$_lang['crontabmanager_task_start_task'] = 'Запустить задание';
$_lang['crontabmanager_task_minutes'] = 'Минута';
$_lang['crontabmanager_task_hours'] = 'Час';
$_lang['crontabmanager_task_days'] = 'День';
$_lang['crontabmanager_task_months'] = 'Месяц';
$_lang['crontabmanager_task_weeks'] = 'День недели';
$_lang['crontabmanager_task_processor'] = 'Путь к процессору';
$_lang['crontabmanager_task_reboot'] = 'Перезапустить';
$_lang['crontabmanager_task_disable'] = 'Отключить задание';
$_lang['crontabmanager_task_remove'] = 'Удалить задание';
$_lang['crontabmanager_task_active'] = 'Включено';
$_lang['crontabmanager_task_category_name'] = 'Категория';
$_lang['crontabmanager_task_completed'] = 'Успешно';
$_lang['crontabmanager_task_notification_enable'] = 'Отправлять уведомление в случае ошибки выполнения задания';
$_lang['crontabmanager_task_notification_enable_desc'] = 'После наступления максимального числа неудачных попыток, администратору сайта отправляется письма с уведомление';
$_lang['crontabmanager_task_notification_emails'] = 'Доп. email для уведомлений';
$_lang['crontabmanager_task_notification_emails_desc'] = 'Через запятую. Адреса администраторов по которым будет отправлено сообщени о превышении неудачных попыток завершения выполнения задания';
$_lang['crontabmanager_task_max_number_attempts'] = 'Макс. число неудачных попыток';
$_lang['crontabmanager_task_max_number_attempts_desc'] = 'Оставить 0 для отключения. После наступления максимального числа неудачных попыток, администратору сайта отправляется письма с уведомление';
$_lang['crontabmanager_task_is_blocked'] = 'Заблокировано';
$_lang['crontabmanager_task_is_blocked_time'] = 'Заблокировано по времени';
$_lang['crontabmanager_task_mode_develop'] = 'Включить режим разработки для задания';
$_lang['crontabmanager_task_mode_develop_desc'] = 'Установите флажок для активации режима отладки, чтобы при повторном запуске не было блокировки и не отправлялись уведомления на электронную почту.';
$_lang['crontabmanager_task_log_storage_time'] = 'Срок хранения логов';
$_lang['crontabmanager_task_log_storage_time_desc'] = 'Укажите срок хранения логов, все логи старше указанного количества минут будет автоматически удалены. Укажите "0" чтобы не удалять логи';
$_lang['crontabmanager_task_blockup_minutes_add'] = 'the task is blocked for "[[+minutes]]" minutes';
$_lang['crontabmanager_task_err_add_crontab'] = 'Не удалось добавить задание контроллер [[+task_path]] в crontab';
$_lang['crontabmanager_task_err_remove_crontab'] = 'Не удалось удалить задание контроллер [[+task_path]] из crontab';
$_lang['CronTabManagerTask_err_remove'] = 'Не удалось удалить задание так как крон задание не удалилось';
$_lang['crontabmanager_show_crontabs'] = 'Смотреть crontabs';
$_lang['crontabmanager_show_pids'] = 'Смотреть pids';
$_lang['crontabmanager_task_active_desc'] = 'Если снять галочку, то задание будет отключено в планировщике cron и не будет запускаться в фоновом режиме';
$_lang['crontabmanager_task_path_task_desc'] = 'Контроллеры для запуска находятся в "core/scheduler/Controllers/"';


// Task Log
$_lang['crontabmanager_task_log_id'] = 'id';
$_lang['crontabmanager_task_log_last_run'] = 'Запуск';
$_lang['crontabmanager_task_log_end_run'] = 'Остановка';
$_lang['crontabmanager_task_log_completed'] = 'Завершено';
$_lang['crontabmanager_task_log_ignore_action'] = 'Игнорировать';
$_lang['crontabmanager_task_log_notification'] = 'Уведомление';
$_lang['crontabmanager_task_log_memory_usage'] = 'Memory Usage';
$_lang['crontabmanager_task_log_exec_time'] = 'Exec Time';
$_lang['crontabmanager_task_log_auto_pause'] = 'Авто пауза';
$_lang['crontabmanager_task_log_pause'] = 'График пауз';
$_lang['crontabmanager_task_log_createdon'] = 'Создан';
$_lang['crontabmanager_task_log_updatedon'] = 'Обновлен';
$_lang['crontabmanager_task_un_look'] = 'Task unlook';
$_lang['crontabmanager_task_err_ae_controller'] = 'Не удалось найти файл контроллера по указанному пути: [[+controller]]';
$_lang['crontabmanager_task_year_err_ae_controller'] = 'Не удалось найти файл контроллера по указанному пути: [[+controller]]. Вы используете собственный путь к контроллеру по этому нужно добавить абсолютный путь до файла';
$_lang['crontabmanager_task_removeLog'] = 'Удалить лог файл crontab';
$_lang['crontabmanager_task_removelog_confirm'] = 'Вы уверены что хотите удалить лог файл crontab?';
$_lang['crontabmanager_task_copyTask'] = 'Скопировать путь CLI';
$_lang['crontabmanager_task_copyTask_success'] = 'Путь успешно скопирован';
$_lang['crontabmanager_time_server'] = 'Время на сервере';


// Action
$_lang['crontabmanager_task_create'] = 'Создать задание';
$_lang['crontabmanager_task_update'] = 'Изменить Задание';
$_lang['crontabmanager_task_enable'] = 'Включить Задание';
$_lang['crontabmanager_tasks_enable'] = 'Включить Задание';
$_lang['crontabmanager_tasks_disable'] = 'Отключить Задание';
$_lang['crontabmanager_tasks_remove'] = 'Удалить Задание';
$_lang['crontabmanager_task_unlock'] = 'Снять блокировку';
$_lang['crontabmanager_task_start'] = 'Запустить задание';
$_lang['crontabmanager_task_unblockup'] = 'Сбросить время блокировки';
$_lang['crontabmanager_task_readlog'] = 'Лог последнего запуска crontab';
$_lang['crontabmanager_task_manualstop'] = 'Ручное прерывать задание';
$_lang['crontabmanager_task_manualstop_confirm'] = 'Вы уверены что хотите в ручную остановить выполнение задания?';

$_lang['crontabmanager_task_unblockup_confirm'] = 'Вы уверены, что хотите сбросить время на которое было заблокировано это Задание?';
$_lang['crontabmanager_task_starttask_confirm'] = 'Вы уверены, что хотите запустить это Задание?';
$_lang['crontabmanager_task_unlock_confirm'] = 'Вы уверены, что хотите снять блокировку с этого Задание?';
$_lang['crontabmanager_task_remove_confirm'] = 'Вы уверены, что хотите удалить это Задание?';

$_lang['crontabmanager_task_err_path_task'] = 'Вы должны указать путь к контроллеру.';
$_lang['crontabmanager_task_err_ae'] = 'Задание с таким именем уже существует.';
$_lang['crontabmanager_task_err_nf'] = 'Задание не найден.';
$_lang['crontabmanager_task_err_ns'] = 'Задание не указан.';
$_lang['crontabmanager_task_err_remove'] = 'Ошибка при удалении Задания.';
$_lang['crontabmanager_task_err_save'] = 'Ошибка при сохранении Задания.';
$_lang['crontabmanager_task_err_ns_minutes'] = 'Укажите количество минут для блокировки задания';
$_lang['crontabmanager_task_err_ns_max_minuts_blockup'] = 'Максимальное количество минут для блокировки задания, не должно превышать: [[+max_minuts_blockup]] мин.';
$_lang['crontabmanager_task_err_ns_allow_blocking_tasks'] = 'Блокировка заданий отключена!';


#  Category category
$_lang['crontabmanager_categories'] = 'Категории';
$_lang['crontabmanager_categories_intro_msg'] = 'Категории для заданий используются для фильтрации';

$_lang['crontabmanager_category_id'] = 'Id';
$_lang['crontabmanager_category_name'] = 'Наименование';
$_lang['crontabmanager_category_description'] = 'Описание';
$_lang['crontabmanager_category_active'] = 'Включена';
$_lang['crontabmanager_category_err_sub_id'] = 'Вы должны выбрать подписчика.';
$_lang['crontabmanager_category_err_nf'] = 'Предмет не найден.';
$_lang['crontabmanager_category_err_ns'] = 'Предмет не указан.';
$_lang['crontabmanager_category_err_remove'] = 'Ошибка при удалении Предмета.';
$_lang['crontabmanager_category_err_save'] = 'Ошибка при сохранении Предмета.';

$_lang['crontabmanager_category_disable'] = 'Отключить категорию';
$_lang['crontabmanager_category_create'] = 'Создать категорию';
$_lang['crontabmanager_category_update'] = 'Изменить Категорию';
$_lang['crontabmanager_category_enable'] = 'Включить Категорию';
$_lang['crontabmanager_categories_enable'] = 'Включить Категории';
$_lang['crontabmanager_categories_disable'] = 'Отключить Категории';
$_lang['crontabmanager_categories_remove'] = 'Удалить Категории';
$_lang['crontabmanager_category_remove'] = 'Удалить Категорию';

$_lang['crontabmanager_category_remove_confirm'] = 'Вы уверены, что хотите удалить эту Категорию?';
$_lang['crontabmanager_categories_remove_confirm'] = 'Вы уверены, что хотите удалить эти Категории?';


# Filter
$_lang['crontabmanager_task_parent'] = 'Категория';
$_lang['crontabmanager_task_parent_empty'] = 'Выберите категорию';
$_lang['crontabmanager_task_filter_active'] = 'Активные';
$_lang['crontabmanager_task_filter_mode_develop'] = 'В разработке';
$_lang['crontabmanager_task_log_remove'] = 'Удалить лог';
$_lang['crontabmanager_task_logs_remove'] = 'Удалить логи';
$_lang['crontabmanager_cron_connector_run_task_windows'] = 'Запустить задание';
$_lang['crontabmanager_cron_connector_run_task_windows_btn'] = 'Перезапустить';
$_lang['crontabmanager_cron_connector_unlock'] = 'Разблокировать задание';
$_lang['crontabmanager_cron_connector_unlock_btn'] = 'Разблокировать';
$_lang['crontabmanager_cron_connector_read_log'] = 'Читать лог файл';
$_lang['crontabmanager_cron_connector_read_log_btn'] = 'Читать лог файл';
$_lang['crontabmanager_cron_connector_args'] = 'Аргументы в виде user=1 resource=2';


#  Notification
$_lang['crontabmanager_notifications'] = 'Центр уведомлений';
$_lang['crontabmanager_notifications_intro_msg'] = 'Список уведомлений об ошибках завершения задач';

$_lang['crontabmanager_notification_id'] = 'Id';
$_lang['crontabmanager_notification_name'] = 'Наименование';
$_lang['crontabmanager_notification_event'] = 'Событие';
$_lang['crontabmanager_notification_rule'] = 'Правило';
$_lang['crontabmanager_notification_rule_id'] = 'Id правила';
$_lang['crontabmanager_notification_read'] = 'Прочитано';
$_lang['crontabmanager_notification_send_email'] = 'Отправлено на email';
$_lang['crontabmanager_notification_createdon'] = 'Дата создания';
$_lang['crontabmanager_notification_rule_class'] = 'Обработчик';
$_lang['crontabmanager_notification_processing'] = 'Обработка';
$_lang['crontabmanager_notification_send'] = 'Отправлено';
$_lang['crontabmanager_notification_delivery'] = 'Доставка';
$_lang['crontabmanager_notification_response'] = 'Ответ';
$_lang['crontabmanager_notification_action_send'] = 'Отправить';
$_lang['crontabmanager_notification_active'] = 'Включена';
$_lang['crontabmanager_notification_err_sub_id'] = 'Вы должны выбрать подписчика.';
$_lang['crontabmanager_notification_err_nf'] = 'Предмет не найден.';
$_lang['crontabmanager_notification_err_ns'] = 'Предмет не указан.';
$_lang['crontabmanager_notification_err_remove'] = 'Ошибка при удалении уведомления.';
$_lang['crontabmanager_notification_err_save'] = 'Ошибка при сохранения уведомления.';

$_lang['crontabmanager_notifications_remove'] = 'Удалить уведомления';
$_lang['crontabmanager_notification_remove'] = 'Удалить уведомление';

$_lang['crontabmanager_notification_send_confirm'] = 'Вы уверены, что хотите отправить это Уведомление?';
$_lang['crontabmanager_notification_remove_confirm'] = 'Вы уверены, что хотите удалить эту Уведомление?';
$_lang['crontabmanager_notifications_remove_confirm'] = 'Вы уверены, что хотите удалить эти Уведомления?';
$_lang['crontabmanager_notification_filter_read'] = 'Не прочитанные';


////////////////////////
//// Install
////////////////////////
$_lang['crontabmanager_button_install'] = 'Установить компонент';
$_lang['crontabmanager_button_download'] = 'Скачать компонент';
$_lang['crontabmanager_button_download_encryption'] = 'Скачать компонент c шифрацией';


$_lang['crontabmanager_when_every_day'] = 'Каждый день';
$_lang['crontabmanager_when_weekdays'] = 'Будни';
$_lang['crontabmanager_when_weekends'] = 'Выходной';
$_lang['crontabmanager_when_monday'] = 'Понедельник';
$_lang['crontabmanager_when_tuesday'] = 'Вторник';
$_lang['crontabmanager_when_wednesday'] = 'Среда';
$_lang['crontabmanager_when_thursday'] = 'Четверг';
$_lang['crontabmanager_when_friday'] = 'Пятница';
$_lang['crontabmanager_when_saturday'] = 'Суббота';
$_lang['crontabmanager_when_sunday'] = 'Воскресенье';
$_lang['crontabmanager_auto_pause_from'] = 'с';
$_lang['crontabmanager_auto_pause_to'] = 'по';


// Auto Pause
$_lang['crontabmanager_task_rule'] = 'Правила';
$_lang['crontabmanager_task_rule_id'] = 'id';
$_lang['crontabmanager_task_rule_task_id'] = 'ID task';
$_lang['crontabmanager_task_rule_createdon'] = 'Создан';
$_lang['crontabmanager_task_rule_updatedon'] = 'Обновлен';
$_lang['crontabmanager_task_rule_message'] = 'Короткая запись для подсказки в сообщении';
$_lang['crontabmanager_task_rule_message_desc'] = 'Максимум 500 символов. Подсказка для пользователя в сообщении';
$_lang['crontabmanager_task_rule_active'] = 'Включена';
$_lang['crontabmanager_task_rule_name'] = 'Имя правила';
$_lang['crontabmanager_task_rule_all'] = 'Все задания';
$_lang['crontabmanager_task_rule_all_desc'] = 'Установите галочку, чтобы применить правило ко всем заданиям';

####
$_lang['crontabmanager_task_rule_create'] = 'Добавить правило';
$_lang['crontabmanager_task_rule_update'] = 'Обновить';
$_lang['crontabmanager_task_rule_remove'] = 'Удалить';
$_lang['crontabmanager_task_rule_remove_confirm'] = 'Вы уверены что хотите удалить эту автопаузу?';
$_lang['crontabmanager_task_rules_remove_confirm'] = 'Вы уверены что хотите удалить эту автопаузу?';
$_lang['crontabmanager_task_rules_remove'] = 'Удалить';


$_lang['crontabmanager_task_rule_err_when'] = 'Укажите когда запускать';
$_lang['crontabmanager_task_rule_err_from'] = 'Укажите часы и минуты';
$_lang['crontabmanager_task_rule_err_to'] = 'Укажите часы и минуты';


/**
 * Build
 */
$_lang['crontabmanager_notification_fail'] = 'Сбой задания';
$_lang['crontabmanager_notification_notify_first_fail'] = 'Уведомлять только при первом сбое задания после успешного выполнения';
$_lang['crontabmanager_notification_notify_new_problem'] = 'Уведомлять только при новой проблеме задания или новом неудачном тесте';
$_lang['crontabmanager_notification_success'] = 'Успешное выполнение задания';
$_lang['crontabmanager_notification_notify_first_success'] = 'Уведомлять только при первом успешном выполнении задания после неудачи';
$_lang['crontabmanager_notification_first_build_error'] = 'Первое появление ошибки задания';
$_lang['crontabmanager_notification_build_start'] = 'Началось выполнение задания';
$_lang['crontabmanager_task_rule_class'] = 'Тип уведомления';


$_lang['crontabmanager_task_rule_categories_desc'] = 'Выберите задания для которых будет работать правило';
$_lang['crontabmanager_task_rule_create'] = 'Создать правило';
$_lang['crontabmanager_task_rule_update'] = 'Обновить правило';
$_lang['crontabmanager_task_rule_class'] = 'Тип уведомления';
$_lang['crontabmanager_task_rule_method_http'] = 'Метод отправки данных';
$_lang['crontabmanager_task_rule_params'] = 'Параметры в JSON формате';
$_lang['crontabmanager_task_rule_token'] = 'token - сгенерированный ботом';
$_lang['crontabmanager_task_rule_chat_id'] = 'Chat id - который выдал бот';
$_lang['crontabmanager_task_rule_email'] = 'E-mail';
$_lang['crontabmanager_task_rule_url'] = 'url - куда стучаться';
$_lang['crontabmanager_task_rule_notice'] = 'Уведомления';


$_lang['crontabmanager_task_rule_err_categories'] = 'Выберите задания для которых будет работать правило';
$_lang['crontabmanager_task_rule_err_class'] = 'Укажите тип уведомления';
$_lang['crontabmanager_task_rule_err_api_key'] = 'Укажите API ключ';
$_lang['crontabmanager_task_rule_err_chat_id'] = 'Укажите chat id';
$_lang['crontabmanager_task_rule_err_token'] = 'Укажите token';
$_lang['crontabmanager_task_rule_err_email'] = 'Укажите email';
$_lang['crontabmanager_task_rule_err_url'] = 'Укажите url';
$_lang['crontabmanager_task_rule_err_method_http'] = 'Укажите метод GET или POST';
$_lang['crontabmanager_task_rule_err_params'] = 'Параметры указаны с ошибкой, должно быть в JSON формате';


$_lang['crontabmanager_task_rule_fails'] = 'Задание завершено неудачно';
$_lang['crontabmanager_task_rule_fails_after_successful'] = '- Уведомлять только о первой неудачном завершении после успешной';
$_lang['crontabmanager_task_rule_fails_new_problem'] = '- Уведомлять только о новой проблеме в задачи';

$_lang['crontabmanager_task_rule_successful'] = 'Задание завершено успешно';
$_lang['crontabmanager_task_rule_successful_after_failed'] = '- Уведомлять только о первой успешном завершении после неудачной';
$_lang['crontabmanager_task_rule_criteria_desc'] = 'Отметьте критерии, которые должны быть выполнены, чтобы уведомление было отправлено.';
$_lang['crontabmanager_task_rule_tasks'] = 'Задания';

$_lang['crontabmanager_task_restart_after_failure'] = 'Перезапускать задание после неудачного завершения';
$_lang['crontabmanager_task_restart_after_failure_desc'] = 'Если установлено, то задание будет перезапущено после неудачного завершения. При этом, если задание не завершается успешно, то оно уведомления не будет отправлено. Только при втором неудачном завершении будет отправлено уведомление.';
$_lang['crontabmanager_crontab_available'] = 'linux <b>crontab</b> доступе для вашего пользователя <b>[[+user]]</b>.';
$_lang['crontabmanager_crontab_not_available'] = 'linux <b>crontab</b> недоступен для вашего пользователя <b>[[+user]]</b>.';

$_lang['crontabmanager_next_run_human'] = 'Следующий запуск через: [[+minutes]] минут';
$_lang['crontabmanager_next_run_human_hours'] = ' и [[+hours]] час.';

$_lang['crontabmanager_next_run_human_seconds'] = 'Следующий запуск через [[+seconds]] сек.';
$_lang['crontabmanager_next_run_human_minutes'] = 'Следующий запуск через [[+minutes]] мин.';
$_lang['crontabmanager_next_run_human_hours'] = ' Следующий запуск через [[+hours]] час.';
$_lang['crontabmanager_next_run_human_days'] = 'Следующий запуск через [[+days]] дн.';

$_lang['crontabmanager_button_help'] = 'Документация CrontabManager';

$_lang['crontabmanager_task_cron_add'] = 'Добавить в cron';
$_lang['crontabmanager_task_cron_remove'] = 'Удалить из cron';
$_lang['crontabmanager_task_add_cron_confirm'] = 'Вы уверены что хотите добавить в crontabs это задание?';
$_lang['crontabmanager_task_remove_cron_confirm'] = 'Вы уверены что хотите удалить из crontabs это задание?';

# mute
$_lang['crontabmanager_task_mute'] = 'Заглушить уведомления';
$_lang['crontabmanager_task_mute_time'] = 'Заглушить уведомления на время';
$_lang['crontabmanager_task_unmute'] = 'Снять молчание для уведомления';

$_lang['crontabmanager_task_mute_confirm'] = 'Вы уверены что хотите залушить уведомления для этого задания до первого успешнего завершения?';
$_lang['crontabmanager_task_unmute_confirm'] = 'Вы уверены что хотите снять молчание для уведомления для этого задания?';

$_lang['crontabmanager_task_window_mute_time'] = 'Заглушить уведомления до даты';
$_lang['crontabmanager_task_mute_time_date'] = 'Дата и время';
$_lang['crontabmanager_task_mute_time_date_desc'] = 'Выберите дату и время после которой уведомления будут отправляться';
$_lang['crontabmanager_task_err_get_task'] = 'Не удалось получить задание';
$_lang['crontabmanager_task_err_time_current'] = 'Время мута меньше текущего времени';


$_lang['crontabmanager_task_failed'] = 'Задание завершено неудачно';
$_lang['crontabmanager_task_executed'] = 'Выполняется';


$_lang['crontabmanager_window_regex_minute'] = 'Введите правильное cron-выражение для минут';

$_lang['crontabmanager_task_snippet_label'] = 'Сниппет для запуска (опционально)';
$_lang['crontabmanager_task_snippet_desc'] = 'Можно выбрать сниппет для автоматического запуска';
$_lang['crontabmanager_task_snippet_placeholder'] = 'сниппет для запуска (option)';


$_lang['crontabmanager_snippet_all'] = '---';
$_lang['crontabmanager_task_command'] = 'Команда для запуска';
