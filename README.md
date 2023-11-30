<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## О проекте

В тестовом проекте используется laravel v.8.82 + vue3 inertia.
Для успешной установки и разворачивания проекта необходимо:

1. Node.js (https://nodejs.org)
2. Composer https://getcomposer.org/
3. Локальный веб-сервер Open-server, Xampp и тд.

# Установка проекта на локальном диске

1.  В папке где планируется разместить проект выполнить команду

    ```
    git clone https://github.com/iliaNeustorev/TestWork
    ```

2.  После выгрузки из Git перейти в корневую директорию появившейся папки с проектом и выполнить следущие действия:

    a) Установить зависимости Composer. В командной строке:

    ```
    composer install
    ```

    b) Установить зависимости Node. В командной строке:

    ```
    npm install
    ```

    c) Переименовать файл .env.example в .env в корневом каталоге проекта.

    d) В файле .env указать следущие настройки

    ```
    APP_NAME=TestWork

    DB_DATABASE=test_work
    DB_USERNAME=admin
    DB_PASSWORD=admin
    ```

    DB_USERNAME и DB_PASSWORD - это имя и пароль пользователя имеющего доступ к вашей локальной базе данных(БД) и могут быть другими.
    Пароль должен быть обязательно!

    e) Выполнить миграцию таблицы в БД и напонение их данными.
    В корневом каталоге проекта в командной строке последовательно выполнить команды:

        ```
        php artisan migrate
        ```
        ```
        php artisan downloadFakeDataInBase
        ```

        Eсли не получилось произвести миграцию с первого раза или наполнить БД данными, то необходимо выполнить команду указаную далее и повторить последовательность двух вышеперечисленных команд:

        ```
        php artisan migrate:rollback
        ```

    f) Сгенерировать уникальный ключ для Laravel. Он автоматически добавиться в .env файл

        ```
        php artisan key:generate
        ```

## Запуск проекта на локальном сервере

Перейти в папку с проектом и в командной строке выполнить команды

```
php artisan serve
```

Запуск node. Создать в проекте папку 'Pages' по пути resources\js\ и запустить команду

```
npm run watch или npm run dev
```
