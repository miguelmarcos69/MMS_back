<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');


$conexion = new mysqli('localhost', 'root', '', 'app1');
if ($conexion->connect_errno) {
    print "Codigo de error" . $conexion->connect_errno . "<br/>" . "Error de " . $conexion->connect_error;
} else {
    $json = json_decode(file_get_contents("php://input"));

    $consulta = $conexion->query("select * from usuarios where nombre='" . $json->nombre . "' AND apellidos='" . $json->apellido . "'");
    $datos = [];

    while ($d = $consulta->fetch_array(MYSQLI_ASSOC)) {
        array_push($datos, $d);
    }


    if (!$datos) {
        if ($json->nombre !== NULL) {
            # code...
            $sentencia = $conexion->query("INSERT INTO usuarios(nombre,apellidos,contra,fechaNac,poblacion,img,email,rol) VALUES('" . $json->nombre . "','" . $json->apellido . "','" . $json->contra2 . "','" . $json->fechaNac . "','" . $json->poblacion . "','/uploads/usuarios/default.png','" . $json->email . "','" . $json->rol . "')");
            $msg = 'Usuario creado';
        }
    } else {
        $sentencia = false;
        $msg = 'Ya existe un usuario con ese nombre';
    }
    # code...
    $datos = (object) array('valido' => $sentencia, 'msg' => $msg,);
    


    echo json_encode($datos, JSON_FORCE_OBJECT);
};
