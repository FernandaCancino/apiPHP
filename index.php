<?php

require_once 'vendor/autoload.php';

// crea instancia del objeto slim
$app = new \Slim\Slim();

// conexion a bd desde php desde nuestro fichero de api rest
$db = new mysqli('localhost', 'root', '', 'curso_angular');
//credenciales 1.- Servidor, 2.- Usuario de bd, 3.-Contraseña, 4.- Nombre de la bd

$app->get("/pruebas", function() use ($app){
    echo "hola mundo desde slim php";
    // $db = new mysqli('localhost', 'root', '', 'curso_angular');
    // var_dump($db);
});

$app->get("/probando", function() use ($app){
    echo "otro texto cualquiera";
});


$app->post('/productos', function() use ($app, $db){

    //recogeremos los parametros que nos llegan desde postman, 
    //DEsde postman vamos a enviar datos como si fuera un formulario y recogeremos los parametros que nos llegan a traves del metodo http POST

    //recogemos el valor del array (POST), que nos llega por request. La variable que nos llega por post se llama JSON
    $json = $app->request->post('json');

    //decodificaremos el valor de la variable json, con el true que le pasamos, estamos diciendo que nos convierta el objeto que trae a un array
    $data = json_decode($json, true);

    if(!isset($data['imagen'])){
        $data['imagen'] = null;
    }
    if(!isset($data['precio'])){
        $data['precio'] = null;
    }
    if(!isset($data['descripcion'])){
        $data['descripcion'] = null;
    }
    if(!isset($data['nombre'])){
        $data['nombre'] = null;
    }

    $query = "INSERT INTO productos VALUES (NULL, ".
            "'{$data['nombre']}',".
            "'{$data['descripcion']}',".
            "'{$data['precio']}',".
            "'{$data['imagen']}'".
            ")";

    var_dump($query);

    //utilizamos VAR_DUMP para saber que contienen las variables... 
    // var_dump($json);
    // var_dump($data);

});

//esto se hace al utilizar slim
$app->run();



