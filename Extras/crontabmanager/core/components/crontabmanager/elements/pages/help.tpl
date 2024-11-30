<div id="crontabmanager-panel-home-div-help" style="display: none">
    <div id="modx-page-help-content-help" class=" container">
        <h2>Настройки для запуска crontab на сервере</h2>
        <div id="contactus" style="width: 100%">
            Ваш консольный пользователя: <b>[[+user]]</b>

            <div class="crontabmanager-method-crontab">
                <h2>Crontab</h2>
                [[+demon_crontab]]
                <p>Используя этот метод, можно включать и выключать задания через панель администрирования. Задания автоматически будут запускаться на вашем
                    сервере
                </p>

                <small>Пример файла crontab -e при доступности crontab в linux для вашего пользователя</small><br>
                <div class="crontabmanager_help_command">
                    <pre class="crontabmanager_help_command_pre">
# modX component CronTabManager
*/1	*	*	*	*	[[+bin]] [[+path_scheduler]]/ControllersLinks/demo.php > [[+path_scheduler]]/logs/task_id_1_demo.log 2>&1 # 7nppzd
</pre>
                </div>
            </div>

            <div class="crontabmanager-method-crontab">
                <h2>Schedule console</h2>
                <p>Добавление задания в крон для запуска через schedule console</p>
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
                    <br>
                    <small>Откроется редактор <a target="_blank" href="https://www.digitalocean
                .com/community/tutorials/how-to-use-cron-to-automate-tasks-ubuntu-1804">nano</a>. При первом запуске может спросить какой редактор
                        по умолчанию</small><br>
                    <small>Внимание!! Не выполняйте команду crontab -e под ROOT пользователем, иначе после исполнения команды у сайта пропадут доступы к
                        созданным
                        файлам.</small>
                </div>


                <p>Добавьте строку в конец файла</p>
                <div class="crontabmanager_help_command">
                    <pre class="crontabmanager_help_command_pre">[[+schedule_cron]]</pre>
                </div>
                <p>Сохраните изменения и выполните выход из файла: <b>CTRL+x && Yes Enter</b></p>
                <p>Пример</p>
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

            </div>

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


            <div class="crontabmanager-method-crontab">
                <h2>Crontab File</h2>
                <p>Вместо добавления в crontab заданий, они добавляются в один файл с кронами, который можно использователь для подключения в ручную</p>

                <small>Пример файла</small><br>
                <div class="crontabmanager_help_command">
                    <pre class="crontabmanager_help_command_pre">
# modX component CronTabManager
*/1	*	*	*	*	[[+bin]] [[+path_scheduler]]/ControllersLinks/demo.php > [[+path_scheduler]]/logs/task_id_1_demo.log 2>&1 # 7nppzd
</pre>
                </div>

            </div>

        </div>
    </div>

</div>
