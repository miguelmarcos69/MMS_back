<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');


$conexion = new mysqli('localhost', 'root', '', 'app1');
if ($conexion->connect_errno) {
    print "Codigo de error" . $conexion->connect_errno . "<br/>" . "Error de " . $conexion->connect_error;
} else {
    $json = json_decode(file_get_contents("php://input"));
    $cadenaIngredientes = "";


    if ($json->tipo === 'platos') {
        # code...

        for ($i = 0; $i < count($json->ingredientes); $i++) {
            $cadenaIngredientes .= $json->ingredientes[$i] . "|";
        }
        $sentenicia = $conexion->query("UPDATE platos  SET nombrePlato ='$json->nombre',ingredientes='" .  substr($cadenaIngredientes, 0, -1) . "', precio='" . $json->precio . "' where id='" . $json->id . " '");

        $datos = (object) array(
            'valido' => $sentenicia,
            'id' => $json->id
        );
        echo json_encode($datos, JSON_FORCE_OBJECT);
    }

    if ($json->tipo === 'bebida') {

        $sentenicia = $conexion->query("UPDATE bebidas  SET nombre ='$json->nombre', precio='" . $json->precio . "' where id='" . $json->id . " '");


        $datos = (object) array(
            'valido' => $sentenicia,
            'id' =>  $json->id
        );
        echo json_encode($datos, JSON_FORCE_OBJECT);
    }
};
