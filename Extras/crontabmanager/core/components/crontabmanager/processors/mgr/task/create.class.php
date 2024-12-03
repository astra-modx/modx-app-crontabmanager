<?php

use Webnitros\CronTabManager\Helpers\TemplateController;

/**
 * Create an Task
 */
class CronTabManagerTaskCreateProcessor extends modObjectCreateProcessor
{
    /* @var CronTabManagerTask $object */
    public $object = 'CronTabManagerTask';
    public $objectType = 'CronTabManagerTask';
    public $classKey = 'CronTabManagerTask';
    public $languageTopics = array('crontabmanager:manager');
    public $permission = 'crontabmanager_create';


    /** {@inheritDoc} */
    public function initialize()
    {
        if (!$this->modx->hasPermission($this->permission)) {
            return $this->modx->lexicon('access_denied');
        }

        return parent::initialize();
    }


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $path_task = trim($this->getProperty('path_task'));
        if (empty($path_task)) {
            $this->modx->error->addField('path_task', $this->modx->lexicon('crontabmanager_task_err_path_task'));
        } elseif ($this->modx->getCount($this->classKey, array('path_task' => $path_task))) {
            $this->modx->error->addField('path_task', $this->modx->lexicon('crontabmanager_task_err_ae'));
        }

        $create_new_controller = $this->setCheckbox('create_new_controller');

        $TemplateController = new TemplateController($this->modx);
        if (!$TemplateController->fileExists($path_task)) {
            if ($create_new_controller) {
                $response = $TemplateController->process($path_task);
                if ($response !== true) {
                    $this->modx->error->addField('path_task', $response);
                }
            } else {
                $this->modx->error->addField('path_task', $this->modx->lexicon('crontabmanager_task_err_ae_controller', array('controller' => $controller)));
            }
        }

        $this->setProperty('status', 1);
        #$this->setProperty('active', false);
        $this->setCheckbox('active');

        return parent::beforeSet();
    }
}

return 'CronTabManagerTaskCreateProcessor';
