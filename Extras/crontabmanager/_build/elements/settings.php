<?php

return [

    // path
    'scheduler_path' => [
        'xtype' => 'textfield',
        'value' => '{core_path}scheduler',
        'area' => 'crontabmanager_path',
    ],


    'log_path' => [
        'xtype' => 'textfield',
        'value' => '{core_path}scheduler/logs',
        'area' => 'crontabmanager_path',
    ],

    // Main

    'php_command' => [
        'xtype' => 'textfield',
        'value' => '',
        'area' => 'crontabmanager_main',
    ],

    'set_completion_time' => [
        'xtype' => 'combo-boolean',
        'value' => 1,
        'area' => 'crontabmanager_main',
    ],

    'user_id' => [
        'xtype' => 'numberfield',
        'value' => 1,
        'area' => 'crontabmanager_main',
    ],

    'log_storage_time' => [
        'xtype' => 'numberfield',
        'value' => 10080,
        'area' => 'crontabmanager_main',
    ],


    'email_administrator' => [
        'xtype' => 'textfield',
        'value' => 'info@bustep.ru',
        'area' => 'crontabmanager_main',
    ],


    // blocking

    'blocking_time_minutes' => [
        'xtype' => 'numberfield',
        'value' => 1,
        'area' => 'crontabmanager_blocking',
    ],

    'allow_blocking_tasks' => [
        'xtype' => 'combo-boolean',
        'value' => 1,
        'area' => 'crontabmanager_blocking',
    ],

    'max_minuts_blockup' => [
        'xtype' => 'numberfield',
        'value' => 1440,
        'area' => 'crontabmanager_blocking',
    ],


    // blocking
    'rest_enable' => [
        'xtype' => 'combo-boolean',
        'value' => 1,
        'area' => 'crontabmanager_rest',
    ],

    'rest_client_id' => [
        'xtype' => 'textfield',
        'value' => '',
        'area' => 'crontabmanager_rest',
    ],

    'rest_controller' => [
        'xtype' => 'textfield',
        'value' => 'assets/components/crontabmanager/rest.php',
        'area' => 'crontabmanager_rest',
    ],
    'version' => [
        'xtype' => 'textfield',
        'value' => getenv('PACKAGE_VERSION_MAJOR').'.'.getenv('PACKAGE_VERSION_MINOR').'.'.getenv('PACKAGE_VERSION_PATCH'),
        'area' => 'crontabmanager_rest',
    ],

    'save_to_file' => [
        'xtype' => 'combo-boolean',
        'value' => 0,
        'area' => 'crontabmanager_main',
    ],

];
