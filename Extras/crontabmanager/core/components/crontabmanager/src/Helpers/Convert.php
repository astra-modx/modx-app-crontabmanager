<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 29.11.2024
 * Time: 21:30
 */

namespace Webnitros\CronTabManager\Helpers;


class Convert
{

    public function command(string $path)
    {
        $path = rtrim($path, '.php');
        $command = str_ireplace('/', ':', $path);
        $pattern = "/\r?\n/";
        $replacement = "";
        $command = preg_replace($pattern, $replacement, $command);

        return mb_strtolower($command);
    }
}
