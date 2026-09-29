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


    $sentenicia = consulta(
        $conexion,
        "UPDATE clientes SET nombre=?, apellidos=?, direccion=?, localidad=?, num=?, telefono=? where id=?",
        [$json->nombre, $json->apellidos, $json->direccion, $json->localidad, $json->num, $json->telefono, $json->id]
    );

    $datos = (object) array(
        'valido' => $sentenicia,
    );
    echo json_encode($datos, JSON_FORCE_OBJECT);
};
