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


    $cadenaPlatos = "";
    $cadenaBebida = "";
    if ($json[0]->bebida != '') {
        # code...
        for ($i = 0; $i < count($json[0]->bebida); $i++) {
            $cadenaBebida .= $json[0]->bebida[$i]->id . "|";
        }
    }
    if ($json[0]->platos != '') {
        # code...

        for ($i = 0; $i < count($json[0]->platos); $i++) {
            $cadenaPlatos .= $json[0]->platos[$i]->id . "|";
        }
    }
    $platos = substr($cadenaPlatos, 0, -1);
    $bebida = substr($cadenaBebida, 0, -1);

    $consultaArray = consulta($conexion, "select * from mesas where id=?", [$json[0]->id]);


    if ($json[0]->accion == 'llevar' || $json[0]->accion == 'domi') {
        if (count($consultaArray) == 1) {
            $sentencia = consulta(
                $conexion,
                "UPDATE mesas SET platos=?, bebida=?, precioTotal=? where id=?",
                [$platos, $bebida, $json[0]->precioTotal, intval($json[0]->id, 10)]
            );
        } else {
            $sentencia = consulta(
                $conexion,
                "INSERT INTO mesas (numTel, platos, bebida, accion, precioTotal, idusu) VALUES (?,?,?,?,?,?)",
                [$json[0]->numTel, $platos, $bebida, $json[0]->accion, $json[0]->precioTotal, $json[0]->idusu]
            );
        }
    }

    if (is_numeric($json[1])) {
        $sentencia = consulta(
            $conexion,
            "UPDATE mesas SET platos=?, bebida=?, ocupantes=?, precioTotal=? where id=?",
            [$platos, $bebida, intval($json[1], 10), $json[0]->precioTotal, intval($json[0]->id, 10)]
        );
    }
    $datos = (object) array('valido' => $sentencia);
    echo json_encode($datos, JSON_FORCE_OBJECT);
}
