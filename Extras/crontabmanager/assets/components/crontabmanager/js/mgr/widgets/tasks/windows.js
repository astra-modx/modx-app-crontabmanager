CronTabManager.window.CreateTask = function (config) {
    config = config || {}
    config.url = CronTabManager.config.connector_url

    Ext.applyIf(config, {
        title: _('crontabmanager_task_create'),
        width: 1000,
        cls: 'crontabmanager_windows',
        baseParams: {
            action: 'mgr/task/create',
        }
    })
    CronTabManager.window.CreateTask.superclass.constructor.call(this, config)
}
Ext.extend(CronTabManager.window.CreateTask, CronTabManager.window.Default, {

    getFields: function (config) {
        return [{
            xtype: 'modx-tabs',
            deferredRender: true,
            items: [
                {
                    title: _('crontabmanager_task'),
                    layout: 'form',
                    items: CronTabManager.window.CreateTask.prototype.getFieldsTask.call(this, config),
                }, {
                    title: _('crontabmanager_task_log'),
                    items: [{
                        xtype: 'crontabmanager-grid-tasks-logs',
                        record: config.record,
                    }]
                }, {
                    title: _('crontabmanager_task_rule'),
                    items: [{
                        xtype: 'crontabmanager-grid-tasks-rules',
                        record: config.record,
                    }]
                }, {
                    title: _('crontabmanager_task_notice'),
                    layout: 'form',
                    items: CronTabManager.window.CreateTask.prototype.getFieldsNotice.call(this, config),
                }, {
                    title: _('crontabmanager_task_setting'),
                    layout: 'form',
                    items: CronTabManager.window.CreateTask.prototype.getFieldsSetting.call(this, config),
                }
            ]
        }]
    },

    cronTime: function (config, field) {

        return [
            {
                columnWidth: .4
                , layout: 'form'
                , items: [
                    {
                        xtype: 'textfield',
                        id: config.id + '-target-' + field,
                        name: field,
                        value: '*',
                        fieldLabel: _('crontabmanager_task_' + field),
                        anchor: '100%',
                        regex: /^(\*|[0-5]?\d)(-([0-5]?\d))?((\/|\,)[0-5]?\d)*$/,
                        regexText: _('crontabmanager_window_regex_' + field),
                    },
                    {
                        xtype: 'crontabmanager-combo-condition',
                        cls: 'desc-under',
                        field: field,
                        name: field + '_condition',
                        id: config.id + '-source-' + field,
                        emptyText: '*',
                        fieldLabel: '',
                        anchor: '100%',
                    }
                ]
            },
        ];
    },
    getFieldsTask: function (config) {
        return [
            {xtype: 'hidden', name: 'id', id: config.id + '-id'},

            {
                layout: 'column',
                items: [{
                    columnWidth: .6,
                    layout: 'form',
                    defaults: {msgTarget: 'under'},
                    items: [
                        {
                            xtype: 'textfield',
                            fieldLabel: _('crontabmanager_task_path_task'),
                            name: 'path_task',
                            id: config.id + '-path_task',
                            anchor: '99%',
                            allowBlank: false,
                            emptyText: _('crontabmanager_task_path_task_placeholder'),
                        }
                    ]
                }, {
                    columnWidth: .4,
                    layout: 'form',
                    defaults: {msgTarget: 'under'},
                    items: [
                        {
                            xtype: 'crontabmanager-combo-parent',
                            fieldLabel: _('crontabmanager_task_parent_empty'),
                            name: 'parent',
                            id: config.id + '-parent',
                            anchor: '99%',
                            emptyText: _('crontabmanager_task_parent_empty'),
                            allowBlank: true,
                        },
                    ],
                }]

            },

            {
                html: _('crontabmanager_task_path_task_desc'),
                cls: 'desc-under',
            },


            {
                layout: 'column',
                items: [{
                    columnWidth: .5,
                    layout: 'form',
                    defaults: {msgTarget: 'under'},
                    items: [
                        // Время запуска

                        {
                            xtype: 'displayfield',
                            fieldLabel: 'Время запуска',
                            html: 'Выберите время автоматического запуска'
                        },

                        // minutes
                        {
                            xtype: 'fieldset'
                            , layout: 'column'
                            , cls: 'crontabmanager_time_crontab'
                            , defaults: {msgTarget: 'under', border: false}
                            , items: this.cronTime(config, 'minutes')
                        },
                        {
                            xtype: 'fieldset'
                            , layout: 'column'
                            , cls: 'crontabmanager_time_crontab'
                            , defaults: {msgTarget: 'under', border: false}
                            , items: this.cronTime(config, 'hours')
                        },

                        {
                            xtype: 'fieldset'
                            , layout: 'column'
                            , cls: 'crontabmanager_time_crontab'
                            , defaults: {msgTarget: 'under', border: false}
                            , items: this.cronTime(config, 'days')
                        },

                        {
                            xtype: 'fieldset'
                            , layout: 'column'
                            , cls: 'crontabmanager_time_crontab'
                            , defaults: {msgTarget: 'under', border: false}
                            , items: this.cronTime(config, 'months')
                        },

                        {
                            xtype: 'fieldset'
                            , layout: 'column'
                            , cls: 'crontabmanager_time_crontab'
                            , defaults: {msgTarget: 'under', border: false}
                            , items: this.cronTime(config, 'weeks')
                        },
                    ]
                }, {
                    columnWidth: .5,
                    layout: 'form',
                    defaults: {msgTarget: 'under'},
                    items: [

                        {
                            xtype: 'modx-snippet',
                            fieldLabel: _('crontabmanager_task_description'),
                            name: 'description',
                            id: config.id + '-description',
                            emptyText: _('crontabmanager_task_description_placeholder'),
                            height: 100,
                            anchor: '99%'
                        },

                        {
                            columnWidth: .5,
                            layout: 'form',
                            defaults: {msgTarget: 'under'},
                            items: [
                                {
                                    xtype: 'xcheckbox',
                                    boxLabel: _('crontabmanager_task_mode_develop'),
                                    name: 'mode_develop',
                                    id: config.id + '-mode_develop',
                                    checked: true,
                                },
                                {
                                    html: _('crontabmanager_task_mode_develop_desc'),
                                    cls: 'desc-under',
                                },
                                {
                                    xtype: 'xcheckbox',
                                    boxLabel: _('crontabmanager_task_active'),
                                    name: 'active',
                                    id: config.id + '-active',
                                    checked: true,
                                },
                                {
                                    html: _('crontabmanager_task_active_desc'),
                                    cls: 'desc-under',
                                },
                            ]
                        },

                        {
                            xtype: 'textarea',
                            fieldLabel: _('crontabmanager_task_description'),
                            name: 'description',
                            id: config.id + '-description',
                            emptyText: _('crontabmanager_task_description_placeholder'),
                            height: 100,
                            anchor: '99%'
                        },
                    ],
                }]

            },


            // hours
            /* {
                 xtype: 'fieldset'
                 , layout: 'column'
                 , style: 'padding:15px 5px;'
                 , defaults: {msgTarget: 'under', border: false}
                 , items: [
                     {
                         columnWidth: .2
                         , layout: 'form'
                         , items: [
                             {
                                 xtype: 'textfield',
                                 id: config.id + '-target-hours',
                                 name: 'hours',
                                 value: '*',
                                 emptyText: '*',
                                 fieldLabel: _('crontabmanager_task_hours'),
                                 anchor: '100%',
                                 regex: /^(\*|[0-5]?\d)(-([0-5]?\d))?((\/|\,)[0-5]?\d)*$/,
                                 regexText: 'Введите правильное cron-выражение для часа',
                             }
                         ]
                     },
                     {
                         columnWidth: .4
                         , layout: 'form'
                         , style: 'margin: 13px 0 0 5px;'
                         , items: [
                             {
                                 xtype: 'crontabmanager-combo-condition',
                                 id: config.id + '-source-hours',
                                 value: 'Своё',
                                 emptyText: 'Своё',
                                 fieldLabel: '',
                                 anchor: '100%',
                             }
                         ]
                     }
                 ]
             },
 */

            /*{
                xtype: 'fieldset'
                , layout: 'column'
                , style: 'padding:15px 5px;text-align:center;'
                , defaults: {msgTarget: 'under', border: false}
                , items: [
                {
                    columnWidth: .20
                    , layout: 'form'
                    , items: [
                        {
                            xtype: 'textfield',
                            name: 'minutes',
                            value: '*',
                            emptyText: '*',
                            fieldLabel: _('crontabmanager_task_minutes'),
                            anchor: '100%',
                            regex: /^(\*|[0-5]?\d)(-([0-5]?\d))?((\/|\,)[0-5]?\d)*$/, // Регулярное выражение для валидации
                            regexText: 'Введите правильное cron-выражение для минут', // Текст ошибки валидации
                        }
                    ]
                }, {
                    columnWidth: .20
                    , layout: 'form'
                    , items: [
                        {
                            xtype: 'textfield',
                            name: 'hours',
                            value: '*',
                            emptyText: '*',
                            fieldLabel: _('crontabmanager_task_hours'),
                            anchor: '100%',
                            regex: /^(\*|[01]?\d|2[0-3])(-([01]?\d|2[0-3]))?((\/|\,)[01]?\d|2[0-3])*$/, // Регулярное выражение для валидации
                            regexText: 'Введите правильное cron-выражение для часов', // Текст ошибки валидации
                        }
                    ]
                }, {
                    columnWidth: .20
                    , layout: 'form'
                    , items: [
                        {
                            xtype: 'textfield',
                            name: 'days',
                            value: '*',
                            emptyText: '*',
                            fieldLabel: _('crontabmanager_task_days'),
                            anchor: '100%',
                            regex: /^(\*|[1-9]|[12]\d|3[01])(-([1-9]|[12]\d|3[01]))?((\/|\,)(\*|[1-9]|[12]\d|3[01]))*$/, // Регулярное выражение для валидации
                            regexText: 'Введите правильное cron-выражение для дней месяца', // Текст ошибки валидации
                        }
                    ]
                }, {
                    columnWidth: .20
                    , layout: 'form'
                    , items: [
                        {
                            xtype: 'textfield',
                            name: 'months',
                            value: '*',
                            emptyText: '*',
                            fieldLabel: _('crontabmanager_task_months'),
                            anchor: '100%',
                            regex: /^(\*|[1-9]|1[0-2])(-([1-9]|1[0-2]))?((\/|\,)(\*|[1-9]|1[0-2]))*$/, // Регулярное выражение для валидации
                            regexText: 'Введите правильное cron-выражение для месяцев', // Текст ошибки валидации
                        }
                    ]
                },
                    {
                        columnWidth: .20
                        , layout: 'form'
                        , items: [
                            {
                                xtype: 'textfield',
                                name: 'weeks',
                                value: '*',
                                emptyText: '*',
                                fieldLabel: _('crontabmanager_task_weeks'),
                                anchor: '100%',
                                regex: /^(\*|[0-7])(-([0-7]))?((\/|\,)(\*|[0-7]))*$/, // Регулярное выражение для валидации
                                regexText: 'Введите правильное cron-выражение для дней недели', // Текст ошибки валидации
                            }
                        ]
                    }]
            },*/

            /* {
                 layout: 'column',
                 items: [

                     {
                         columnWidth: .5,
                         layout: 'form',
                         defaults: {msgTarget: 'under'},
                         items: [
                             {
                                 xtype: 'textarea',
                                 fieldLabel: _('crontabmanager_task_description'),
                                 name: 'description',
                                 id: config.id + '-description',
                                 height: 100,
                                 anchor: '99%'
                             },
                         ],
                     }]

             },*/

        ]
    },

    getFieldsSetting: function (config) {
        return [
            {
                xtype: 'numberfield',
                fieldLabel: _('crontabmanager_task_log_storage_time'),
                description: _('crontabmanager_task_log_storage_time_desc'),
                name: 'log_storage_time',
                id: config.id + '-log_storage_time',
                anchor: '99%',
            },
            {
                xtype: 'xcheckbox',
                boxLabel: _('crontabmanager_task_restart_after_failure'),
                name: 'restart_after_failure',
                id: config.id + '-restart_after_failure',
                checked: true,
            },
            {
                html: _('crontabmanager_task_restart_after_failure_desc'),
                cls: 'desc-under',
            },
        ]
    },

    getFieldsNotice: function (config) {
        return [

            {
                layout: 'column',
                items: [{
                    columnWidth: .5,
                    layout: 'form',
                    defaults: {msgTarget: 'under'},
                    items: []
                }, {
                    columnWidth: .5,
                    layout: 'form',
                    defaults: {msgTarget: 'under'},
                    items: [
                        {
                            xtype: 'xcheckbox',
                            boxLabel: _('crontabmanager_task_notification_enable'),
                            name: 'notification_enable',
                            id: config.id + '-notification_enable',
                            checked: true,
                        },
                        {
                            html: _('crontabmanager_task_notification_enable_desc'),
                            cls: 'desc-under',
                        },

                        {
                            xtype: 'numberfield',
                            fieldLabel: _('crontabmanager_task_max_number_attempts'),
                            name: 'max_number_attempts',
                            id: config.id + '-max_number_attempts',
                            anchor: '99%',
                        },
                        {
                            html: _('crontabmanager_task_max_number_attempts_desc'),
                            cls: 'desc-under',
                        }/*,
                        {
                            xtype: 'textfield',
                            fieldLabel: _('crontabmanager_task_notification_emails'),
                            description: _('crontabmanager_task_notification_emails_desc'),
                            name: 'notification_emails',
                            id: config.id + '-notification_emails',
                            anchor: '99%'
                        },*/
                    ],
                }]

            },

        ]
    },

})
Ext.reg('crontabmanager-task-window-create', CronTabManager.window.CreateTask)

CronTabManager.window.UpdateTask = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        title: _('crontabmanager_task_update'),
        baseParams: {
            action: 'mgr/task/update',
            resource_id: config.resource_id
        },
    })
    CronTabManager.window.UpdateTask.superclass.constructor.call(this, config)

}
Ext.extend(CronTabManager.window.UpdateTask, CronTabManager.window.CreateTask)
Ext.reg('crontabmanager-task-window-update', CronTabManager.window.UpdateTask)
