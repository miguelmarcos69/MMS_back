<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=UTF-8");

$conexion = new mysqli('localhost', 'root', '', 'app1');
if ($conexion->connect_errno) {
    print "Codigo de error" . $conexion->connect_errno . "<br/>" . "Error de " . $conexion->connect_error;
} else {
    $json = json_decode(file_get_contents("php://input"));
    $cadenaPlatos = "";
    $cadenaBebida = "";
    if ($json->bebida != '') {
        # code...
        for ($i = 0; $i < count($json->bebida); $i++) {
            $cadenaBebida .= $json->bebida[$i]->id . "|";
        }
    }
    if ($json->platos != '') {
        # code...

        for ($i = 0; $i < count($json->platos); $i++) {
            $cadenaPlatos .= $json->platos[$i]->id . "|";
        }
    }
    //'" . $json->nombre . "',

    if ($json->id !== NULL) {
        $sentencia = $conexion->query("INSERT INTO archivomesa (IDmesa,nombreMesa,platos,bebida,ocupantes,idUsu,numTel,accion,precioTotal,dia,usuPagado) 
    VALUES ('" . $json->id . "','" . $json->nombreMesa . "','" . substr($cadenaPlatos, 0, -1) . "','" . substr($cadenaBebida, 0, -1) . "','" . $json->ocupantes . "','" . $json->idusu . "','" . $json->numTel . "','" . $json->accion . "','" . floatval($json->precioTotal) . "','" . $json->dia . "','" . $json->usuPa . "') ");

        if ($sentencia) {
            if ($json->accion === 'domi') {
                $sentenicia2 = $conexion->query("DELETE  FROM mesas where id='" . $json->id . " '");
            } else {
                $sentenicia2 = $conexion->query("UPDATE mesas  SET platos ='', bebida='', numTel='', accion=null, precioTotal='', ocupantes=0  where id='" . $json->id . " '");
            }
        }
    }

    $datos = (object) array('valido' => true);


    echo json_encode($datos);
}
