<?php

// Configuración de la base de datos.
// En local (XAMPP) se usan estos valores por defecto.
// En el servidor, crea config.prod.php (a partir de config.prod.example.php) con los datos del hosting.

// El navegador envía una petición OPTIONS (preflight) antes de cada POST con JSON.
// Solo necesita las cabeceras CORS, que ya se han enviado: se responde sin ejecutar el script.
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Desde PHP 8.1 mysqli lanza excepciones por defecto; el código espera que las consultas devuelvan false.
mysqli_report(MYSQLI_REPORT_OFF);

if (file_exists(__DIR__ . '/config.prod.php')) {
    require __DIR__ . '/config.prod.php';
} else {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'app1');
}

function conectar()
{
    $conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$conexion->connect_errno) {
        $conexion->set_charset('utf8mb4');
    }
    return $conexion;
}

// Ejecuta una consulta preparada con parámetros "?" (evita la inyección SQL).
// Devuelve las filas en un SELECT, true/false en INSERT/UPDATE/DELETE, o false si falla.
// Los valores se convierten a texto igual que al concatenarlos (null -> '').
function consulta($conexion, $sql, $params = [])
{
    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        return false;
    }
    if ($params) {
        $valores = [];
        foreach ($params as $v) {
            $valores[] = is_scalar($v) ? (string) $v : '';
        }
        $stmt->bind_param(str_repeat('s', count($valores)), ...$valores);
    }
    if (!$stmt->execute()) {
        return false;
    }
    $resultado = $stmt->get_result();
    if (!$resultado) {
        return true;
    }
    // Las consultas preparadas devuelven los números como int/float; el Front espera texto ("1"), como con query()
    $filas = $resultado->fetch_all(MYSQLI_ASSOC);
    foreach ($filas as &$fila) {
        foreach ($fila as &$valor) {
            if ($valor !== null) {
                $valor = (string) $valor;
            }
        }
    }
    return $filas;
}
