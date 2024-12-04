CronTabManager.form.UpdateSetting = function (config) {
    config = config || {}
    this.ident = config.ident || 'setting' + Ext.id()
    config.id = this.ident


    Ext.applyIf(config, {
        cls: 'container form-with-labels main-wrapper crontabmanager_buttons_settings'
        , labelAlign: 'left'
        , autoHeight: true
        , title: _('search_criteria')
        , labelWidth: 300
        , url: CronTabManager.config.connector_url
        , items: this.getFields(config)
        , style: {margin: '15px 30px'}
        , buttonAlign: 'top'
        , defaults: {
            anchor: '80%'
        },
        listeners: {},

    })
    CronTabManager.form.UpdateSetting.superclass.constructor.call(this, config)


    this.on('afterrender', function () {
        setTimeout(() => { // Используем стрелочную функцию
            var sourceDiv = document.getElementById('crontabmanager-panel-home-div-help');
            var targetDiv = document.getElementById('crontabmanager_help');

            // Проверяем, что элементы найдены
            if (sourceDiv && targetDiv) {
                // Удаляем атрибут display: none из style
                // Копируем содержимое из sourceDiv в targetDiv
                targetDiv.innerHTML = sourceDiv.innerHTML;
                targetDiv.style.display = '';  // Это удалит inline стиль display: none

                sourceDiv.remove()
            }

            this.checkAvailabilityCrontab(); // `this` сохранён
        }, 300);
    });
}
Ext.extend(CronTabManager.form.UpdateSetting, MODx.FormPanel, {

    getFields: function (config) {
        return [
            {
                layout: 'form',
                items: [
                    {
                        xtype: 'displayfield',
                        style: {margin: '0px 0 15px 0px', color: '#666666'},
                        hideLabel: true,
                        name: 'transport_desc',
                        anchor: '70%',
                        id: config.id + '-transport_desc',
                        //html: atob(CronTabManager.config.html),
                    },

                ]
            },
        ]
    },

    checkAvailabilityCrontab: function () {

        checkAvailabilityCrontab()
    }


})
Ext.reg('crontabmanager-form-setting-update', CronTabManager.form.UpdateSetting)


function scheduleCronTabAjax(action) {

    MODx.msg.confirm({
        title: _('crontabmanager_schedule_confirm_' + action + '_title')
        , text: _('crontabmanager_schedule_confirm_' + action + '_text')
        , url: CronTabManager.config.connectorUrl
        , params: {
            action: 'mgr/setting/schedule/' + action,
        }
        , listeners: {
            success: {
                fn: function (r) {
                    checkAvailabilityCrontab()
                    MODx.msg.status({
                        title: _('success')
                        , message: _('crontabmanager_schedule_confirm_' + action + '_success')
                    })

                }, scope: this
            }
        }
    });

}


function checkAvailabilityCrontab() {

    MODx.Ajax.request({
        url: CronTabManager.config.connectorUrl,
        params: {
            action: 'mgr/setting/schedule/check',
        },
        listeners: {
            success: {
                fn: function (r) {

                    if (r.object.available) {

                        var element = document.getElementById('schedule_service');
                        element.style.display = 'block';

                        var status = document.getElementById('schedule_service_status');
                        status.classList.remove('success');
                        status.classList.remove('error');
                        status.classList.add('crontabmanager-status');
                        status.classList.add(r.object.find ? 'success' : 'error');
                        status.innerHTML = r.object.status;

                        /// Add
                        document.getElementById('schedule_service_button_add')
                            .style.display = r.object.find ? 'none' : 'block';

                        /// Remove
                        document.getElementById('schedule_service_button_remove')
                            .style.display = !r.object.find ? 'none' : 'block';
                    }

                }, scope: this
            },
            failure: {
                fn: function (r) {

                }, scope: this
            }
        }
    })
}

