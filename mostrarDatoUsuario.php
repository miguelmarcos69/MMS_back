<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


$conexion = new mysqli('localhost', 'root', '', 'app1');
//print $conexion->server_info;


if ($conexion->connect_errno) {

    //print "Error al conectar con la BD";

    print "Codigo de error" . $conexion->connect_errno . "<br/>" . "Error de " . $conexion->connect_error;
} else {
    $json = json_decode(file_get_contents("php://input"));

    $consulta = $conexion->query("select * from usuarios where id=" . $json->id);
    $datos = [];

    while ($d = $consulta->fetch_array(MYSQLI_ASSOC)) {
        array_push($datos, $d);
    }


    //echo json_encode($platos);
    echo json_encode($datos);
}
