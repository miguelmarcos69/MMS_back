<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');


$conexion = new mysqli('localhost', 'root', '', 'app1');
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
    $select = $conexion->query("select * from mesas where id='" . $json[0]->id . "'");
    $consultaArray = [];
    while ($d3 = $select->fetch_array(MYSQLI_ASSOC)) {
        array_push($consultaArray, $d3);
    }


    if ($json[0]->accion == 'llevar') {
        if (count($consultaArray) == 1) {
            $sentencia = $conexion->query("UPDATE mesas  SET platos ='" . substr($cadenaPlatos, 0, -1) . "', bebida ='" . substr($cadenaBebida, 0, -1) . "', precioTotal= " . $json[0]->precioTotal . " where id=" .  intval($json[0]->id, 10));
        } else {
            $sentencia = $conexion->query("INSERT INTO mesas (numTel, platos, bebida,accion,precioTotal,idusu)
        VALUES ('" . $json[0]->numTel . "', '" .  substr($cadenaPlatos, 0, -1) . "','" .  substr($cadenaBebida, 0, -1) . "','llevar','" . $json[0]->precioTotal . "','" . $json[0]->idusu . "') ");
        }
    }

    if ($json[0]->accion == 'domi') {
        if (count($consultaArray) == 1) {
            $sentencia = $conexion->query("UPDATE mesas  SET platos ='" . substr($cadenaPlatos, 0, -1) . "', bebida ='" . substr($cadenaBebida, 0, -1) . "', precioTotal= " . $json[0]->precioTotal . " where id=" .  intval($json[0]->id, 10));
        } else {

            $sentencia = $conexion->query("INSERT INTO mesas (numTel, platos, bebida,accion,precioTotal,idusu)
            VALUES ('" . $json[0]->numTel . "','" .  substr($cadenaPlatos, 0, -1) . "','" .  substr($cadenaBebida, 0, -1) . "', 'domi','" . $json[0]->precioTotal . "','" . $json[0]->idusu . "') ");
        }
    }
    if (is_numeric($json[1])) {
        $sentencia = $conexion->query("UPDATE mesas  SET platos ='" . substr($cadenaPlatos, 0, -1) . "', bebida ='" . substr($cadenaBebida, 0, -1) . "', ocupantes= " .  intval($json[1], 10) . " , precioTotal= '" .  $json[0]->precioTotal . "' where id=" .  intval($json[0]->id, 10));
    }
    $datos = (object) array('valido' => $sentencia);
    echo json_encode($datos, JSON_FORCE_OBJECT);
}
