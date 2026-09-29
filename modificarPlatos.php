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
    $cadenaIngredientes = "";


    if ($json->tipo === 'platos') {
        # code...

        for ($i = 0; $i < count($json->ingredientes); $i++) {
            $cadenaIngredientes .= $json->ingredientes[$i] . "|";
        }
        $sentenicia = consulta(
            $conexion,
            "UPDATE platos SET nombrePlato=?, ingredientes=?, precio=? where id=?",
            [$json->nombre, substr($cadenaIngredientes, 0, -1), $json->precio, $json->id]
        );

        $datos = (object) array(
            'valido' => $sentenicia,
            'id' => $json->id
        );
        echo json_encode($datos, JSON_FORCE_OBJECT);
    }

    if ($json->tipo === 'bebida') {

        $sentenicia = consulta($conexion, "UPDATE bebidas SET nombre=?, precio=? where id=?", [$json->nombre, $json->precio, $json->id]);


        $datos = (object) array(
            'valido' => $sentenicia,
            'id' =>  $json->id
        );
        echo json_encode($datos, JSON_FORCE_OBJECT);
    }
};
