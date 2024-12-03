<div id="crontabmanager-panel-home-div-help" style="display: none">
    <div id="modx-page-help-content-help" class=" container">
        <h2>Настройки для запуска crontab на сервере</h2>
        <div id="contactus" style="width: 100%">

            <div class="crontabmanager-wrapper-help">
                <div class="crontabmanager-row-help">
                    <div class="crontabmanager-method-crontab">
                        <h2>Schedule console</h2>
                        <p>Добавление крон задания в linux crontab</p>
                        <p>Войдите на сервер по ssh</p>
                        <div class="crontabmanager_help_command_wrapper">
                            <div class="crontabmanager_help_command">
                                <pre class="crontabmanager_help_command_pre">ssh [[+user]]@127.0.0.1</pre>
                            </div>
                            <br>
                            <small>user и ip адрес 127.0.0.1 заменить на имя пользователя под которым работает сайт и IP адрес для подключения</small>
                        </div>
                        <p>Выполните команду от вашего пользователя:</p>
                        <div class="crontabmanager_help_command_wrapper">
                            <div class="crontabmanager_help_command">
                                <pre class="crontabmanager_help_command_pre">crontab -e</pre>
                            </div>

                            <span style="    padding: 0px 20px;
    display: inline-block;
    position: relative;
    top: -14px;">под ROOT пользователем </span>
                            <div class="crontabmanager_help_command">
                                <pre class="crontabmanager_help_command_pre">crontab -u [[+user]] -e</pre>
                            </div>

                            <br>
                            <small>Внимание!! Не выполняйте команду crontab -e под ROOT пользователем без явного указания USER, иначе после исполнения команды у
                                сайта
                                пропадут доступы к
                                созданным
                                файлам.</small>
                            <br>

                        </div>

                        <p>Откроется редактор <a style="color: #1775ef" target="_blank" href="https://www.digitalocean
                .com/community/tutorials/how-to-use-cron-to-automate-tasks-ubuntu-1804">nano</a>. При первом запуске может спросить какой редактор
                            по умолчанию</p>
                        <p>Добавьте строку в конец файла</p>
                        <div class="crontabmanager_help_command">
                            <pre class="crontabmanager_help_command_pre">[[+schedule_cron]]</pre>
                        </div>
                        <p>Сохраните изменения и выполните выход из файла: <b>CTRL+x && Yes Enter</b></p>
                        <p>Пример как будет выглядить crontab</p>
                        <div class="crontabmanager_help_command">
                    <pre class="crontabmanager_help_command_pre">
# modX component CronTabManager
[[+schedule_cron]]</pre>
                        </div>
                        <p>Сrontab запускается каждую минуту и выполняет команду от имени вашего пользователя <b>[[+user]]</b></p>


                        <div class="crontabmanager_help_command_help">
                            <h4>Дополнительная информация</h4>
                            <div class="crontabmanager_help_command_wrapper">
                                <small>Узнать под каким пользователем подключились (просте введите "id"):</small><br>
                                <div class="crontabmanager_help_command">
                        <pre class="crontabmanager_help_command_pre">
id
# ---> uid=82([[+user]]) gid=82([[+user]]) groups=82([[+user]])</pre>
                                </div>
                            </div>
                            <div class="crontabmanager_help_command_wrapper">
                                <small>Для переключения на пользователя из под root можно выполнить команду</small><br>
                                <div class="crontabmanager_help_command">
                                    <pre class="crontabmanager_help_command_pre">su - [[+user]]</pre>
                                </div>
                            </div>
                        </div>


                        <p>
                            Используя этот метод, можно включать и выключать задания через панель администрирования. Задания автоматически будут запускаться на
                            вашем
                            сервере.
                            <br>
                            <a href="https://raw.githubusercontent.com/astra-modx/modx-app-crontabmanager/refs/heads/master/docs/images/task_enable.png"
                               target="_blank">
                                <img
                                        width="300px"
                                        src="https://raw.githubusercontent.com/astra-modx/modx-app-crontabmanager/refs/heads/master/docs/images/task_enable.png"
                                        alt="Включение-выключение крон заданий"></a>
                        </p>

                    </div>
                    <hr>
                    <div class="crontabmanager-method-crontab">
                        <h2>Schedule Work console</h2>
                        <p>Для подключения через supervesor (php artisan schedule:work)</p>
                        <div class="crontabmanager_help_command">
                    <pre class="crontabmanager_help_command_pre">
[program:crontab]
command=php [[+path_artisan]] schedule:work
user=www-data
autostart=true
autorestart=true
redirect_stderr=true
stdout_logfile=/dev/stdout
</pre>
                        </div>
                        <p>Задание делает паузу в одну минуту, после окончания запуска всех команд в текущее время</p>
                    </div>

                </div>
                <div class="crontabmanager-row-help crontabmanager-row-help-check">
                    <h2>Проверка доступности</h2>
                    <br>
                    [[+demon_crontab_available:is=`1`:then=`
                    <span class="crontabmanager_crontab available">[[+demon_crontab]]</span>
                    <div style="margin-top: 10px"><em>Крон задания автоматически запускают</em></div>
                    `:else=`
                    <span class="crontabmanager_crontab not_available">[[+demon_crontab]]</span>
                    <div style="margin-top: 10px"><em>Используйте инструкцию "Schedule console" для автоматического запуска команды "php artisan
                            schedule:run"</em></div>
                    `]]

                    <hr>
                    Ваш консольный пользователя: <b>[[+user]]</b>
                    <hr>


                    [[+demon_crontab_available:is=`1`:then=`
                    <div>
                        <span class="x-btn x-btn-small x-btn-icon-small-left primary-button x-btn-noicon" onclick="addScheduleCronTab()">
                            <button type="button" class=" x-btn-text">
                                    <i class=" icon icon-play"></i> Добавить "schedule:run" в сrontab
                            </button>
                        </span>
                    </div>
                    `:else=`
                    `]]

                </div>
            </div>

        </div>
    </div>

</div>
