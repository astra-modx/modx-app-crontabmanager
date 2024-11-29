CronTabManager.grid.Rules = function (config) {
    config = config || {}
    if (!config.id) {
        config.id = 'crontabmanager-grid-tasks-rules'
        config.namegrid = 'tasks-rules'
        config.processor = 'mgr/task/rule/'
    }

    Ext.applyIf(config, {
        baseParams: {
            action: 'mgr/task/rule/getlist',
            type: 'task',
            dir: 'DESC',
            combo: 1,
            task_id: config.record ? config.record.object.id : 'rule',
        },
        url: CronTabManager.config.connector_url,
        cls: 'crontabmanager-grid',
        multi_select: true,
        stateful: true,
        stateId: config.id,
        pageSize: 5,
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: 0,
            getRowClass: function (rec) {
                return !rec.data.active
                    ? 'crontabmanager-row-disabled'
                    : ''
            }
        },
    })
    CronTabManager.grid.Rules.superclass.constructor.call(this, config)
}
Ext.extend(CronTabManager.grid.Rules, CronTabManager.grid.Default, {

    getFields: function () {
        return ['id', 'task_id', 'class', 'chat_id', 'token', 'fails', 'notice', 'fails_after_successful', 'fails_new_problem', 'successful', 'successful_after_failed', 'name', 'email', 'url', 'createdon', 'updatedon', 'all', 'active', 'actions']
    },

    getColumns: function () {
        return [
            {header: _('crontabmanager_task_rule_id'), dataIndex: 'id', width: 20, sortable: true},
            {header: _('crontabmanager_task_rule_name'), dataIndex: 'name', sortable: true, width: 100},
            {header: _('crontabmanager_task_rule_class'), dataIndex: 'class', sortable: true, width: 100},
            {
                header: _('crontabmanager_task_rule_notice'), dataIndex: 'notice', sortable: true, width: 100, renderer: function (value, cell, row) {
                    var str = ''
                    if (value) {
                        for (var prop in value) {
                            str += value[prop] + ' '+ prop + '<br>'
                        }
                    } else {
                        str = '-'
                    }
                    return '<div class="crontabmanager_task_notice" >' + str + '</div>'
                }
            },
            /*{header: _('crontabmanager_task_rule_task_id'), dataIndex: 'task_id', sortable: true, width: 100},*/
            {header: _('crontabmanager_task_rule_chat_id'), dataIndex: 'chat_id', sortable: true, width: 50, hidden: true},
            {header: _('crontabmanager_task_rule_email'), dataIndex: 'email', sortable: true, width: 50, hidden: true},
            {header: _('crontabmanager_task_rule_url'), dataIndex: 'url', sortable: true, width: 50, hidden: true},
            {header: _('crontabmanager_task_rule_all'), dataIndex: 'all', sortable: true, width: 50, renderer: this._renderBoolean},
            {header: _('crontabmanager_task_rule_active'), dataIndex: 'active', sortable: true, width: 50, renderer: this._renderBoolean},
            {header: _('crontabmanager_task_rule_createdon'), dataIndex: 'createdon', sortable: true, width: 70, hidden: true, renderer: CronTabManager.utils.formatDate},
            {header: _('crontabmanager_task_rule_updatedon'), dataIndex: 'updatedon', sortable: true, width: 70, hidden: true, renderer: CronTabManager.utils.formatDate},
            {
                header: _('crontabmanager_grid_actions'),
                dataIndex: 'actions',
                renderer: CronTabManager.utils.renderActions,
                sortable: false,
                width: 100,
                id: 'actions'
            }
        ]
    },

    getTopBar: function () {
        return [{
            text: '<i class="icon icon-plus"></i>&nbsp;' + _('crontabmanager_task_rule_create'),
            handler: this.createItem,
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

    createItem: function (btn, e) {
        var w = MODx.load({
            xtype: 'crontabmanager-rule-window-create',
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

        w.setValues({active: true})
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
                action: 'mgr/task/rule/get',
                id: id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var w = MODx.load({
                            xtype: 'crontabmanager-rule-window-update',
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
        this.processors.confirm('remove', 'task_rule_remove')
    },
    disableItem: function (grid, row, e) {
        this.processors.multiple('disable')
    },

    getListeners: function () {
        return {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex)
                this.updateItem(grid, e, row)
            },
        }
    },
})
Ext.reg('crontabmanager-grid-tasks-rules', CronTabManager.grid.Rules)
