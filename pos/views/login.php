<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo BASE_URL; ?>assets/images/favicon-32x32.png" type="image/png" />
    
    <!-- CSS -->
    <link href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>assets/css/login.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>assets/css/icons.css" rel="stylesheet">
    
    <title><?php echo TITLE . ' - ' . $data['title']; ?></title>
</head>

<body class="login-body">
    <div class="login-wrapper">
        <div class="login-container">
            <!-- Logo y título -->
            <div class="text-center mb-4">
                <img src="<?php echo BASE_URL; ?>assets/images/logo-img.png" alt="Logo" class="login-logo">
                <h2 class="login-title mt-3">Sistema POS</h2>
                <p class="login-subtitle">Ingresa tus credenciales para continuar</p>
            </div>

            <!-- Formulario de login -->
            <div class="login-card">
                <form id="formulario" method="POST" autocomplete="off">
                    <!-- Campo de email -->
                    <div class="form-floating mb-4">
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="nombre@ejemplo.com" required>
                        <label for="email">Correo Electrónico</label>
                        <div class="invalid-feedback" id="errorEmail"></div>
                    </div>

                    <!-- Campo de contraseña -->
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="clave" name="clave" 
                               placeholder="Contraseña" required>
                        <label for="clave">Contraseña</label>
                        <span class="password-toggle" onclick="togglePassword()">
                            <i class="bx bx-hide" id="toggleIcon"></i>
                        </span>
                        <div class="invalid-feedback" id="errorClave"></div>
                    </div>

                    <!-- Opciones adicionales -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="recordar">
                            <label class="form-check-label" for="recordar">Recordar correo</label>
                        </div>
                        <a href="#" class="forgot-password" data-bs-toggle="modal" data-bs-target="#recuperarModal">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <!-- Botón de ingreso -->
                    <button type="submit" class="btn btn-primary w-100 login-btn">
                        <span class="btn-text">Iniciar Sesión</span>
                        <div class="spinner-border spinner-border-sm d-none" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="login-footer text-center mt-4">
                <p>&copy; <?php echo date('Y'); ?> <?php echo TITLE; ?>. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>

    <!-- Modal Recuperar Contraseña -->
    <div class="modal fade" id="recuperarModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Recuperar Contraseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Ingresa tu correo electrónico para recibir instrucciones de recuperación.</p>
                    <div class="form-floating">
                        <input type="email" class="form-control" id="recuperarEmail" 
                               placeholder="nombre@ejemplo.com">
                        <label for="recuperarEmail">Correo Electrónico</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="enviarRecuperacion()">Enviar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/sweetalert2.all.min.js"></script>
    <script>
        const base_url = '<?php echo BASE_URL; ?>';
    </script>
    <script src="<?php echo BASE_URL; ?>assets/js/modulos/login.js"></script>
</body>

</html>
