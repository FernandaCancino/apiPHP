<?php

require_once 'vendor/autoload.php';
// require_once 'piramide-uploader/PiramideUploader.php';

// crea instancia del objeto slim
$app = new \Slim\Slim();

// conexion a bd desde php desde nuestro fichero de api rest
$db = new mysqli('localhost', 'root', '', 'curso_angular');
//credenciales 1.- Servidor, 2.- Usuario de bd, 3.-Contraseña, 4.- Nombre de la bd

/*PARA FINALIZAR EL SERVICIO REST, SE CONFIGURAN LAS CABECERAS HTTP, PARA PERMITIR EL ACCESO CORS, Y ASI REALIZAR PETICIONES AJAX SIN PROBLEMAS  */
//cabeceras

//permite el acceso a la api 
header('Access-Control-Allow-Origin: *');
//desde otras urls
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
//a traves de los distintos metodos http
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
    die();
}

$app->get("/pruebas", function() use ($app){
    echo "hola mundo desde slim php";
    // $db = new mysqli('localhost', 'root', '', 'curso_angular');
    // var_dump($db);
});

$app->get("/probando", function() use ($app){
    echo "otro texto cualquiera";
});

//FUNCION PARA LISTAR TODOS LOS PRODUCTOS
$app->get('/productos', function() use($db, $app){

    //consulta sql
    $sql = 'select * from productos order by id desc';
    $query = $db->query($sql);

    //metodo fetch_all saca todos los productos sin necesidad de crear un bucle while
    //devuelve un array de array, no array de objetos
    //var_dump($query->fetch_all());
    
    //metodo fetch_assoc solo trae devuelta el primer valor de la lista
    // var_dump($query->fetch_assoc());


    $productos = array();
    while($producto = $query->fetch_assoc()){
        $productos[] = $producto;
    }

    $result = array(
                    'status' => 'success'
                    ,'code' => '200'
                    ,'data' => $productos
                );

    echo json_encode($result);

});

//DEVOLVER UN SOLO PRODUCTO
$app->get('/producto/:id', function($id) use($db, $app){

    $sql = 'select * from productos where id = '. $id;
    $query = $db->query($sql);

    //array result por defectp
    $result = array(
         'status'   => 'error'
        ,'code'     => 404
        ,'message'  => 'Producto no disponible'
    );  

    if($query->num_rows == 1){
        $producto = $query->fetch_assoc();

        $result = array(
            'status'    => 'success'
           ,'code'      => 200
           ,'data'      => $producto 
       ); 
    }

    //devolver un json (result) en la pagina web
    echo json_encode($result);

});

//ELIMINAR UN PRODUCTO
$app->get('/delete-producto/:id', function ($id) use ($db, $app){

    $sql = 'delete from productos where id = '. $id; 
    $query = $db->query($sql);

    if($query){
        $result = array(
            'status'    => 'success'
           ,'code'      => 200
           ,'data'      => 'El producto se ha eliminado correctamente' 
       ); 
    }else{
        $result = array(
            'status'    => 'error'
           ,'code'      => 404
           ,'data'      => 'no se pudo eliminar producto' 
       ); 
    }

    echo json_encode($result);

});

//ACTUALIZAR UN PRODUCTO
$app->post('/update-producto/:id', function($id) use ($db, $app){
    $json = $app->request->post('json');
    $data = json_decode ($json, true);

    $sql =  "update productos set " . 
            "nombre = '{$data["nombre"]}' , ".
            "descripcion = '{$data["descripcion"]}' , ";
    
    if(isset($data['imagen'])){
        $sql .= "imagen = '{$data["imagen"]}' , ";
    }

    //$sql.= concatena los datos 
    $sql .= "precio = '{$data["precio"]}' where id = {$id} ";
    $query = $db->query($sql);

    var_dump($sql);

    if($query){
        $result = array(
            'status'    => 'success'
           ,'code'      => 200
           ,'data'      => 'El producto se ha actualizado correctamente' 
       ); 
    }else{
        $result = array(
            'status'    => 'error'
           ,'code'      => 404
           ,'data'      => 'no se pudo actualizar producto' 
       ); 
    }

    echo json_encode($result);

});

//SUBIR UNA IMAGEN A UN PRODUCTO
$app->post('/upload-file', function() use ($app, $db){

    $result = array(
        'status'    => 'error'
       ,'code'      => 404
       ,'data'      => 'el archivo no ha podido subirse' 
   ); 

   if(isset($_FILES['uploads'])){
        $piramideUploader = new PiramideUploader();

        $upload = $piramideUploader->upload('image', "uploads", "uploads", array('image/jpeg', 'image/png', 'image/gif'));
        $file = $piramideUploader->getInfoFile();
        $file_name = $file['complete_name'];

    if(isset($upload) && $upload["uploaded"] == false){
        $result = array(
            'status' 	=> 'error',
            'code'		=> 404,
            'message' 	=> 'El archivo no ha podido subirse'
        );
    }else{
        $result = array(
            'status' 	=> 'success',
            'code'		=> 200,
            'message' 	=> 'El archivo se ha subido',
            'filename'  => $file_name
        );
    }
}

   echo json_encode($result);

});



//FUNCION PARA GUARDAR PRODUCTOS EN LA BBDD
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

    //utilizamos VAR_DUMP para saber que contienen las variables... 
    // var_dump($json);
    // var_dump($data);


    //guardar registro en la bd
    $query = "INSERT INTO productos VALUES (
             NULL
            ,"."'{$data['nombre']}'
            ,"."'{$data['descripcion']}'
            ,"."'{$data['precio']}'
            ,"."'{$data['imagen']}'".
            ")";

    //var_dump($query);

    $insert = $db->query($query);

    $result = array (
        //comillas simples cosumen menos recursos en el servidor
         'status' => 'error'
        ,'code' => '404'
        ,'message' => 'Producto NO se ha creado correctamente'
    );
    //comprobamos... si se realizo correctamente el insert...
    if ($insert){
        $result = array (
                            //comillas simples cosumen menos recursos en el servidor
                             'status' => 'success'
                            ,'code' => '200'
                            ,'message' => 'Producto creado correctamente'
                        );
    }


    //pasamos el resultado a un json (encode), para poder trabajarlos desde js 
    echo json_encode($result);

});

//esto se hace al utilizar slim
$app->run();



