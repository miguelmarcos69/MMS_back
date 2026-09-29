<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');

$conexion = new mysqli('localhost', 'root', '', 'app1');

if ($conexion->connect_errno) {
    print "Codigo de error" . $conexion->connect_errno . "<br/>" . "Error de " . $conexion->connect_error;
} else {
    $json = file_get_contents('php://input'); // RECIBE EL JSON DE ANGULAR

    $params = json_decode($json); // DECODIFICA EL JSON Y LO GUARADA EN LA VARIABLE

    $nombre = $params->nombre;
    $nombreArchivo = $params->nombreArchivo;
    $archivo = $params->base64textString;
    $archivo = base64_decode($archivo);
    $ext = explode('.', $params->nombreArchivo);
    $extension = $ext[1];








    if ($params->tipo == 'platos') {
        $sentencia2 = $conexion->query("select * from platos where id='" . $params->nombre . "'");

        if ($sentencia2) {
            $datos2 = [];
            while ($d2 = $sentencia2->fetch_array(MYSQLI_ASSOC)) {
                array_push($datos2, $d2);
            }

            $sentencia3 = $conexion->query("UPDATE platos  SET img ='/uploads/platos/" . $datos2[0]['id'] . "." . $extension . "' where id='" . $datos2[0]['id'] . " '");
            $filePath = $_SERVER['DOCUMENT_ROOT'] . "/proyectodaw/uploads/platos/" . $nombre . "." . $extension;
        }
    }
    if ($params->tipo == 'bebida') {
        $sentencia2 = $conexion->query("select * from bebidas where id='" . $params->nombre . "'");


        if ($sentencia2) {
            $datos2 = [];
            while ($d2 = $sentencia2->fetch_array(MYSQLI_ASSOC)) {
                array_push($datos2, $d2);
            }

            $sentencia3 = $conexion->query("UPDATE platos  SET img ='/uploads/bebidas/" . $datos2[0]['id'] . "." . $extension . "' where id='" . $datos2[0]['id'] . " '");
            $filePath = $_SERVER['DOCUMENT_ROOT'] . "/proyectodaw/uploads/bebidas/" . $nombre . "." . $extension;
        }
    }
    if ($params->tipo == 'usuar') {


        $sentencia3 = $conexion->query("UPDATE usuarios  SET img ='/uploads/usuarios/" . $nombre . "." . $extension . "' where id='" . $nombre . " '");


        $filePath = $_SERVER['DOCUMENT_ROOT'] . "/proyectodaw/uploads/usuarios/" . $nombre . "." . $extension;
    }

    file_put_contents($filePath, $archivo);


    class Result
    {
    }
    // GENERA LOS DATOS DE RESPUESTA
    $response = new Result();

    $response->resultado = true;
    $response->mensaje = 'SE SUBIO EXITOSAMENTE';
    $response->ext = $extension;

    header('Content-Type: application/json');
    echo json_encode($response); // MUESTRA EL JSON GENERADO */
}
