<?php

class CronTabManagerClassGetListProcessor extends modProcessor
{
    /**
     * @return string
     */
    public function process()
    {
        // отправка email уведомления
        /* @var CronTabManager $CronTabManager */
        $CronTabManager = $this->modx->getService('crontabmanager', 'CronTabManager', MODX_CORE_PATH . 'components/crontabmanager/model/');

        $dir = MODX_CORE_PATH . 'components/crontabmanager/src/Event/Providers';

        $classes = array();

// Получаем список файлов в директории
        $files = scandir($dir);

        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                // Получаем полный путь к файлу
                $path = $dir . '/' . $file;

                // Проверяем, является ли файл PHP-классом
                if (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === 'php') {
                    // Получаем имя класса из файла
                    $class = pathinfo($path, PATHINFO_FILENAME);

                    // Добавляем имя класса в массив
                    $classes[] = [
                        'class' => $class,
                        'type' => 'provider',
                    ];
                }
            }
        }

        return $this->outputArray($classes);
    }
}

return 'CronTabManagerClassGetListProcessor';
