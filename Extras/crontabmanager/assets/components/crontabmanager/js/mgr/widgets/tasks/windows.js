CronTabManager.window.CreateTask = function (config) {
    config = config || {}
    config.url = CronTabManager.config.connector_url
    config.current_path_task = null

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
                        },

                        {
                            xtype: 'xcheckbox',
                            cls: 'crontabmanager_creat_new_controller',
                            hidden: config.record != null,
                            boxLabel: _('crontabmanager_task_create_new_controller'),
                            name: 'create_new_controller',
                            id: config.id + '-create_new_controller',
                            checked: true,
                        },
                    ]
                }, {
                    columnWidth: .4,
                    layout: 'form',
                    defaults: {msgTarget: 'under'},
                    items: [

                        {
                            xtype: 'crontabmanager-combo-snippets',
                            fieldLabel: _('crontabmanager_task_snippet_label'),
                            name: 'snippet',
                            id: config.id + '-snippet',
                            emptyText: _('crontabmanager_task_snippet_placeholder'),
                            height: 100,
                            anchor: '99%',
                            listeners: {
                                'select': {
                                    fn: function (f, value) {

                                        var current = Ext.getCmp(config.id + '-path_task').getValue()
                                        if (!config.current_path_task) {
                                            config.current_path_task = current
                                        }
                                        if (value.data.id == '') {
                                            Ext.getCmp(config.id + '-path_task')
                                                .removeClass('crontabmanager_readonly')
                                                .setReadOnly(false);

                                            Ext.getCmp(config.id + '-path_task').setValue(config.current_path_task)
                                        } else {
                                            Ext.getCmp(config.id + '-path_task')
                                                .addClass('crontabmanager_readonly')
                                                .setReadOnly(true);

                                            Ext.getCmp(config.id + '-path_task').setValue(CronTabManager.config.snippet_run)
                                        }


                                    }, scope: this
                                }
                            }
                        },
                        {
                            html: _('crontabmanager_task_snippet_desc'),
                            cls: 'desc-under',
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
                            xtype: 'crontabmanager-combo-parent',
                            fieldLabel: _('crontabmanager_task_parent_empty'),
                            name: 'parent',
                            id: config.id + '-parent',
                            anchor: '99%',
                            emptyText: _('crontabmanager_task_parent_empty'),
                            allowBlank: true,
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
                        }
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
            action: 'mgr/task/update'
        },
    })
    CronTabManager.window.UpdateTask.superclass.constructor.call(this, config)

}
Ext.extend(CronTabManager.window.UpdateTask, CronTabManager.window.CreateTask)
Ext.reg('crontabmanager-task-window-update', CronTabManager.window.UpdateTask)


/**
 * muteTimeItem
 */

CronTabManager.window.MuteTime = function (config) {
    config = config || {}
    config.url = CronTabManager.config.connector_url

    Ext.applyIf(config, {
        title: _('crontabmanager_task_window_mute_time'),
        width: 600,
        cls: 'crontabmanager_windows',
        baseParams: {
            action: 'mgr/task/mutetime',
        },
        fields: [
            {xtype: 'hidden', name: 'id', id: config.id + '-id'},
            {
                xtype: 'xdatetime',
                fieldLabel: _('crontabmanager_task_mute_time_date'),
                name: 'mute_time',
                id: config.id + '-mute_time',
                anchor: '99%',
                allowBlank: false,
                dateWidth: 153,
                timeWidth: 153,
                timeFormat: 'H:i',
                dateFormat: 'd.m.Y',
                minDate: new Date().toLocaleDateString(),
                minValue: new Date().toLocaleDateString()
            },
            {
                html: _('crontabmanager_task_mute_time_date_desc'),
                cls: 'desc-under',
            },
        ],
    })
    CronTabManager.window.MuteTime.superclass.constructor.call(this, config)
}
Ext.extend(CronTabManager.window.MuteTime, CronTabManager.window.Default)
Ext.reg('crontabmanager-task-window-mute-time', CronTabManager.window.MuteTime)
