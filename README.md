# symfony-base-backoffice
Symfony Base Backoffice

# Installation
Configure the .env file with your database details.
```shell
    $ composer install
    $ yarn install
    $ yarn build
```

# Development
For development run the built-in php server
```shell
    $ php bin/console server:run
```
You must also run yarn to compile the assets
```shell
    $ encore dev --watch 
```
or 
```shell
    $ yarn encore dev --watch 
```
