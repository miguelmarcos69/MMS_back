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
    if ($json->contra1 != '' && $json->contra2 != '') {
        $sentenicia = consulta(
            $conexion,
            "UPDATE usuarios SET nombre=?, contra=?, fechaNac=?, poblacion=?, img=?, email=?, rol=? where id=?",
            [$json->nombre, $json->contra2, $json->fechaNac, $json->poblacion, $json->img, $json->email, $json->rol, $json->id]
        );
    } else {

        $sentenicia = consulta(
            $conexion,
            "UPDATE usuarios SET nombre=?, fechaNac=?, poblacion=?, img=?, email=?, rol=? where id=?",
            [$json->nombre, $json->fechaNac, $json->poblacion, $json->img, $json->email, $json->rol, $json->id]
        );
    }
    $datos = (object) array(
        'valido' => $sentenicia,
    );
    echo json_encode($datos, JSON_FORCE_OBJECT);
};
