<?php
// DIC configuration
$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// Facebook Controller
$container['FacebookController'] = function($c) {
    return new App\Controllers\FacebookController($c);
};
// cache connfig
$container['cache'] = function ($c) {
    $config = [
        'schema' => 'tcp',
        'host' => 'localhost',
        'port' => 6379,
        // other options
    ];
    //$connection = new Predis\Client($config);
    //return new Symfony\Component\Cache\Adapter\RedisAdapter($connection);
    return new Predis\Client($config);
};

$container['CacheService'] = function ($c) {
    return new App\Services\CacheService($c);
};
