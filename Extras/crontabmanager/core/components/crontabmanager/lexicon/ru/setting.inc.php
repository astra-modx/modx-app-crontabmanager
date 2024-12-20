<?php

$_lang['area_crontabmanager_main'] = 'Основные';
$_lang['area_crontabmanager_path'] = 'Директории с scheduler';
$_lang['area_crontabmanager_blocking'] = 'Блокировки';
$_lang['area_crontabmanager_rest'] = 'Rest для проложения';


// Path
$_lang['setting_crontabmanager_scheduler_path'] = 'Основная директория с классами';
$_lang['setting_crontabmanager_scheduler_path_desc'] = 'Здесь храняться все классы';

$_lang['setting_crontabmanager_link_path'] = 'Директория ссылок на контроллеры';
$_lang['setting_crontabmanager_link_path_desc'] = 'Директория хранения ссылок на контроллеры';

$_lang['setting_crontabmanager_lock_path'] = 'Директория с блокировочными файлами';
$_lang['setting_crontabmanager_lock_path_desc'] = 'Директория хранения файлов блокеровок, для избежания повторного запуска';

$_lang['setting_crontabmanager_log_path'] = 'Директория с логами';
$_lang['setting_crontabmanager_log_path_desc'] = 'Во время запуска через крон, логи по заданиям буду сохраняться в эту директорию';


// Main
$_lang['setting_crontabmanager_php_command'] = 'php команда для запуска крон задания';
$_lang['setting_crontabmanager_php_command_desc'] = 'По умолчанию пусто. В зависимости от версии и хранения файла вы можете указать свой путь к файл. Оставить пусты чтобы путь определился автоматически';

$_lang['setting_crontabmanager_set_completion_time'] = 'Запись времени запуска и остановки задания';
$_lang['setting_crontabmanager_set_completion_time_desc'] = 'По умолчанию Да. Если указать нет то время и логи не будут фиксироваться';
$_lang['setting_crontabmanager_allow_blocking_tasks'] = 'Разрешить блокировать по времени';
$_lang['setting_crontabmanager_allow_blocking_tasks_desc'] = 'По умолчанию Да. Если установить Нет, то невозможно будет заблокировать задания на какой то промежуток времени';
$_lang['setting_crontabmanager_max_minuts_blockup'] = 'Максимальное кол-во минут для блокировки';
$_lang['setting_crontabmanager_max_minuts_blockup_desc'] = 'По умолчанию 1440 минут (сутки). Больше чем на указанное количество минут задание нельзя будет заблокировать';

$_lang['setting_crontabmanager_user_id'] = 'Запускать задания по пользователем';
$_lang['setting_crontabmanager_user_id_desc'] = 'По умолчанию 1. Во время запуска задания, все операции будут производится под этим пользователем';

$_lang['setting_crontabmanager_log_storage_time'] = 'Срок хранения логов по умолчанию';
$_lang['setting_crontabmanager_log_storage_time_desc'] = 'По умолчанию все задания будут хранить логи 10080 минут. Для каждого задания можно настроить срок хранения логов персонально.';

$_lang['setting_crontabmanager_email_administrator'] = 'E-mail администратора для уведомлений';
$_lang['setting_crontabmanager_email_administrator_desc'] = 'При привышении установленного количества попыток запуска задания, автоматически будет создано письмо и отправлено на перечисленные e-mail адреса';

$_lang['setting_crontabmanager_blocking_time_minutes'] = 'Время ожидания до автоматической разблокировки';
$_lang['setting_crontabmanager_blocking_time_minutes_desc'] = 'По умолчанию 1 минута. Если во время запуска задание небыло завершено, то при следующем запуске из блокирующего файла будет получено время старта. Если оно привысит указанное количество минут, то блокировочный файл будет удален автоматически';


$_lang['setting_crontabmanager_rest_enable'] = 'Включить rest для приложения';
$_lang['setting_crontabmanager_rest_enable_desc'] = 'Укажите Да если хотите чтобы преложение CronTabManager могло обращаться к сайт';

$_lang['setting_crontabmanager_rest_client_id'] = 'Client ID';
$_lang['setting_crontabmanager_rest_client_id_desc'] = 'Уникальный индитификатор приложения для авторизации на сайте из проложения';

$_lang['setting_crontabmanager_rest_controller'] = 'Путь к контроллеру rest';
$_lang['setting_crontabmanager_rest_controller_desc'] = 'Можно указать свой уникальный путь. По умолчанию assets/components/crontabmanager/rest.php';

$_lang['setting_crontabmanager_save_to_file'] = 'Разрешить записывать кроны в файл';
$_lang['setting_crontabmanager_save_to_file_desc'] = 'Установите Да, чтобы все сохраненные крон задания писались в файл core/scheduler/crontabs/USER';
