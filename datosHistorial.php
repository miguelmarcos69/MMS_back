<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');

$conexion = new mysqli('localhost', 'root', '', 'app1');
//print $conexion->server_info;


if ($conexion->connect_errno) {

    //print "Error al conectar con la BD";

    print "Codigo de error" . $conexion->connect_errno . "<br/>" . "Error de " . $conexion->connect_error;
} else {

    $consulta = $conexion->query("select * from archivoMesa");
    $datosMesas = [];
    while ($d = $consulta->fetch_array(MYSQLI_ASSOC)) {
        array_push($datosMesas, $d);
    }

    for ($i = 0; $i < count($datosMesas); $i++) {
    }

    ///*** *///

    $consulta2 = $conexion->query("select * from platos");
    $datosPlatos = [];

    while ($d2 = $consulta2->fetch_array(MYSQLI_ASSOC)) {
        array_push($datosPlatos, $d2);
    }



    $consulta3 = $conexion->query("select * from ingredientres");
    $datosIngredientes = [];

    while ($d3 = $consulta3->fetch_array(MYSQLI_ASSOC)) {
        array_push($datosIngredientes, $d3);
    }

    $consulta4 = $conexion->query("SELECT * FROM bebidas");
    $datosBebidas = [];

    while ($d4 = $consulta4->fetch_array(MYSQLI_ASSOC)) {

        array_push($datosBebidas, $d4);
    }



    for ($i = 0; $i < count($datosMesas); $i++) {
        //echo $i;

        //se generan los platos
        if ($datosMesas[$i]["platos"] != '') {
            $datosMesas[$i]["p"] = $datosMesas[$i]["platos"];
            $datosMesas[$i]["platos"] = explode("|", $datosMesas[$i]["platos"]);
            for ($a = 0; $a < count($datosMesas[$i]["platos"]); $a++) {

                $datosMesas[$i]["platos"][$a] = existeParametros($datosMesas[$i]["platos"][$a], $datosPlatos);



                if ($datosMesas[$i]["platos"][$a]['ingredientes'] != '') {
                    $datosMesas[$i]["platos"][$a]['ingredientes'] = explode("|", $datosMesas[$i]["platos"][$a]['ingredientes']);

                    //se generan los ingredientes
                    for ($x = 0; $x < count($datosMesas[$i]["platos"][$a]['ingredientes']); $x++) {
                        $datosMesas[$i]["platos"][$a]['ingredientes'][$x] = existeParametros($datosMesas[$i]["platos"][$a]['ingredientes'][$x], $datosIngredientes);
                        # code...
                    }
                }
            }
        }
        //se generan las bebidas
        if ($datosMesas[$i]["bebida"] != '') {
            $datosMesas[$i]["bebida"] = explode("|", $datosMesas[$i]["bebida"]);
            for ($a = 0; $a < count($datosMesas[$i]["bebida"]); $a++) {


                $datosMesas[$i]["bebida"][$a] = existeParametros($datosMesas[$i]["bebida"][$a], $datosBebidas);
            }
        }
        //se añade el cliente
        if ($datosMesas[$i]["accion"] == 'domi' || $datosMesas[$i]["accion"] == 'llevar') {

            $consulta3 = $conexion->query("select * from clientes where telefono='" . $datosMesas[$i]["numTel"] . "'");


            while ($d3 = $consulta3->fetch_array(MYSQLI_ASSOC)) {
                $datosMesas[$i]["nombre"] = $d3['nombre'];
                $datosMesas[$i]["apellidos"] = $d3["apellidos"];
                $datosMesas[$i]["direccion"] = $d3["direccion"];
                $datosMesas[$i]["localidad"] = $d3["localidad"];
                $datosMesas[$i]["num"] = $d3["num"];
            }
        }
        if ($datosMesas[$i]["usuPagado"] != '') {
            $consulta4 = $conexion->query("select * from usuarios where id='" . $datosMesas[$i]["usuPagado"] . "'");

            while ($d4 = $consulta4->fetch_array(MYSQLI_ASSOC)) {
                $datosMesas[$i]["usuPagado"] = $d4;
            }
        }
        if ($datosMesas[$i]["idUsu"] != '') {
            $consulta4 = $conexion->query("select * from usuarios where id='" . $datosMesas[$i]["idUsu"] . "'");

            while ($d4 = $consulta4->fetch_array(MYSQLI_ASSOC)) {
                $datosMesas[$i]["idUsu"] = $d4;
            }
        }
    }

    //Debe estar en orden para que lo lea bien


    //echo json_encode($platos);
    //echo json_encode($datosBebidas);
    //echo json_encode($datosPlatos);
    echo json_encode($datosMesas);
}



//hay que pasar el id del array que queremos buscar, y el array que queremos buscar
function existeParametros($id, $array)
{
    $res = "";
    for ($i = 0; $i < count($array); $i++) {
        if ($id == $array[$i]["id"]) {
            $res = $array[$i];
        }
    }
    return $res;
}
