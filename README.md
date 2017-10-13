# Slim API

A simple API made with PHP Slim Framework, which retrieves the profile of one facebook user, using the Facebook API Graph.

## Used technologies

PHP
Slim Framework
Redis

## Install

- Download / clone this repository

```shell
git clone https://github.com/sergioggonzalez/slimApi.git
```

- Install dependencies with composer

```shell
composer install
```

- Start development server:
```shell
php -S localhost:8080 -t public public/index.php
```
## Optional

- You can use Redis for caching the Facebook API Response. Set as true the USE_REDIS const in  [app/controllers/facebookController.php](https://github.com/sergioggonzalez/slimApi/blob/master/app/controllers/facebookController.php#L10)

## Example url

Ej: http://localhost:8080/profile/facebook/1339894004

## Unit Testing

```shell
 php  vendor/phpunit/phpunit/phpunit
```

```shell
 vendor/bin/phpunit
```
