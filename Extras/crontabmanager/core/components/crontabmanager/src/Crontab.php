<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 29.11.2024
 * Time: 17:35
 */

namespace Webnitros\CronTabManager;

class Crontab
{
    /**
     * Проверка доступности crontab
     * @return bool
     */
    public static function isAvailable()
    {
        // Проверяем, доступна ли функция exec
        if (function_exists('exec') && is_callable('exec')) {
            // Проверяем, не отключена ли функция в php.ini
            $disabled_functions = explode(',', ini_get('disable_functions'));
            if (!in_array('exec', $disabled_functions)) {
                exec('crontab -l', $output, $returnVar);

                return $returnVar === 0;
            }
        }

        return false;
    }
}
