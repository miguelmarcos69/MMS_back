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

        $arrayDatos = [];
        $sentencia2 = $conexion->query("select * from platos where nombrePlato='" . $json->nombre . "'");

        while ($d2 = $sentencia2->fetch_array(MYSQLI_ASSOC)) {
            array_push($arrayDatos, $d2);
        }
        if (count($arrayDatos) === 0) {
            $sentencia = $conexion->query("INSERT INTO platos (nombrePlato,ingredientes,precio) 
            VALUES ('" . $json->nombre . "','" . substr($cadenaIngredientes, 0, -1) . "','" . floatval($json->precio) . "') ");

            $datos2 = [];
            $sentencia2 = $conexion->query("select * from platos where nombrePlato='" . $json->nombre . "'");

            while ($d2 = $sentencia2->fetch_array(MYSQLI_ASSOC)) {
                array_push($datos2, $d2);
            }

            $sentencia3 = $conexion->query("UPDATE platos  SET img ='/uploads/platos/" . $datos2[0]['id'] . ".jpg' where id='" . $datos2[0]['id'] . " '");
            $datos = (object) array(
                'valido' => $sentencia3,
                'id' =>  $datos2[0]['id']
            );
        } else {
            $datos = (object) array(
                'valido' => false,
                'id' =>  0,
                'msg' => 'Ese plato ya existe'

            );
        }
        echo json_encode($datos, JSON_FORCE_OBJECT);
    }

    if ($json->tipo === 'bebida') {

        $arrayDatos = [];
        $sentencia2 = $conexion->query("select * from bebidas where nombre='" . $json->nombre . "'");

        while ($d2 = $sentencia2->fetch_array(MYSQLI_ASSOC)) {
            array_push($arrayDatos, $d2);
        }
        if (count($arrayDatos) === 0) {

            $sentencia = $conexion->query("INSERT INTO bebidas (nombre,precio) 
            VALUES ('" . $json->nombre . "','" . floatval($json->precio) . "') ");

            $datos2 = [];
            $sentencia2 = $conexion->query("select * from bebidas where nombre='" . $json->nombre . "'");



            while ($d2 = $sentencia2->fetch_array(MYSQLI_ASSOC)) {
                $datos2 = $d2;
            }

            $sentencia3 = $conexion->query("UPDATE bebidas  SET img ='/uploads/bebidas/" . $datos2['id'] . ".jpg' where id='" . $datos2['id'] . " '");

            $datos = (object) array(
                'valido' => $sentencia3,
                'id' =>  $datos2['id']
            );
        } else {
            $datos = (object) array(
                'valido' => false,
                'id' =>  0,
                'msg' => 'Esa bebida ya existe'

            );
        }
        echo json_encode($datos, JSON_FORCE_OBJECT);

    }
};
