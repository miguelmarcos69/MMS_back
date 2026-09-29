<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');

$conexion = new mysqli('localhost', 'root', '', 'app1');
//print $conexion->server_info;


if ($conexion->connect_errno) {

    //print "Error al conectar con la BD";

    print "Codigo de error" . $conexion->connect_errno . "<br/>" . "Error de " . $conexion->connect_error;
} else {

    $consulta = $conexion->query("select * from platos");
    $datos = [];

    while ($d = $consulta->fetch_array(MYSQLI_ASSOC)) {
        array_push($datos, $d);
    }

    $consulta2 = $conexion->query("select * from ingredientres");
    $datos2 = [];

    while ($d2 = $consulta2->fetch_array(MYSQLI_ASSOC)) {
        array_push($datos2, $d2);
    }

    for ($i = 0; $i < count($datos); $i++) {

        $datos[$i]["ingredientes"] = explode("|", $datos[$i]["ingredientes"]);
        for ($a = 0; $a < count($datos[$i]["ingredientes"]); $a++) {;

            $datos[$i]["ingredientes"][$a] = existeParametros($datos[$i]["ingredientes"][$a], $datos2);
        }
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
