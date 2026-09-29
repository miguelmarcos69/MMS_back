<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');

require_once __DIR__ . '/config.php';
$conexion = conectar();
//print $conexion->server_info;


if ($conexion->connect_errno) {

    //print "Error al conectar con la BD";

    print "Codigo de error" . $conexion->connect_errno . "<br/>" . "Error de " . $conexion->connect_error;
} else {

    $consulta = $conexion->query("select * from bebidas");
    $datos = [];

    while ($d = $consulta->fetch_array(MYSQLI_ASSOC)) {
        array_push($datos, $d);
    }

    //echo json_encode($platos);
    echo json_encode($datos);
    
}
function existeParametros($id, $array)
{
    $res = "";
    for ($i = 0; $i < count($array); $i++) {
        if ($id == $array[$i]["id"]) {
            $res = $array[$i];
        }
    }
    return $res;
}
