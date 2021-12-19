<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

const CONNECTION_DEFAULT = 'default';

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'db_3',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
],CONNECTION_DEFAULT);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

$capsule->getConnection(CONNECTION_DEFAULT)->enableQueryLog();

