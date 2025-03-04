<?php
/**
 * Limpia y sanitiza una cadena de texto
 * @param string $cadena Cadena a limpiar
 * @return string Cadena limpia
 */
function strClean($cadena)
{
    $cadena = trim($cadena);
    $cadena = stripslashes($cadena);
    $cadena = str_ireplace("<script>", "", $cadena);
    $cadena = str_ireplace("</script>", "", $cadena);
    $cadena = str_ireplace("<script src", "", $cadena);
    $cadena = str_ireplace("<script type=", "", $cadena);
    $cadena = str_ireplace("SELECT * FROM", "", $cadena);
    $cadena = str_ireplace("DELETE FROM", "", $cadena);
    $cadena = str_ireplace("INSERT INTO", "", $cadena);
    $cadena = str_ireplace("UPDATE", "", $cadena);
    $cadena = str_ireplace("DROP TABLE", "", $cadena);
    $cadena = str_ireplace("OR '1'='1", "", $cadena);
    $cadena = str_ireplace('OR "1"="1"', "", $cadena);
    $cadena = str_ireplace('OR ´1´=´1´', "", $cadena);
    $cadena = str_ireplace("is NULL; --", "", $cadena);
    $cadena = str_ireplace("LIKE '", "", $cadena);
    $cadena = str_ireplace('LIKE "', "", $cadena);
    $cadena = str_ireplace("LIKE ´", "", $cadena);
    $cadena = str_ireplace("OR 'a'='a", "", $cadena);
    $cadena = str_ireplace('OR "a"="a', "", $cadena);
    $cadena = str_ireplace("OR ´a´=´a", "", $cadena);
    $cadena = str_ireplace("--", "", $cadena);
    $cadena = str_ireplace("^", "", $cadena);
    $cadena = str_ireplace("[", "", $cadena);
    $cadena = str_ireplace("]", "", $cadena);
    $cadena = str_ireplace("==", "", $cadena);
    return $cadena;
}

/**
 * Genera un token seguro
 * @return string Token generado
 */
function generateToken()
{
    return bin2hex(random_bytes(32));
}

/**
 * Verifica si una sesión está activa
 * @return bool True si hay sesión activa, false en caso contrario
 */
function sessionExists()
{
    return isset($_SESSION['id_usuario']);
}

/**
 * Obtiene la IP real del cliente
 * @return string IP del cliente
 */
function getClientIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

/**
 * Formatea una fecha al formato especificado
 * @param string $fecha Fecha a formatear
 * @param string $formato Formato deseado (default: d/m/Y)
 * @return string Fecha formateada
 */
function formatearFecha($fecha, $formato = 'd/m/Y')
{
    return date($formato, strtotime($fecha));
}

/**
 * Formatea un número como moneda
 * @param float $cantidad Cantidad a formatear
 * @param int $decimales Número de decimales (default: 2)
 * @return string Cantidad formateada
 */
function formatearMoneda($cantidad, $decimales = 2)
{
    return number_format($cantidad, $decimales, '.', ',');
}

/**
 * Verifica si el usuario tiene un rol específico
 * @param array|string $roles Rol o roles permitidos
 * @return bool True si tiene el rol, false en caso contrario
 */
function tieneRol($roles)
{
    if (!isset($_SESSION['rol'])) {
        return false;
    }

    if (is_array($roles)) {
        return in_array($_SESSION['rol'], $roles);
    }

    return $_SESSION['rol'] == $roles;
}

/**
 * Redirecciona a una URL específica
 * @param string $url URL a redireccionar
 */
function redirect($url)
{
    header('Location: ' . BASE_URL . $url);
    exit;
}

/**
 * Genera una contraseña aleatoria segura
 * @param int $length Longitud de la contraseña
 * @return string Contraseña generada
 */
function generatePassword($length = 10)
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?';
    return substr(str_shuffle($chars), 0, $length);
}

/**
 * Envía una respuesta JSON
 * @param mixed $data Datos a enviar
 * @param int $status Código de estado HTTP
 */
function jsonResponse($data, $status = 200)
{
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Verifica si una petición es AJAX
 * @return bool True si es AJAX, false en caso contrario
 */
function isAjax()
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

/**
 * Registra un mensaje en el archivo de log
 * @param string $mensaje Mensaje a registrar
 * @param string $tipo Tipo de mensaje (error, info, warning)
 */
function registrarLog($mensaje, $tipo = 'error')
{
    $fecha = date('Y-m-d H:i:s');
    $log = "[{$fecha}] [{$tipo}] {$mensaje}" . PHP_EOL;
    error_log($log, 3, __DIR__ . '/../logs/app.log');
}
