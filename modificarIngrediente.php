<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=UTF-8");

$conexion = new mysqli('localhost', 'root', '', 'app1');
if ($conexion->connect_errno) {
    print "Codigo de error" . $conexion->connect_errno . "<br/>" . "Error de " . $conexion->connect_error;
} else {
    $json = json_decode(file_get_contents("php://input"));

    if ($json->accion == 'modificar') {
        $sentenicia = $conexion->query("UPDATE ingredientres  SET nombre ='$json->nombre' where id='" . $json->id . " '");
    }else{
        $sentenicia = $conexion->query("UPDATE ingredientres  SET nombre ='' where id='" . $json->id . " '");
        
    }
    # code...

    $datos = (object) array(
        'valido' => $sentenicia,
    );
    echo json_encode($datos, JSON_FORCE_OBJECT);
};
