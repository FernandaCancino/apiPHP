<?php

require_once 'vendor/autoload.php';

// crea instancia del objeto slim
$app = new \Slim\Slim();


$app->get("/pruebas", function() use ($app){
    echo "hola mundo desde slim php";
});

$app->get("/probando", function() use ($app){
    echo "otro texto cualquiera";
});

//esto se hace al utilizar slim
$app->run();

