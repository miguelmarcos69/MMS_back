<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=UTF-8");


include "clases.php";
require_once "vendor/autoload.php";

use Firebase\JWT\JWT;

$conexion = new mysqli('localhost', 'root', '', 'app1');
//print $conexion->server_info;


if ($conexion->connect_errno) {

    //print "Error al conectar con la BD";

    print "Codigo de error" . $conexion->connect_errno . "<br/>" . "Error de " . $conexion->connect_error;
} else {
    $json = json_decode(file_get_contents("php://input"));


    if ($json->username != "" || $json->password) {
        # code...

        $consulta = $conexion->query("select * from usuarios where nombre='" . $json->username . "' and contra='" . $json->password . "'");
        $datos = [];
        $da = [];

        while ($d = $consulta->fetch_array(MYSQLI_ASSOC)) {

            array_push($da, $d);
        }

        if (count($da) == 1) {
            //generar el key del usuario
            //$token = bin2hex(random_bytes((30 - (30 % 2)) / 2));
            $jwt = jwt($da[0]["id"], $da[0]["nombre"]);


            //$usuario = new usuario($datos[0]["usuario"],$datos[0]->contraseña);


            $datos["id"] = $da[0]["id"];
            $datos["usuario"] = $da[0]["nombre"];
            $datos["email"] = $da[0]["email"];
            $datos["rol"] = $da[0]["rol"];
            $datos["poblacion"] = $da[0]["poblacion"];
            $datos["obs"] = $da[0]["obs"];
            $datos["token"] = $jwt;
            $datos["valido"] = true;
        } else {
            $datos["msg"] = "El usuario o contraseña no son válidos";
            $datos["valido"] = false;
        }
    } else {
        $datos["msg"] = "No has introdicido bien todos lo párametros indicados";
        $datos["valido"] = false;
    }


    //echo json_encode($platos);
    echo json_encode($datos);
    header('Content-Type: application/json');
}

function jwt($id, $nombre)
{

    $time = time();

    $token = array(
        "iat" => $time, //momento en el que inicia el token
        "exp" => $time + (60 * 60 * 24), //tiempo de expiracion, 1 dia
        "data" => [
            'id' => $id,
            'nombre' => $nombre,
        ]
    );
    $jwt = JWT::encode($token, "miguel", 'HS256');
    return $jwt;
}
