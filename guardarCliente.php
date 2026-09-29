<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');


$conexion = new mysqli('localhost', 'root', '', 'app1');
if ($conexion->connect_errno) {
    print "Codigo de error" . $conexion->connect_errno . "<br/>" . "Error de " . $conexion->connect_error;
} else {
    $json = json_decode(file_get_contents("php://input"));

    $consulta = $conexion->query("select * from clientes where telefono='" . $json->tlfn . "'");

    $datosCliente = [];
    while ($d = $consulta->fetch_array(MYSQLI_ASSOC)) {
        array_push($datosCliente, $d);
    }
    if ($datosCliente) {
        $d = true;
    } else {
        $d = false;
    }

    if (!$d) {

        $sentencia = $conexion->query("INSERT INTO clientes (nombre,apellidos,telefono,direccion,num,localidad) VALUES ('" . $json->nombre . "','" . $json->apellidos . "','" . $json->tlfn . "','" . $json->direccion . "','" . $json->num . "','" . $json->localidad . "') ");
        $datos = (object) array('valido' => $sentencia);
    } else {
        $datos = (object) array(
            'valido' => false,
            'msg' => 'Ese cliene ya existe'
        );
    }

    echo json_encode($datos, JSON_FORCE_OBJECT);
}
