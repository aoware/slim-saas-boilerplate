# slim-saas-boilerplate
A PHP-based user-authenticated Saas boilerplate by [aoWare](https://www.aoware.co.uk/), using:
* [Slim 4](https://www.slimframework.com/docs/) Framework
* [Twig 3](https://twig.symfony.com) Template Engine
* [Slim-Psr7](https://github.com/slimphp/Slim-Psr7) as PSR-7 implementation
* [PHP-DI](http://php-di.org) as dependency injection container
* [Play](https://github.com/uideck/play-bootstrap) bootstrap template
* [Tabler](https://github.com/tabler/tabler) bootstrap template
* [https://github.com/konsav/email-templates](https://github.com/konsav/email-templates) email template
* [nextras/dbal](https://packagist.org/packages/nextras/dbal) as a light database abstraction layer
* TODO: [JWT Authentication](https://github.com/tuupola/slim-jwt-auth) / [PHP-JWT](https://github.com/firebase/php-jwt)

## System Requirements

* Web server with URL rewriting
* Composer
* PHP 8.1 or newer.
* MySQL/MariaDB.
* Composer

## Installation
* `git clone https://github.com/aoware/slim-saas-boilerplate.git` clone git repo
* `cd slim-saas-boilerplate` change working directory to root project folder
* `composer install` install dependencies
* `cp config.dist config.php` creates a new config file for you
* Edit config.php with MySQL configurations and more
* Execute `db_source/migrations/initial_db.sql` run initial database migration

## Run
* set virtual host to project *public* folder

## PHP Coding Standards
* Only use [snake case](https://en.wikipedia.org/wiki/Snake_case) for variables, classes, namespace (everything really)
* Configuration parameters should be stored in `/config.dist` and `/config.php`. Those should have a prefix of `CONF_` to clearly identify them in the source code
* Do not use abbreviations in variables or classes definition. `$ac_in` could be read as `$account_inported` or `$actual_invoice`.
* Do not use include or require. All classes should be autoloaded or in a container
* Do not use `use`. All objects should be defined with their full qualified namespace path.
* DB queries must use prepare statements. 2 methods:
    * use generated models that provided methods for each indexes existing on a table.
    * use DBAL nextras/dbal Database Abstraction Layer
* DB object create statements to be stored in folder `/db_source` in the appropriate object type
* All controllers should return a valid [PSR 7](https://www.php-fig.org/psr/psr-7) response (html, json, download/stream or redirection). `echo $something;die();` is not permitted.
* $_SESSION variable should only use a single entry `login_token`, which sole purpose is to validate the user logged in against the `login_token` column in the `users` table

## MYSQL naming Standards
* Only use [snake case](https://en.wikipedia.org/wiki/Snake_case) for tables, columns, functions and procedures names
* Do not use abbreviations in variables or classes definition.
* Column names should not repeat the name of the table. For example `client_rep_name` should be `name` in table `client_rep`.
* First column of all tables must be set as `id int(11) NOT NULL AUTO_INCREMENT`
* All tables to have a comment
* Columns with ambiguous name should have a comment
* Column that reference a foreign key should be set as "table_name_in_singular"_id int(11) NOT NULL
* Index should be named as 'IDX_' + table name + column(s) name, where table name and columns name are in snake_case