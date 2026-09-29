<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');

require_once __DIR__ . '/config.php';
$conexion = conectar();

if ($conexion->connect_errno) {
    print "Codigo de error" . $conexion->connect_errno . "<br/>" . "Error de " . $conexion->connect_error;
} else {
    $json = file_get_contents('php://input'); // RECIBE EL JSON DE ANGULAR

    $params = json_decode($json); // DECODIFICA EL JSON Y LO GUARADA EN LA VARIABLE

    $response = new stdClass();
    $response->resultado = false;

    // El "nombre" es el id del plato, bebida o usuario: solo se aceptan números
    $nombre = intval($params->nombre ?? 0);
    $archivo = base64_decode($params->base64textString ?? '', true);
    $extension = strtolower(pathinfo($params->nombreArchivo ?? '', PATHINFO_EXTENSION));

    // Carpeta y tabla según el tipo de imagen
    $destinos = [
        'platos' => ['carpeta' => 'platos', 'tabla' => 'platos'],
        'bebida' => ['carpeta' => 'bebidas', 'tabla' => 'bebidas'],
        'usuar' => ['carpeta' => 'usuarios', 'tabla' => 'usuarios'],
    ];
    $destino = $destinos[$params->tipo ?? ''] ?? null;

    if ($nombre <= 0 || $destino === null) {
        $response->mensaje = 'Datos no válidos';
    } elseif (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
        $response->mensaje = 'Solo se permiten imágenes (jpg, png, gif, webp)';
    } elseif ($archivo === false || @getimagesizefromstring($archivo) === false) {
        $response->mensaje = 'El fichero no es una imagen válida';
    } else {
        $ruta = '/uploads/' . $destino['carpeta'] . '/' . $nombre . '.' . $extension;

        // La tabla sale de la lista fija de arriba, nunca de la petición
        consulta($conexion, "UPDATE " . $destino['tabla'] . " SET img=? where id=?", [$ruta, $nombre]);
        file_put_contents(__DIR__ . $ruta, $archivo);

        $response->resultado = true;
        $response->mensaje = 'SE SUBIO EXITOSAMENTE';
        $response->ext = $extension;
    }

    echo json_encode($response); // MUESTRA EL JSON GENERADO */
}
