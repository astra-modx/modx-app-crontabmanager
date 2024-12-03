CronTabManager.window.CreateRule = function (config) {
    config = config || {}
    config.url = CronTabManager.config.connector_url

    Ext.applyIf(config, {
        title: _('crontabmanager_task_rule_create'),
        width: 1100,
        cls: 'crontabmanager_windows',
        baseParams: {
            action: 'mgr/task/rule/create',
        }
    })
    CronTabManager.window.CreateRule.superclass.constructor.call(this, config)
}
Ext.extend(CronTabManager.window.CreateRule, CronTabManager.window.Default, {

    toggleFields: function (config, value) {

        // Telegram
        var chatIdField = Ext.getCmp(config.id + '-chat_id')
        var tokenField = Ext.getCmp(config.id + '-token')
        var methodField = Ext.getCmp(config.id + '-method_http')
        var paramsField = Ext.getCmp(config.id + '-params')

        // Email
        var emailField = Ext.getCmp(config.id + '-email')

        // Webhook
        var urlField = Ext.getCmp(config.id + '-url')

        // Hide all fields
        chatIdField.hide()
        tokenField.hide()
        emailField.hide()
        urlField.hide()
        methodField.hide()
        // Show the fields based on selected class
        paramsField.show()
        switch (value) {
            case 'Telegram':
                chatIdField.show()
                tokenField.show()
                break
            case 'Email':
                emailField.show()
                break
            case 'Webhook':
                urlField.show()
                methodField.show()
                break
        }

    },
    getFields: function (config) {

        var categories = ''
        if (config.record && config.record.object) {
            if (config.record.object.categories) {
                categories = config.record.object.categories
            }
        }

        return [
            {xtype: 'hidden', name: 'id', id: config.id + '-id'},

            {
                layout: 'column',
                items: [
                    {
                        columnWidth: .33,
                        layout: 'form',
                        defaults: {msgTarget: 'under'},
                        items: [

                            {
                                xtype: 'xcheckbox',
                                boxLabel: _('crontabmanager_task_rule_all'),
                                name: 'all',
                                id: config.id + '-all',
                                checked: false,
                                listeners: {
                                    check: function (checkbox, checked) {
                                        // Ваш код для обработки изменения состояния чекбокса

                                        var categoriesField = Ext.getCmp(config.id + '-categories')
                                        if (categoriesField) {
                                            categoriesField.setVisible(!checked)
                                        }

                                    },
                                    afterrender: function (checkbox, checked) {
                                        // Ваш код для обработки инициализации чекбокса
                                        var categoriesField = Ext.getCmp(config.id + '-categories')
                                        if (categoriesField) {
                                            categoriesField.setVisible(!checkbox.checked)
                                        }

                                    }
                                }
                            },
                            {
                                html: _('crontabmanager_task_rule_all_desc'),
                                cls: 'desc-under',
                            },
                            {
                                xtype: 'label',
                                html: _('crontabmanager_task_rule_tasks'),
                            },
                            {
                                hidden: true,
                                xtype: 'crontabmanager-tree-categories',
                                name: 'categories',
                                id: config.id + '-categories',
                                baseParams: {
                                    class_key_resource: 'msCategory',
                                    parent: 0,
                                    type: 'modResource',
                                    categories: categories,
                                },
                                categories: categories,
                                maxHeight: 320,
                                listeners: {
                                    checkchange: function (node, checked) {
                                        var catField = Ext.getCmp(config.id + '-categories')
                                        if (node && catField) {
                                            var value
                                            if (catField.getValue() == '[]') {
                                                value = {}
                                            } else {
                                                value = Ext.util.JSON.decode(catField.getValue())
                                            }
                                            value[node.attributes.pk] = Number(checked)
                                            catField.setValue(Ext.util.JSON.encode(value))
                                        }
                                    }
                                }
                            },
                            {
                                html: _('crontabmanager_task_rule_categories_desc'),
                                cls: 'desc-under',
                                style: {margin: ' 15px 0 0 0'}
                            },

                        ]
                    },
                    {
                        columnWidth: .33,
                        layout: 'form',
                        defaults: {msgTarget: 'under'},
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: _('crontabmanager_task_rule_name'),
                                name: 'name',
                                id: config.id + '-name',
                                anchor: '99%',
                                allowBlank: false
                            }, {
                                xtype: 'textfield',
                                fieldLabel: _('crontabmanager_task_rule_message'),
                                name: 'message',
                                id: config.id + '-message',
                                anchor: '99%',
                                allowBlank: true
                            },
                            {
                                html: _('crontabmanager_task_rule_message_desc'),
                                cls: 'desc-under',
                            },

                            {
                                xtype: 'crontabmanager-combo-classes',
                                fieldLabel: _('crontabmanager_task_rule_class'),
                                name: 'class',
                                id: config.id + '-class',
                                anchor: '99%',
                                listeners: {
                                    'select': function (combo, value) {
                                        if (value.data.class) {
                                            this.toggleFields(config, value.data.class)
                                        }

                                    },
                                    'afterrender': function (combo, value) {
                                        if (!value && config.record && config.record.object !== undefined) {
                                            var provider = config.record.object.class || null
                                            this.toggleFields(config, provider)
                                        }
                                    },
                                    scope: this
                                }
                            },
                            // Telegram
                            {
                                xtype: 'textfield',
                                fieldLabel: _('crontabmanager_task_rule_chat_id'),
                                name: 'chat_id',
                                id: config.id + '-chat_id',
                                anchor: '99%',
                                hidden: true
                            },

                            {
                                xtype: 'textfield',
                                fieldLabel: _('crontabmanager_task_rule_token'),
                                name: 'token',
                                id: config.id + '-token',
                                anchor: '99%',
                                hidden: true
                            },

                            // Email
                            {
                                xtype: 'textfield',
                                fieldLabel: _('crontabmanager_task_rule_email'),
                                name: 'email',
                                id: config.id + '-email',
                                anchor: '99%',
                                hidden: true
                            },

                            // Webhook
                            {
                                xtype: 'textfield',
                                fieldLabel: _('crontabmanager_task_rule_url'),
                                name: 'url',
                                id: config.id + '-url',
                                anchor: '99%',
                                hidden: true
                            },

                            {
                                xtype: 'xcheckbox',
                                boxLabel: _('crontabmanager_task_rule_active'),
                                name: 'active',
                                id: config.id + '-active',
                                checked: true,
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: _('crontabmanager_task_rule_method_http'),
                                name: 'method_http',
                                id: config.id + '-method_http',
                                anchor: '99%',
                                hidden: true
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: _('crontabmanager_task_rule_params'),
                                name: 'params',
                                id: config.id + '-params',
                                anchor: '99%',
                                hidden: true
                            },

                        ],
                    },
                    {
                        columnWidth: .33,
                        layout: 'form',
                        defaults: {msgTarget: 'under'},
                        items: [
                            {
                                html: _('crontabmanager_task_rule_criteria_desc'),
                                cls: 'desc-under',
                                style: {margin: ' 15px 0 0 0'}
                            },
                            {
                                xtype: 'xcheckbox',
                                boxLabel: _('crontabmanager_task_rule_fails'),
                                name: 'fails',
                                id: config.id + '-fails',
                                checked: true,
                            },

                            {
                                xtype: 'xcheckbox',
                                boxLabel: _('crontabmanager_task_rule_fails_after_successful'),
                                name: 'fails_after_successful',
                                id: config.id + '-fails_after_successful',
                                checked: false
                            },

                            {
                                xtype: 'xcheckbox',
                                boxLabel: _('crontabmanager_task_rule_fails_new_problem'),
                                name: 'fails_new_problem',
                                id: config.id + '-fails_new_problem',
                                checked: false
                            },

                            {
                                xtype: 'xcheckbox',
                                boxLabel: _('crontabmanager_task_rule_successful'),
                                name: 'successful',
                                id: config.id + '-successful',
                                checked: false,
                            },

                            {
                                xtype: 'xcheckbox',
                                boxLabel: _('crontabmanager_task_rule_successful_after_failed'),
                                name: 'successful_after_failed',
                                id: config.id + '-successful_after_failed',
                                checked: false
                            },

                        ],
                    }
                ]

            },

        ]
    },

})
Ext.reg('crontabmanager-rule-window-create', CronTabManager.window.CreateRule)

CronTabManager.window.UpdateRule = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        title: _('crontabmanager_task_autopause_update'),
        baseParams: {
            action: 'mgr/task/rule/update'
        },
    })
    CronTabManager.window.UpdateRule.superclass.constructor.call(this, config)

}
Ext.extend(CronTabManager.window.UpdateRule, CronTabManager.window.CreateRule)
Ext.reg('crontabmanager-rule-window-update', CronTabManager.window.UpdateRule)
