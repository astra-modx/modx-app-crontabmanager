# Исполняемый файл

По умолчанию запуск команд доступен из под консоли через команду

```shell
php core/scheduler/artisan list
```

Чтобы было удобней запускать из корневой директории сразу после подключения ssh:
```shell
php artisan list
```

Создаем файл artisan (или копируем `cp core/scheduler/artisan.example artisan`)

```shell
cat > artisan << 'EOF'
#!/usr/bin/env php
<?php
define('MODX_CRONTAB_MODE', true);
require_once __DIR__.'/core/scheduler/index.php'; # Проверьте путь к директории core
$Artisan = new \Webnitros\CronTabManager\Artisan\Builder($scheduler, $argv);
$Artisan->run();
EOF
```


# Команды

#### Список всех команд

```shell
php artisan list
# Available commands:
#   completion               Dump the shell completion script
#   demo                     Демонстрация контроллера
#   help                     Display help for a command
#   list                     List commands
#  command
#   command:create           Creates a new controller.
#  schedule
#   schedule:list            List scheduled tasks
#   schedule:run             Run current tasks
#   schedule:work            Run scheduled tasks
#  support
#   support:clearlogmanager  Чистит логи менеджеров старше 2-х месяцев

```

#### Создание команды

Создает пример контроллера с передачей аргумента arg_name

```shell
# --name имя новой команды для запуска
php artisan command:create --name=MySuperTask

# Create a controller with the name: CrontabControllerMySuperTask [command: php artisan mysupertask --arg_name=water]
#Path controller: /var/www/html/core/scheduler/Controllers/MySuperTask.php

```

#### Запуск команды

Из предыдущего примера

```shell
php artisan mysupertask --arg_name=water

# [INFO] Hello: water <----- Наш аргумент

```

### Содержимое контроллера команды

```php
<?php
/**
 * New Command "php artisan mysupertask --arg_name=water"
 */
class CrontabControllerMySuperTask extends modCrontabController
{
    protected $signature = 'mysupertask {--arg_name}'; // no required arguments
    public function process()
    {
        $name = $this->input()->getArgument('arg_name') ?? 'no name';
        $this->info('Hello: '.$name);
    }
}
```

Можно изменить signature контроллера на **my-super-task** команда будет доступна под этим именем

```shell
php artisan my-super-task --arg_name=water

# [INFO] Hello: water  
```

# Crontab

Кроны запускаются на основе созданных команд, все запускаемые крон задания храняться в базе данных

#### Добавить команду в задачи крон таб

```shell
php artisan crontab:add --command=demo
```

#### Список крон заданий

```shell
php artisan schedule:list

# ------ -------- ------------- --------------------- ----------------- --------------------------- 
#  Path   Active   Crontab       Next run              Diff              Comment                    
# ------ -------- ------------- --------------------- ----------------- --------------------------- 
#  demo   Yes      */1 * * * *   2024-11-30 05:48:00   через 6 секунды   Тестовое задание для д...  
# ------ -------- ------------- --------------------- ----------------- --------------------------- 
```

#### Запустить текущие крон задания

Будут исполнены задания которые совпадают с текущем временем

```shell

php artisan schedule:run
# // Тестовое задание для демонстрации работы контро...                                                                  
# 
# [INFO] [*/1 * * * *] demo.php run 
```


