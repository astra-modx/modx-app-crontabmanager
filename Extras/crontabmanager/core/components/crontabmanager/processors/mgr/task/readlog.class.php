<?php

/**
 * Get an Task
 */
class CronTabManagerTaskGetProcessor extends modObjectGetProcessor
{
    /* @var CronTabManagerTask $object */
    public $object;
    public $objectType = 'CronTabManagerTask';
    public $classKey = 'CronTabManagerTask';
    public $languageTopics = array('crontabmanager:default');
    #public $permission = 'crontabmanager_view';

    /**
     * We doing special check of permission
     * because of our objects is not an instances of modAccessibleObject
     *
     * @return mixed
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $startLine = $this->getProperty('startLine', 1);
        $startLine = (int)$startLine;
        if ($startLine < 1) {
            $startLine = 1;
        }

        $content = $this->readLogFileFormat($startLine);
        #$content = $this->object->readLogFileFormat();
        $yesLog = !empty($content);
        $return = $this->getProperty('return', false);
        if (!$return) {
            exit($content);
        } else {
            return $this->success($this->modx->lexicon('crontabmanager_not_log_content'), [
                'yesLog' => $yesLog,
                'content' => $content,
                'lastLine' => $this->lastLine,
                'state' => $this->object->pid()->isLock() ? 'repeat' : 'stop',
            ]);
        }
    }

    public $lastLine = 0;

    public function readLogFileFormat($startLine = 1)
    {
        $data = $this->readLogFile($startLine);
        $content = $data['content'];
        $this->lastLine = $data['lastLine'];


        $content = nl2br($content);
        $content = str_ireplace('✘', '❌', $content);
        $content = str_ireplace('✔', '✅', $content);
        #$content = $content;

        #$content = '<pre>'.$content.'</pre>';

        return $content;
    }


    public function readLogFile($startLine = 1)
    {
        $path = $this->object->getFileLogPath();

        if (!file_exists($path)) {
            return false;
        }

        $content = [];
        $lastLine = 0;

        $file = new SplFileObject($path, 'r');

        // Перемещаем указатель на указанную строку
        $file->seek($startLine - 1);

        // Читаем файл построчно
        while (!$file->eof()) {
            $content[] = $file->current();
            $lastLine = $file->key() + 1; // Номер текущей строки
            $file->next();
        }

        return [
            'content' => implode('', $content), // Объединяем строки в один текст
            'lastLine' => $lastLine,           // Номер последней прочитанной строки
        ];
    }
    /*public function readLogFile()
    {
        $content = false;
        $path = $this->object->getFileLogPath();
        if (file_exists($path)) {
            // Получаем только часть строки
            $content = file_get_contents($path, false, null, 0, 10024);
        }

        return $content;
    }*/
}

return 'CronTabManagerTaskGetProcessor';
