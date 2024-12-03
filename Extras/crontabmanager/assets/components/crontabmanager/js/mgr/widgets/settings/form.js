CronTabManager.form.UpdateSetting = function (config) {
    config = config || {}
    this.ident = config.ident || 'setting' + Ext.id()
    config.id = this.ident
    config.record = {
        transport: "system"
    }
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
        setTimeout(function () {
            var sourceDiv = document.getElementById('crontabmanager-panel-home-div-help');
            var targetDiv = document.getElementById('crontabmanager_help');

            // Проверяем, что элементы найдены
            if (sourceDiv && targetDiv) {
                // Удаляем атрибут display: none из style
                // Копируем содержимое из sourceDiv в targetDiv
                targetDiv.innerHTML = sourceDiv.innerHTML;
                targetDiv.style.display = '';  // Это удалит inline стиль display: none
            }


        }, 300)
    })
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


})
Ext.reg('crontabmanager-form-setting-update', CronTabManager.form.UpdateSetting)
