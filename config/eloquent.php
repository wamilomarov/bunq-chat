<?php
use Slim\App;

return static function (App $app) {

    $container = $app->getContainer();
    $dbSettings = $container->get('settings')['db'];

    // boot eloquent
    $capsule = new Illuminate\Database\Capsule\Manager();
    $capsule->addConnection($dbSettings);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
};
