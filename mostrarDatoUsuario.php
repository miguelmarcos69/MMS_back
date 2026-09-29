<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


require_once __DIR__ . '/config.php';
$conexion = conectar();
//print $conexion->server_info;


if ($conexion->connect_errno) {

    //print "Error al conectar con la BD";

    print "Codigo de error" . $conexion->connect_errno . "<br/>" . "Error de " . $conexion->connect_error;
} else {
    $json = json_decode(file_get_contents("php://input"));

    // Todas las columnas menos la contraseña
    $datos = consulta($conexion, "select id, nombre, apellidos, fechaNac, poblacion, img, obs, email, rol from usuarios where id=?", [$json->id]);


    //echo json_encode($platos);
    echo json_encode($datos);
}
