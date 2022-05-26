# What is this?
This is a simple blogging app.

## Technology used
- PHP 8.1
- Symphony 6
- MySQL 8

## How to use this?
- in the project root, change your MySQL's setting in `.env.local` then run below to code.

'''
$ php bin/console doctrine:database:create
$ php bin/console make:migration
$ php bin/console doctrine:migrations:migrate
'''

- run build.

'''
$ symfony run yarn encore dev
'''

- you can make datas by executing the following code.
and you also can login with the output username and password('admin').

'''
$ php bin/console doctrine:fixtures:load
'''

- run server.

'''
$ symfony server:start
'''

- open your browser and navigate to `http://localhost:8000/`.
