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

        $arrayDatos = consulta($conexion, "select * from platos where nombrePlato=?", [$json->nombre]);

        if (count($arrayDatos) === 0) {
            $sentencia = consulta(
                $conexion,
                "INSERT INTO platos (nombrePlato,ingredientes,precio) VALUES (?,?,?)",
                [$json->nombre, substr($cadenaIngredientes, 0, -1), floatval($json->precio)]
            );

            $datos2 = consulta($conexion, "select * from platos where nombrePlato=?", [$json->nombre]);

            $sentencia3 = consulta($conexion, "UPDATE platos SET img=? where id=?", ['/uploads/platos/' . $datos2[0]['id'] . '.jpg', $datos2[0]['id']]);
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

        $arrayDatos = consulta($conexion, "select * from bebidas where nombre=?", [$json->nombre]);

        if (count($arrayDatos) === 0) {

            $sentencia = consulta($conexion, "INSERT INTO bebidas (nombre,precio) VALUES (?,?)", [$json->nombre, floatval($json->precio)]);

            $filas = consulta($conexion, "select * from bebidas where nombre=?", [$json->nombre]);
            $datos2 = end($filas);

            $sentencia3 = consulta($conexion, "UPDATE bebidas SET img=? where id=?", ['/uploads/bebidas/' . $datos2['id'] . '.jpg', $datos2['id']]);

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
