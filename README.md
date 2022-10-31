https://project-awesome.org/nekofar/awesome-slim

https://www.slimframework.com/docs/v4/concepts/life-cycle.html

# slim-saas-boilerplate
A PHP-based user-authenticated Saas boilerplate by [aoWare](https://www.aoware.co.uk/), using:
* [Slim 4](https://www.slimframework.com/docs/) Framework
* [Twig 3](https://twig.symfony.com) Template Engine
* [Slim-Psr7](https://github.com/slimphp/Slim-Psr7) as PSR-7 implementation
* [PHP-DI](http://php-di.org) as dependency injection container
* [JWT Authentication](https://github.com/tuupola/slim-jwt-auth) / [PHP-JWT](https://github.com/firebase/php-jwt)
* [Play](https://github.com/uideck/play-bootstrap) bootstrap template
* [Tabler](https://github.com/tabler/tabler) bootstrap template

## System Requirements

* Web server with URL rewriting 
* Composer
* PHP 8.1 or newer.
* MySQL/MariaDB.

## Installation
* `git clone https://github.com/aoware/slim-saas-boilerplate.git` clone git repo
* `cd slim-saas-boilerplate` change working directory to root project folder
* `composer install` install dependencies
* `cp config.dist config.php` creates a new config file for you
* Edit config.php with MySQL configurations and more
* Execute `db_source/migrations/1.sql` run initial database migration

## Run
* set virtual host to *public* folder

## PHP Coding Standards
* Only use [snake case](https://en.wikipedia.org/wiki/Snake_case) for variables, classes, namespace (everything really)
* Configuration parameters should be stored in `/config.dist` and `/config.php`. Those should have a prefix of `CONF_` to clearly identify them in the source code
* Do not use abbreviations in variables or classes definition. `$ac_in` could be read as `$account_inported` or `$actual_invoice`.
* Do not use include or require. All classes should be autoloaded or in a container
* Do not use `use`. All objects should be defined with their full qualified namespace path.
* DB queries must use prepare statements. 2 methods:
    * use generated models that provided methods for each indexes existing on a table.
    * use DBAL nextras/dbal. TODO: To be implemented, initiated through a container, available as `$this->dbal` in every controller
* DB object create statements to be stored in folder `/db_source` in the appropriate object type  
* All controllers should return a valid [PSR 7](https://www.php-fig.org/psr/psr-7) response (html, json, download/stream or redirection). `echo $something;die();` is not permitted.   

## MYSQL naming Standards
* Only use [snake case](https://en.wikipedia.org/wiki/Snake_case) for tables, columns, functions and procedures names
* Do not use abbreviations in variables or classes definition.
* Column names should not repeat the name of the table. For example `client_rep_name` should be `name` in table `client_rep`.
* First column of all tables ,ust be set as `id int(11) NOT NULL AUTO_INCREMENT`
* All tables to have a comment
* Columns with ambiguous name should have a comment
* Column that reference a foreign key should be set as "table_name_in_singular"_id int(11) NOT NULL
* Index should be named as 'IDX_' + table name + column(s) name, where table name and columns name are in snake_case 

## Tests
Execute unit tests via PHPUnit by running `./vendor/bin/phpunit ./tests/`.  You can debug tests via XDebug by running `./phpunit-debug ./tests/` (use Git Bash if on Windows).
This boilerplate's test suite features 100% code coverage out-of-the-box (see report in *./test/coverage/*).  To regenerate code coverage HTML report, run `./vendor/bin/phpunit --coverage-html ./tests/coverage/ --whitelist ./app/ ./tests/`

## API Documentation
### HTTP Codes
* `200` API request successful
* `400` API request returned an error
* `401` Unauthorized (access token missing/invalid/expired)
* `404` API endpoint not found
### Authentication
Endpoint | Parameters | Description
--- | --- | ---
`POST /users` | `username` *string* required<br>`password` *string* required | creates a user
`POST /users/login` | `username` *string* required<br>`password` *string* required | generates user access token
### Endpoints
All RESTful API endpoints below require a `Authorization: Bearer xxxx` header set on the HTTP request, *xxxx* is replaced with token generated from the Authentication API above.
#### Categories
Endpoint | Parameters | Description
--- | --- | ---
`GET /categories` | *n/a* | lists all categories
`GET /categories/{id}` | *n/a* | gets category data by ID
`GET /categories/{id}/todo` | *n/a* | lists all todo items for a category
`POST /categories/` | `name` *string* required<br>`category` *integer* required | creates a category
`PUT /categories/{id}` | `name` *string* optional<br>`category` *integer* optional | updates a category
`DELETE /categories/{id}` | *n/a* | delete category and associated todo items
#### Todo Items
Endpoint | Parameters | Description
--- | --- | ---
`GET /todo` | *n/a* | lists all todo items
`GET /todo/{id}` | *n/a* | gets todo item data by ID
`POST /todo` | `name` *string* required<br>`category` *integer* required | creates a todo item
`PUT /todo/{id}` | `name` *string* optional<br>`category` *integer* optional | updates a todo item
`DELETE /todo/{id}` | *n/a* | deletes a todo item