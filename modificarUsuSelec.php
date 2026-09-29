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
    foreach ($json as $indice => $valor) {
        $sentenicia = consulta($conexion, "UPDATE mesas SET idusu=? where id=?", [$valor, $indice]);
    }

    $datos = (object) array(
        'valido' => $sentenicia,
    );
    echo json_encode($datos, JSON_FORCE_OBJECT);
};
