CronTabManager.grid.LogsSync1c = function (config) {
    config = config || {}
    if (!config.id) {
        config.id = 'crontabmanager-grid-tasks-logs'
    }

    Ext.applyIf(config, {
        baseParams: {
            action: 'mgr/task/log/getlist',
            task_id: config.record ? config.record.object.id : 'log',
            type: 'task',
            dir: 'DESC',
            combo: 1
        },
        url: CronTabManager.config.connector_url,
        cls: 'crontabmanager-grid',
        multi_select: true,
        stateful: true,
        stateId: config.id,
        pageSize: 5,
    })
    CronTabManager.grid.LogsSync1c.superclass.constructor.call(this, config)
}
Ext.extend(CronTabManager.grid.LogsSync1c, CronTabManager.grid.Default, {

    getFields: function () {
        return ['id', 'task_id', 'last_run', 'end_run', 'memory_usage', 'ignore_action', 'exec_time', 'auto_pause', 'pause', 'auto_pause_id', 'createdon', 'updatedon', 'completed', 'notification', 'actions']
    },

    getColumns: function () {
        return [
            {header: _('crontabmanager_task_log_id'), dataIndex: 'id', width: 20, sortable: true, hidden: true},
            {header: _('crontabmanager_task_log_last_run'), dataIndex: 'last_run', sortable: true, width: 100, renderer: CronTabManager.utils.formatDate},
            {
                header: _('crontabmanager_task_log_end_run'), dataIndex: 'end_run', sortable: true, width: 100,
                renderer: function (value, e, row) {

                    if (value === '') {
                        return '---';
                    }
                    return CronTabManager.utils.formatDate(value);
                    return String.format('<span class="crontabmanager_task_pid_{0}" title="{0}">{0}</span>', value)
                }
                //renderer: CronTabManager.utils.formatDate
            },
            {header: _('crontabmanager_task_log_completed'), dataIndex: 'completed', sortable: true, width: 50, renderer: this._renderBoolean},
            {header: _('crontabmanager_task_log_ignore_action'), dataIndex: 'ignore_action', sortable: true, width: 50, renderer: this._renderBoolean},
            {header: _('crontabmanager_task_log_notification'), dataIndex: 'notification', sortable: true, width: 50, renderer: this._renderBoolean},
            {
                header: _('crontabmanager_task_log_memory_usage'),
                dataIndex: 'memory_usage',
                sortable: true,
                width: 50,
                hidden: true,
                renderer: function (val, data, row) {
                    return val + ' mb'
                }
            },
            {
                header: _('crontabmanager_task_log_exec_time'),
                dataIndex: 'exec_time',
                sortable: true,
                width: 50,
                hidden: true,
                renderer: function (val, data, row) {
                    return val + ' s'
                }
            },
            {header: _('crontabmanager_task_log_createdon'), dataIndex: 'createdon', sortable: true, width: 70, renderer: CronTabManager.utils.formatDate},
            {
                header: _('crontabmanager_task_log_updatedon'),
                dataIndex: 'updatedon',
                sortable: true,
                width: 70,
                hidden: true,
                renderer: CronTabManager.utils.formatDate
            },
        ]
    },

    getTopBar: function () {
        return [{
            text: '<i class="icon icon-remove"></i>&nbsp;' + _('crontabmanager_task_logs_remove'),
            handler: this.removeAllItem,
            scope: this
        }, '->', this.getSearchField()]
    },
    _renderBoolean: function (value, cell, row) {
        var color, text
        if (value == 0 || value == false || value == undefined) {
            color = 'red'
            text = _('no')
        } else {
            color = 'green'
            text = _('yes')
        }
        return String.format('<span class="{0}">{1}</span>', color, text)
    },

    removeAllItem: function () {
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/task/log/clear',
                task_id: this.config.record.object.id,
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh()
                    }, scope: this
                }
            }
        })
    },

    removeItem: function () {
        var ids = this._getSelectedIds()
        if (!ids.length) {
            return false
        }

        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/task/log/remove',
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh()
                    }, scope: this
                }
            }
        })

        return true
    },

})
Ext.reg('crontabmanager-grid-tasks-logs', CronTabManager.grid.LogsSync1c)
