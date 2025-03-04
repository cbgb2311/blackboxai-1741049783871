-- Tabla para registrar los intentos de inicio de sesión
CREATE TABLE IF NOT EXISTS `log_accesos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `correo` varchar(100) NOT NULL,
  `exitoso` tinyint(1) NOT NULL DEFAULT 0,
  `ip_address` varchar(45) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `detalles` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_log_accesos_correo` (`correo`),
  KEY `idx_log_accesos_fecha` (`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Agregar columnas necesarias a la tabla usuarios si no existen
ALTER TABLE `usuarios` 
ADD COLUMN IF NOT EXISTS `ultimo_login` timestamp NULL DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `fecha_bloqueo` timestamp NULL DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `intentos_fallidos` int(11) DEFAULT 0;

-- Crear procedimiento almacenado para actualizar intentos fallidos
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS sp_actualizar_intentos_fallidos(
    IN p_correo VARCHAR(100),
    IN p_exitoso TINYINT
)
BEGIN
    IF p_exitoso = 0 THEN
        -- Incrementar intentos fallidos
        UPDATE usuarios 
        SET intentos_fallidos = intentos_fallidos + 1
        WHERE correo = p_correo;
        
        -- Bloquear usuario si supera 5 intentos fallidos
        UPDATE usuarios 
        SET estado = 0,
            fecha_bloqueo = NOW()
        WHERE correo = p_correo 
        AND intentos_fallidos >= 5;
    ELSE
        -- Resetear intentos fallidos si el login es exitoso
        UPDATE usuarios 
        SET intentos_fallidos = 0,
            fecha_bloqueo = NULL
        WHERE correo = p_correo;
    END IF;
END //
DELIMITER ;

-- Crear trigger para log_accesos
DELIMITER //
CREATE TRIGGER IF NOT EXISTS tr_after_log_acceso
AFTER INSERT ON log_accesos
FOR EACH ROW
BEGIN
    CALL sp_actualizar_intentos_fallidos(NEW.correo, NEW.exitoso);
END //
DELIMITER ;
