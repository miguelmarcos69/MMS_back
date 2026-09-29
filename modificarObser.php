<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/config.php';
$conexion = conectar();
if ($conexion->connect_errno) {
    print "Codigo de error" . $conexion->connect_errno . "<br/>" . "Error de " . $conexion->connect_error;
} else {
    $json = json_decode(file_get_contents("php://input"));
    foreach ($json as $key => $usu) {
       $sentenicia = consulta($conexion, "UPDATE usuarios SET obs=? where id=?", [$usu->obs, $usu->id]);
    }

    $datos = (object) array(
        'valido' => $sentenicia,
    );
    echo json_encode($datos, JSON_FORCE_OBJECT);
};
