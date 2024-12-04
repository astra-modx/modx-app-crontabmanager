CronTabManager.grid.Tasks = function (config) {
    config = config || {}
    if (!config.id) {
        config.id = 'crontabmanager-grid-tasks'
        config.namegrid = 'tasks'
        config.processor = 'mgr/task/'
    }

    this.exp = new Ext.grid.RowExpander({
        expandOnDblClick: false,
        tpl: new Ext.Template('<p class="desc">{description} <br>{message}</p>'),
        getRowClass: function (rec) {
            if (!rec.data.active || !rec.data.controller_exists) {
                return 'crontabmanager-row-disabled'
            }
            if (rec.data.mute) {
                return 'crontabmanager-row-mute'
            }

            return ''
        },
        renderer: function (v, p, record) {
            return record.data.description !== '' && record.data.description != null ? '<div class="x-grid3-row-expander">&#160;</div>' : '&#160;'
        }
    })

    Ext.applyIf(config, {
        baseParams: {
            action: 'mgr/task/getlist',
        },
        plugins: this.exp,
        stateful: true,
        stateId: config.id,
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: -10,
            getRowClass: function (rec) {
                // controller_exists
                return (!rec.data.active || !rec.data.controller_exists)
                    ? 'crontabmanager-row-disabled'
                    : ''
            }
        },
        paging: true,
        remoteSort: true,
        autoHeight: true,
    })
    CronTabManager.grid.Tasks.superclass.constructor.call(this, config)
}
Ext.extend(CronTabManager.grid.Tasks, CronTabManager.grid.Default, {

    getFields: function () {
        return ['id', 'description', 'command', 'mute', 'pid_status', 'pid', 'snippet_name', 'mute_success', 'snippet', 'mute_time', 'controller_exists', 'path_task_cli', 'message', 'next_run', 'next_run_human', 'createdon', 'completed', 'updatedon', 'add_output_email', 'mode_develop', 'status', 'is_blocked_time', 'max_number_attempts', 'parent', 'time', 'path_task', 'last_run', 'category_name', 'end_run', 'active', 'actions']
    },

    getColumns: function () {

        return [
            this.exp,
            {
                header: _('crontabmanager_task_id'),
                dataIndex: 'id',
                sortable: true,
                width: 40,
            }, {
                header: _('crontabmanager_task_category_name'),
                dataIndex: 'category_name',
                sortable: true,
                hidden: true,
                width: 70,
                renderer: function (value, e, row) {
                    return value ? value : '---'
                }
            },
            {
                header: _('crontabmanager_task_path_task'),
                dataIndex: 'path_task',
                sortable: true,
                width: 200,
                renderer: function (value, e, row) {


                    if (row.data.snippet) {
                        value = '<a title="' + _('crontabmanager_task_snippet') + '" target="_blank" href="/manager/?a=element/snippet/update&id=' + row.data.snippet + '">' + row.data.snippet_name + '</a>'
                    }
                    if (row.data.mute) {
                        if (row.data.mute_success) {
                            value += '<br><small title="Уведомления для этого задания загрулушены. До первого успешного завершения">mute:success</small>'
                        } else {

                            value += '<br><small title="Уведомления для этого задания загрулушены, до даты">mute:time ' + row.data.mute_time + '</small>'
                        }
                    }


                    return value
                }
            }, {
                header: _('crontabmanager_task_command'),
                dataIndex: 'command',
                sortable: false,
                width: 100
            }, {
                header: _('crontabmanager_task_time'),
                dataIndex: 'time',
                sortable: false,
                width: 70,
                renderer: function (value, e, row) {
                    return String.format('<span class="crontabmanager_time_cron" title="{0}">{0}</span><span class="crontabmanager_next_run">{1}</span>', value, row.data.next_run_human)
                }
            }, {
                header: _('crontabmanager_task_createdon'),
                dataIndex: 'createdon',
                sortable: true,
                width: 70,
                renderer: CronTabManager.utils.formatDate,
                hidden: true
            }, {
                header: _('crontabmanager_task_next_run'),
                dataIndex: 'next_run',
                sortable: false,
                width: 70,
                renderer: CronTabManager.utils.formatDate,
                hidden: true
            }, {
                header: _('crontabmanager_task_updatedon'),
                dataIndex: 'updatedon',
                sortable: true,
                width: 70,
                renderer: CronTabManager.utils.formatDate,
                hidden: true
            }, {
                header: _('crontabmanager_task_last_run'),
                dataIndex: 'last_run',
                sortable: true,
                hidden: true,
                width: 70,
                renderer: CronTabManager.utils.formatDate,
            }, {
                header: _('crontabmanager_task_end_run'),
                dataIndex: 'end_run',
                hidden: true,
                sortable: true,
                width: 70,
                renderer: CronTabManager.utils.formatDate,
            }, {
                header: _('crontabmanager_task_completed'),
                dataIndex: 'completed',
                sortable: true,
                hidden: true,
                width: 70,
                //renderer: CronTabManager.utils.renderBoolean,
                renderer: function (value, e, row) {
                    return value
                        ? String.format('<span class="crontabmanager_task_success" title="{0}"></span>', 'Успешно завершено')
                        : String.format('<span class="crontabmanager_task_insuccess" title="{0}"></span>', 'Не завершено')
                }
            }, {
                header: _('crontabmanager_task_pid_status'),
                dataIndex: 'pid_status',
                sortable: false,
                hidden: true,
                width: 70,
                renderer: function (value, e, row) {
                    return String.format('<span class="crontabmanager_task_pid_{0}" title="{0}">{0}</span>', value)
                }
            }, {
                header: _('crontabmanager_task_status'),
                dataIndex: 'status',
                sortable: false,
                width: 70,
                renderer: function (value, e, row) {

                    var completed = '';

                    var cls = value
                    if (value === 'completed' && !row.data.completed) {
                        completed = ' - с ошибкой';
                        cls = 'completed_error'
                    }

                    return String.format('<span class="crontabmanager_task_pid_{2}" title="{0}">{0} {1}</span>', value, completed, cls)
                }
            },
            {
                header: _('crontabmanager_task_pid'),
                dataIndex: 'pid',
                sortable: true,
                width: 60,
                hidden: true
            },
            {
                header: _('crontabmanager_task_add_output_email'),
                dataIndex: 'add_output_email',
                sortable: true,
                width: 70,
                hidden: true,
                renderer: CronTabManager.utils.renderBoolean,
            }, {
                header: _('crontabmanager_task_max_number_attempts'),
                dataIndex: 'max_number_attempts',
                sortable: true,
                width: 60,
                hidden: true
            }, {
                header: _('crontabmanager_task_active'),
                dataIndex: 'active',
                renderer: CronTabManager.utils.renderBoolean,
                sortable: true,
                width: 60,
            }, {
                header: _('crontabmanager_task_mode_develop'),
                dataIndex: 'mode_develop',
                renderer: CronTabManager.utils.renderBoolean,
                sortable: true,
                width: 60,
                hidden: true
            }, {
                header: _('crontabmanager_grid_actions'),
                dataIndex: 'actions',
                renderer: CronTabManager.utils.renderActions,
                sortable: false,
                width: 100,
                id: 'actions'
            }]
    },

    getTopBar: function (config) {
        return [
            {
                tooltip: _('crontabmanager_task_create'),
                text: '<i class="icon icon-plus"></i>&nbsp;' + _('crontabmanager_task_create'),
                handler: this.createItem,
                scope: this
            },

            {
                text: '<i class="icon icon-cogs"></i> Действия',
                cls: 'primary-button',
                menu: [
                    {
                        tooltip: _('crontabmanager_task_create'),
                        text: '<i class="icon icon-plus"></i>&nbsp;' + _('crontabmanager_task_create'),
                        handler: this.createItem,
                        scope: this
                    },
                    '-',
                    {
                        text: '<i class="icon icon-eye"></i>&nbsp;' + _('crontabmanager_show_crontabs'),
                        handler: this.ShowCrontabs,
                        scope: this,
                    },
                    {
                        text: '<i class="icon icon-eye"></i>&nbsp;' + _('crontabmanager_show_pids'),
                        handler: this.ShowPids,
                        scope: this,
                    },
                ]
            },

            {
                xtype: 'crontabmanager-combo-parent',
                width: 300,
                custm: true,
                clear: true,
                addall: true,
                emptyText: _('crontabmanager_task_parent'),
                name: 'parent',
                id: config.id + '-parent',
                value: '',
                listeners: {
                    select: {
                        fn: this._filterByCombo,
                        scope: this
                    },
                    afterrender: {
                        fn: this._filterByCombo,
                        scope: this
                    }
                }
            },

            {
                xtype: 'label',
                text: ' ' + _('crontabmanager_time_server') + ': ' + CronTabManager.config.time_server,
            },

            '->', {
                xtype: 'xcheckbox',
                name: 'active',
                id: config.id + '-active',
                width: 130,
                boxLabel: _('crontabmanager_task_filter_active'),
                ctCls: 'tbar-checkbox',
                checked: true,
                listeners: {
                    check: {fn: this.activeFilter, scope: this}
                }
            }, this.getSearchField()]
    },

    getListeners: function () {
        return {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex)
                this.updateItem(grid, e, row)
            },
        }
    },

    _filterByCombo: function (cb) {
        this.getStore().baseParams[cb.name] = cb.value
        this.getBottomToolbar().changePage(1)
    },

    activeFilter: function (checkbox, checked) {
        var s = this.getStore()
        s.baseParams.active = checked ? 1 : 0
        this.getBottomToolbar().changePage(1)
    },

    fireParent: function (checkbox, value) {
        var s = this.getStore()
        s.baseParams.parent = value.id
        this.getBottomToolbar().changePage(1)
    },

    _clearSearch: function () {
        this.getStore().baseParams.query = ''
        this.getStore().baseParams.parent = ''
        this.getStore().baseParams.active = 1

        var active = Ext.getCmp('crontabmanager-grid-tasks-active')
        active.setValue(1)

        var parent = Ext.getCmp('crontabmanager-grid-tasks-parent')
        parent.setValue('')

        this.getBottomToolbar().changePage(1)
    },

    createItem: function (btn, e) {
        var w = MODx.load({
            xtype: 'crontabmanager-task-window-create',
            id: Ext.id(),
            listeners: {
                success: {
                    fn: function () {
                        this.refresh()
                    }, scope: this
                }
            }
        })
        w.reset()
        w.setValues({
            active: true,
            path_task: 'Example.php',
            create_new_controller: true,
            minutes: '*/5',
            hours: '*',
            days: '*',
            months: '*',
            weeks: '*',
            minutes_condition: '*/5',
            hours_condition: '*',
            days_condition: '*',
            months_condition: '*',
            weeks_condition: '*'
        })
        w.setValues({log_storage_time: CronTabManager.config.log_storage_time})
        w.show(e.target)
    },
    updateItem: function (btn, e, row) {
        if (typeof (row) != 'undefined') {
            this.menu.record = row.data
        } else if (!this.menu.record) {
            return false
        }
        var id = this.menu.record.id


        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/task/get',
                id: id
            },
            listeners: {
                success: {
                    fn: function (r) {

                        var w = MODx.load({
                            xtype: 'crontabmanager-task-window-update',
                            id: Ext.id(),
                            record: r,
                            listeners: {
                                success: {
                                    fn: function () {
                                        this.refresh()
                                    }, scope: this
                                }
                            }
                        })
                        w.reset()
                        w.setValues(r.object)
                        w.show(e.target)
                    }, scope: this
                }
            }
        })
    },
    enableItem: function () {
        this.processors.multiple('enable')
    },
    removeItem: function () {
        this.processors.confirm('remove', 'task_remove')
    },
    disableItem: function (grid, row, e) {
        this.processors.multiple('disable')
    },

    unblockupTask: function (act, btn, e) {
        this.processors.confirm('unblockup', 'task_unblockup', {multiple: false})
    },
    removeLog: function (act, btn, e) {
        this.processors.confirm('removelog', 'task_removelog', {multiple: false})
    },

    manualStopTask: function (act, btn, e) {
        this.processors.confirm('manualstop', 'task_manualstop', {multiple: false})
    },
    readLog: function (act, btn, e) {
        if (this.win !== null) {
            this.win.destroy()
        }
        this.win = new Ext.Window({
            id: this.config.id + 'readlog'
            , title: 'Task crontab: ' + this.menu.record.path_task
            , width: 900
            , height: 550
            , layout: 'fit'
            , autoLoad: {
                url: CronTabManager.config['connector_url'] + '?action=mgr/task/readlog&id=' + this.menu.record.id,
                scripts: true
            }
        })
        this.win.show()
    },


    muteItem: function (act, btn, e) {
        this.processors.confirm('mute', 'task_mute', {multiple: false})
    },

    unmuteItem: function (act, btn, e) {
        this.processors.confirm('unmute', 'task_unmute', {multiple: false})
    },

    addCron: function (act, btn, e) {
        this.processors.confirm('addcron', 'task_add_cron', {multiple: false})
    },
    removeCron: function (act, btn, e) {
        this.processors.confirm('removecron', 'task_remove_cron', {multiple: false})
    },

    killPid: function (act, btn, e) {
        this.processors.confirm('pid/kill', 'task_pid_kill', {multiple: false})
    },

    win: null,
    runTask: function (act, btn, e) {
        this.runTaskWindow()
    },
    muteTime: function (btn, e, row) {

        // crontabmanager-task-window-mute-time

        if (typeof (row) != 'undefined') {
            this.menu.record = row.data
        } else if (!this.menu.record) {
            return false
        }
        var id = this.menu.record.id

        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/task/get',
                id: id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var w = MODx.load({
                            xtype: 'crontabmanager-task-window-mute-time',
                            id: Ext.id(),
                            record: r,
                            listeners: {
                                success: {
                                    fn: function () {
                                        this.refresh()
                                    }, scope: this
                                }
                            }
                        })
                        w.reset()
                        w.setValues(r.object)
                        w.show(e.target)
                    }, scope: this
                }
            }
        })

    },
    runTaskWindow: function () {

        var connector_args = ''
        var $inputArgs = document.getElementById('crontabmanager_connector_args')
        if ($inputArgs) {
            connector_args = $inputArgs.value
        }

        if (this.win !== null) {
            this.win.destroy()
        }
        this.elementLog = false
        this.win = new Ext.Window({
            id: this.config.id + 'runtask'
            , title: this.menu.record.path_task
            , width: 700
            , height: 450
            , layout: 'fit'
            , autoLoad: {
                url: CronTabManager.config['connector_cron_url'] + '?task_id=' + this.menu.record.id + '&scheduler_path=' + CronTabManager.config.schedulerPath + '&connector_base_path_url=' + CronTabManager.config.schedulerPath + '&connector_args=' + connector_args,
                scripts: true
            }
        })
        this.win.show()
        setTimeout(() => {
            this.refresh();
        }, 300)

    },

    ShowCrontabs: function () {
        if (this.win !== null) {
            this.win.destroy()
        }
        this.win = new Ext.Window({
            id: this.config.id + 'showcrontabs'
            , title: _('crontabmanager_show_crontabs')
            , width: 1100
            , height: 450
            , layout: 'fit'
            , autoLoad: {
                url: CronTabManager.config['connector_url'] + '?action=mgr/showcrontabs',
                scripts: true
            }
        })
        this.win.show()
    },

    ShowPids: function () {
        if (this.win !== null) {
            this.win.destroy()
        }
        this.win = new Ext.Window({
            id: this.config.id + 'pids'
            , title: _('crontabmanager_show_pids')
            , width: 1100
            , height: 450
            , layout: 'fit'
            , autoLoad: {
                url: CronTabManager.config['connector_url'] + '?action=mgr/pids',
                scripts: true
            }
        })
        this.win.show()
    },

    readLogFile: function (btn, e, row) {
        if (typeof (row) != 'undefined') {
            this.menu.record = row.data
        } else if (!this.menu.record) {
            return false
        }
        this.readLogFileBody(this.menu.record)
    },

    elementLog: false,
    readLogFileBody: function (record) {

        if (!this.elementLog) {
            var $win = this.win
            var wrapper = document.createElement('div')
            wrapper.setAttribute('id', 'crontabmanager_area_reading')
            $win.body.dom.appendChild(wrapper)

            this.elementLog = true
        }

        //<div class="loading-indicator">Loading...</div>
        this.setLogFile('<div class="loading-indicator">Loading...</div>')

        MODx.Ajax.request({
            url: CronTabManager.config['connector_url'],
            params: {
                action: 'mgr/task/readlog',
                id: record.id,
                return: true,
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var log = r.object.yesLog ? r.object.content : r.message
                        this.setLogFile(log)
                    }, scope: this
                }
            }
        })

    },

    setLogFile: function (content) {
        var area = document.getElementById('crontabmanager_area_reading')
        area.innerHTML = '<hr>' + content
    },

    copyPathTask: function (btn, e, row) {
        if (typeof (row) != 'undefined') {
            this.menu.record = row.data
        } else if (!this.menu.record) {
            return false
        }

        this.menu.record.path_task_cli
        var area = document.createElement('textarea')
        document.body.appendChild(area)
        area.value = this.menu.record.path_task_cli
        area.select()
        document.execCommand('copy')
        document.body.removeChild(area)

        MODx.msg.status({
            title: _('success')
            , message: _('crontabmanager_task_copyTask_success')
        })

    }

})
Ext.reg('crontabmanager-grid-tasks', CronTabManager.grid.Tasks)

function runTaskWindow() {
    var Tasks = Ext.getCmp('crontabmanager-grid-tasks')
    Tasks.runTaskWindow()
}

function unlockTask() {
    var Tasks = Ext.getCmp('crontabmanager-grid-tasks')
    Tasks.processors.confirm('pid/kill', 'task_pid_kill')
}

function readLogFileBody() {
    var Tasks = Ext.getCmp('crontabmanager-grid-tasks')
    Tasks.readLogFile()
}

