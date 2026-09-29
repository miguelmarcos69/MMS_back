<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');


require_once __DIR__ . '/config.php';
$conexion = conectar();
if ($conexion->connect_errno) {
    print "Codigo de error" . $conexion->connect_errno . "<br/>" . "Error de " . $conexion->connect_error;
} else {
    $json = json_decode(file_get_contents("php://input"));

    $datos = consulta($conexion, "select * from usuarios where nombre=? AND apellidos=?", [$json->nombre, $json->apellido]);


    if (!$datos) {
        if ($json->nombre !== NULL) {
            # code...
            $sentencia = consulta(
                $conexion,
                "INSERT INTO usuarios(nombre,apellidos,contra,fechaNac,poblacion,img,email,rol) VALUES(?,?,?,?,?,'/uploads/usuarios/default.png',?,?)",
                [$json->nombre, $json->apellido, $json->contra2, $json->fechaNac, $json->poblacion, $json->email, $json->rol]
            );
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
