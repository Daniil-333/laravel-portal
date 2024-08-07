# Инструкция для запуска
### В командной строке в корне проекта запустить поочерёдно команды 1 и 2

1. composer install
2. npm i
3. Скопировать файл .env.example и переименовать копию в .env <br>
    3.1. Изменить ключи в .env файле: <br>
    <pre>
        APP_ENV=production
        APP_DEBUG=false
        FILESYSTEM_DISK=public
   
   //параметры подключения к своей Базе Данных (могут отличаться)
        DB_CONNECTION=mysql
        DB_HOST=localhost
        DB_PORT=3306
        DB_DATABASE=laravel_portal
        DB_USERNAME=root
        DB_PASSWORD=
   
   //чтобы работал функционал восстановления пароля, необходимо указать свои параметры почтового сервера
        MAIL_MAILER=smtp
        MAIL_HOST=smtp.googlemail.com
        MAIL_PORT=465
        MAIL_USERNAME=
        MAIL_PASSWORD=
        MAIL_ENCRYPTION=ssl
        MAIL_FROM_ADDRESS=""
   </pre>
### В командной строке в корне проекта запустить поочерёдно команды 4-9

4. php artisan key:generate
5. php artisan config:cache
6. php artisan storage:link
7. php artisan migrate:fresh
8. php artisan db:seed
9. npm run build

### <p>Логин: test@test.ru.</p> Пароль: 12345678

### <p>P.S. Регистрация доступна только для пользователей из белого списка.</p> Внести их можно в разделе Access_List
