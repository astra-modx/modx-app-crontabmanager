CronTabManager.combo.Search = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        xtype: 'twintrigger',
        ctCls: 'x-field-search',
        allowBlank: true,
        msgTarget: 'under',
        emptyText: _('search'),
        name: 'query',
        triggerAction: 'all',
        clearBtnCls: 'x-field-search-clear',
        searchBtnCls: 'x-field-search-go',
        onTrigger1Click: this._triggerSearch,
        onTrigger2Click: this._triggerClear,
    })
    CronTabManager.combo.Search.superclass.constructor.call(this, config)
    this.on('render', function () {
        this.getEl().addKeyListener(Ext.EventObject.ENTER, function () {
            this._triggerSearch()
        }, this)
    })
    this.addEvents('clear', 'search')
}
Ext.extend(CronTabManager.combo.Search, Ext.form.TwinTriggerField, {

    initComponent: function () {
        Ext.form.TwinTriggerField.superclass.initComponent.call(this)
        this.triggerConfig = {
            tag: 'span',
            cls: 'x-field-search-btns',
            cn: [
                {tag: 'div', cls: 'x-form-trigger ' + this.searchBtnCls},
                {tag: 'div', cls: 'x-form-trigger ' + this.clearBtnCls}
            ]
        }
    },

    _triggerSearch: function () {
        this.fireEvent('search', this)
    },

    _triggerClear: function () {
        this.fireEvent('clear', this)
    },

})
Ext.reg('crontabmanager-combo-search', CronTabManager.combo.Search)
Ext.reg('crontabmanager-field-search', CronTabManager.combo.Search)

CronTabManager.combo.Parent = function (config) {
    config = config || {}

    Ext.applyIf(config, {
        name: config.name || 'status'
        , fieldLabel: _('crontabmanager_task_parent_empty')
        , emptyText: _('crontabmanager_task_parent_empty')
        , hiddenName: config.name || 'parent'
        , displayField: 'name'
        , valueField: 'id'
        , anchor: '99%'
        , fields: ['name', 'id']
        , pageSize: 20
        , url: CronTabManager.config.connector_url
        , typeAhead: true
        , editable: true
        , allowBlank: true
        , baseParams: {
            action: 'mgr/category/getlist'
            , combo: 1
            , id: config.value
        }
    })
    CronTabManager.combo.Parent.superclass.constructor.call(this, config)
}
Ext.extend(CronTabManager.combo.Parent, MODx.combo.ComboBox)
Ext.reg('crontabmanager-combo-parents', CronTabManager.combo.Parent)

/**
 * Combo SeoType
 * @param config
 * @constructor
 */
CronTabManager.combo.Parents = function (config) {
    config = config || {}

    if (config.custm) {
        config.triggerConfig = [{
            tag: 'div',
            cls: 'x-field-search-btns',
            style: String.format('width: {0}px;', config.clear ? 62 : 31),
            cn: [{
                tag: 'div',
                cls: 'x-form-trigger x-field-crontabmanager-active-go'
            }]
        }]
        if (config.clear) {
            config.triggerConfig[0].cn.push({
                tag: 'div',
                cls: 'x-form-trigger x-field-crontabmanager-active-clear'
            })
        }

        config.initTrigger = function () {
            var ts = this.trigger.select('.x-form-trigger', true)
            this.wrap.setStyle('overflow', 'hidden')
            var triggerField = this
            ts.each(function (t, all, index) {
                t.hide = function () {
                    var w = triggerField.wrap.getWidth()
                    this.dom.style.display = 'none'
                    triggerField.el.setWidth(w - triggerField.trigger.getWidth())
                }
                t.show = function () {
                    var w = triggerField.wrap.getWidth()
                    this.dom.style.display = ''
                    triggerField.el.setWidth(w - triggerField.trigger.getWidth())
                }
                var triggerIndex = 'Trigger' + (index + 1)

                if (this['hide' + triggerIndex]) {
                    t.dom.style.display = 'none'
                }
                t.on('click', this['on' + triggerIndex + 'Click'], this, {
                    preventDefault: true
                })
                t.addClassOnOver('x-form-trigger-over')
                t.addClassOnClick('x-form-trigger-click')
            }, this)
            this.triggers = ts.elements
        }
    }
    Ext.applyIf(config, {
        name: config.name || 'field',
        hiddenName: config.name || 'field',
        displayField: 'name',
        valueField: 'id',
        editable: true,
        fields: ['name', 'id'],
        pageSize: 10,
        hideMode: 'offsets',
        url: CronTabManager.config.connector_url,
        baseParams: {
            action: 'mgr/category/getlist'
            , combo: 1
            , id: config.value,
            addall: config.addall || 0
        },
        tpl: new Ext.XTemplate(
            '<tpl for="."><div class="x-combo-list-item">',
            '{name}</span>',
            '</div></tpl>', {
                compiled: true
            }),
        cls: 'input-combo-crontabmanager-field-values',
        clearValue: function () {
            if (this.hiddenField) {
                this.hiddenField.value = ''
            }
            this.setRawValue('')
            this.lastSelectionText = ''
            this.applyEmptyText()
            this.value = ''
            this.fireEvent('select', this, null, 0)
        },

        getTrigger: function (index) {
            return this.triggers[index]
        },

        onTrigger1Click: function () {
            this.onTriggerClick()
        },

        onTrigger2Click: function () {
            this.clearValue()
        }
    })
    CronTabManager.combo.Parents.superclass.constructor.call(this, config)

}
Ext.extend(CronTabManager.combo.Parents, MODx.combo.ComboBox)
Ext.reg('crontabmanager-combo-parent', CronTabManager.combo.Parents)

/**
 * Filter Active
 * @param config
 * @constructor
 */
CronTabManager.combo.When = function (config) {
    config = config || {}

    if (config.custm) {
        config.triggerConfig = [{
            tag: 'div',
            cls: 'x-field-search-btns',
            style: String.format('width: {0}px;', config.clear ? 62 : 31),
            cn: [{
                tag: 'div',
                cls: 'x-form-trigger x-field-crontabmanager-when-go'
            }]
        }]
        if (config.clear) {
            config.triggerConfig[0].cn.push({
                tag: 'div',
                cls: 'x-form-trigger x-field-crontabmanager-when-clear'
            })
        }

        config.initTrigger = function () {
            var ts = this.trigger.select('.x-form-trigger', true)
            this.wrap.setStyle('overflow', 'hidden')
            var triggerField = this
            ts.each(function (t, all, index) {
                t.hide = function () {
                    var w = triggerField.wrap.getWidth()
                    this.dom.style.display = 'none'
                    triggerField.el.setWidth(w - triggerField.trigger.getWidth())
                }
                t.show = function () {
                    var w = triggerField.wrap.getWidth()
                    this.dom.style.display = ''
                    triggerField.el.setWidth(w - triggerField.trigger.getWidth())
                }
                var triggerIndex = 'Trigger' + (index + 1)

                if (this['hide' + triggerIndex]) {
                    t.dom.style.display = 'none'
                }
                t.on('click', this['on' + triggerIndex + 'Click'], this, {
                    preventDefault: true
                })
                t.addClassOnOver('x-form-trigger-over')
                t.addClassOnClick('x-form-trigger-click')
            }, this)
            this.triggers = ts.elements
        }
    }
    Ext.applyIf(config, {
        name: config.name || 'when',
        hiddenName: config.name || 'when',
        displayField: 'name',
        valueField: 'value',
        editable: true,
        fields: ['name', 'value'],
        pageSize: 10,
        emptyText: _('crontabmanager_combo_select'),
        hideMode: 'offsets',
        url: CronTabManager.config.connector_url,
        baseParams: {
            action: 'mgr/misc/when/getlist',
            combo: true,
            addall: config.addall || 0
        },
        tpl: new Ext.XTemplate(
            '<tpl for="."><div class="x-combo-list-item">',
            '<small>({value})</small> <b>{name}</b></span>',
            '</div></tpl>', {
                compiled: true
            }),
        cls: 'input-combo-crontabmanager-when',
        clearValue: function () {
            if (this.hiddenField) {
                this.hiddenField.value = ''
            }
            this.setRawValue('')
            this.lastSelectionText = ''
            this.applyEmptyText()
            this.value = ''
            this.fireEvent('select', this, null, 0)
        },

        getTrigger: function (index) {
            return this.triggers[index]
        },

        onTrigger1Click: function () {
            this.onTriggerClick()
        },

        onTrigger2Click: function () {
            this.clearValue()
        }
    })
    CronTabManager.combo.When.superclass.constructor.call(this, config)
}

Ext.extend(CronTabManager.combo.When, MODx.combo.ComboBox)
Ext.reg('crontabmanager-combo-filter-when', CronTabManager.combo.When)

CronTabManager.combo.Classes = function (config) {
    config = config || {}
    Ext.applyIf(config, {
        id: 'crontabmanager-combo-classes',
        fieldLabel: _('crontabmanager_class'),
        name: 'class',
        hiddenName: 'class',
        displayField: 'class',
        valueField: 'class',
        pageSize: 20,
        fields: ['type', 'class'],
        url: CronTabManager.config['connector_url'],
        baseParams: {
            action: 'mgr/misc/provider/getclass',
            type: config.type || '',
        },
        allowBlank: true,
        editable: true,
    })
    CronTabManager.combo.Classes.superclass.constructor.call(this, config)
}
Ext.extend(CronTabManager.combo.Classes, MODx.combo.ComboBox)
Ext.reg('crontabmanager-combo-classes', CronTabManager.combo.Classes)


/**
 * Filter Active
 * @param config
 * @constructor
 */
CronTabManager.combo.condition = function (config) {
    config = config || {}
    var field = config.field || 'minutes'


    var dataTime = CronTabManager.config.time_cron[field];

    var data = []
    for (var i = 0; i < dataTime.length; i++) {
        var row = dataTime[i];
        data.push([row['value'], row['name']])
    }


    Ext.applyIf(config, {
        emptyText: _('bxsender_mailsender_transport')
        , hiddenName: field + '_condition'
        , store: new Ext.data.ArrayStore({
            id: 1
            , fields: ['condition', 'display']
            , data: data
            /* , data: [
                 ['*', _('crontabmanager_condition_my')],
                 ['*!/1', _('crontabmanager_condition_' + field)],
                 ['*!/5', _('crontabmanager_condition_' + field + '_5')],
                 ['*!/10', _('crontabmanager_condition_' + field + '_10')],
                 ['*!/15', _('crontabmanager_condition_' + field + '_15')],
                 ['*!/30', _('crontabmanager_condition_' + field + '_30')],
             ]*/
        })
        , mode: 'local'
        , valueField: 'condition'
        , displayField: 'display'
        , listeners: {
            'select': {
                fn: function (f, value) {
                    var field = f.id.split("source-")[1];
                    var id = f.id.split("-source-")[0];
                    var val = value.data.condition
                    if (val === 'my') {
                        val = '*'
                    }
                    Ext.getCmp(id + '-target-' + field).setValue(val)
                }, scope: this
            }
        }
    })
    CronTabManager.combo.condition.superclass.constructor.call(this, config)

    this.on('render', function () {
        //this.getEl().setFormValue('*/5')
        /* this.getEl().addKeyListener(Ext.EventObject.ENTER, function () {
             this._triggerSearch()
         }, this)*/
    })
}
Ext.extend(CronTabManager.combo.condition, MODx.combo.ComboBox)
Ext.reg('crontabmanager-combo-condition', CronTabManager.combo.condition)


CronTabManager.combo.Snippet = function (config) {
    config = config || {}

    // description
    Ext.applyIf(config, {
        name: config.name || 'status'
        , fieldLabel: _('crontabmanager_task_snippet_empty')
        , emptyText: _('crontabmanager_task_snippet_empty')
        , hiddenName: config.name || 'snippet'
        , displayField: 'name'
        , valueField: 'id'
        , anchor: '99%'
        , fields: ['name', 'id', 'description']
        , pageSize: 5
        , url: CronTabManager.config.connector_url
        , typeAhead: true
        , editable: true
        , allowBlank: true
        , tpl: new Ext.XTemplate(
            '<tpl for="."><div class="x-combo-list-item">',
            '{name}  {description}</span>',
            '</div></tpl>', {
                compiled: true
            })
        , baseParams: {
            action: 'mgr/misc/snippet/getlist'
            , combo: 1
            , addall: 1
            , id: config.value
        }
    })
    CronTabManager.combo.Snippet.superclass.constructor.call(this, config)
}
Ext.extend(CronTabManager.combo.Snippet, MODx.combo.ComboBox)
Ext.reg('crontabmanager-combo-snippets', CronTabManager.combo.Snippet)
