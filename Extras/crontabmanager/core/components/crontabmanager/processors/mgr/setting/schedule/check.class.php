<?php

/**
 * Проверяет наличие задания для запуска в крон задании
 */
class CronTabManagerScheduleCheckProcessor extends modProcessor
{

    /**
     * @return array|string
     */
    public function process()
    {
        /* @var CronTabManager $CronTabManager */
        $CronTabManager = $this->modx->getService('crontabmanager', 'CronTabManager', MODX_CORE_PATH.'components/crontabmanager/model/');

        $this->modx->lexicon->load('crontabmanager:manager');

        $find = false;
        $isAvailable = cronTabManagerIsAvailable();
        if ($isAvailable) {
            $Schedule = $CronTabManager->addSchedule();
            $find = $Schedule->has();
        }
        $user = cronTabManagerCurrentUser();

        $demon_crontab = $isAvailable
            ? $this->modx->lexicon('crontabmanager_crontab_available', ['user' => $user])
            : $this->modx->lexicon('crontabmanager_crontab_not_available', ['user' => $user]);

        return $this->success('ok', [
            'class_crontab' => $isAvailable ? 'available' : 'not_available',
            'demon_crontab' => $demon_crontab,
            'available' => $isAvailable,
            'find' => $find,
            'status' => $find
                ? 'Задачи автоматически запускаются'
                : 'Необходимо добавить schedule:run в crontab',
        ]);
    }
}

return 'CronTabManagerScheduleCheckProcessor';
