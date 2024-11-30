<?php

/**
 * The home manager controller for CronTabManager.
 *
 */
class CronTabManagerHomeManagerController extends modExtraManagerController
{
    /** @var CronTabManager $CronTabManager */
    public $CronTabManager;

    public $version = '1.0.0';

    /**
     *
     */
    public function initialize()
    {
        $this->CronTabManager = $this->modx->getService('CronTabManager', 'CronTabManager', MODX_CORE_PATH.'components/crontabmanager/model/');
        $this->tokenIssuance();
        parent::initialize();
        $this->version = $this->modx->getOption('crontabmanager_version');
    }


    /**
     *  Выдача api_key после подтверждения прав
     */
    public function tokenIssuance()
    {
        if (!empty($_GET['oauth']) && $_GET['oauth'] === 'application') {
            if (!$this->modx->hasPermission('crontabmanager_view')) {
                $this->failure($this->modx->lexicon('access_denied'));
            } else {
                $Auth = new \Webnitros\CronTabManager\Auth($this->CronTabManager);
                $Auth->createApiKey($this->modx->user);
            }
        }
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['crontabmanager:manager', 'crontabmanager:default'];
    }


    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('crontabmanager_view');
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('crontabmanager');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss('mgr/main.css');
        $this->addJavascript('mgr/crontabmanager.js');
        $this->addJavascript('mgr/misc/strftime-min-1.3.js');
        $this->addJavascript('mgr/misc/utils.js');
        $this->addJavascript('mgr/misc/combo.js');
        $this->addJavascript('mgr/misc/tree.js');
        $this->addJavascript('mgr/misc/processorx.js');
        $this->addJavascript('mgr/misc/default.grid.js');
        $this->addJavascript('mgr/misc/default.window.js');
        $this->addJavascript('mgr/widgets/tasks/grid.js');
        $this->addJavascript('mgr/widgets/tasks/logs/grid.js');
        $this->addJavascript('mgr/widgets/tasks/rules/grid.js');
        $this->addJavascript('mgr/widgets/tasks/rules/windows.js');
        $this->addJavascript('mgr/widgets/tasks/windows.js');
        $this->addJavascript('mgr/widgets/categories/grid.js');
        $this->addJavascript('mgr/widgets/categories/windows.js');
        $this->addJavascript('mgr/widgets/notifications/grid.js');
        $this->addJavascript('mgr/widgets/notifications/windows.js');
        $this->addJavascript('mgr/widgets/home.panel.js');
        $this->addJavascript('mgr/sections/home.js');

        $time_server = date('H:i:s', time());

        $CrontabIsAvailable = \Webnitros\CronTabManager\Crontab::isAvailable() ? 'yes' : 'no';

        $this->CronTabManager->config['help_buttons'] = ($buttons = $this->getButtons()) ? $buttons : '';

        $this->addHtml(
            '<script type="text/javascript">
        CronTabManager.config = '.json_encode($this->CronTabManager->config).';
        CronTabManager.config.connector_url = "'.$this->CronTabManager->config['connectorUrl'].'";
        CronTabManager.config.connector_cron_url = "'.$this->CronTabManager->config['connectorCronUrl'].'";
        CronTabManager.config.time_server = "'.$time_server.'";
        CronTabManager.config.crontab_is_available = "'.$CrontabIsAvailable.'";
        Ext.onReady(function() {MODx.load({ xtype: "crontabmanager-page-home"});});
        </script>'
        );
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="crontabmanager-panel-home-div"></div>';

        return '';
    }


    /**
     * @return string
     */
    public function getButtons()
    {
        $buttons = null;
        $buttons[] = [
            'url' => 'https://tteck.github.io/Proxmox/',
            'text' => '<i class="icon-question-circle icon icon-large"></i>  Команды',
        ];

        $buttons[] = [
            'url' => 'https://docs.modx.pro/components/scheduler/',
            'text' => '<i class="icon-question-circle icon icon-large"></i> '.$this->modx->lexicon('crontabmanager_button_help'),
        ];

        return $buttons;
    }

    /**
     * Add an external Javascript file to the head of the page
     *
     * @param  string  $script
     * @return void
     */
    public function addJavascript($script)
    {
        parent::addJavascript($this->CronTabManager->config['jsUrl'].$script.'?v='.$this->version);
    }

    public function addCss($script)
    {
        parent::addCss($this->CronTabManager->config['cssUrl'].$script.'?v='.$this->version);
    }

}
