document.addEventListener('DOMContentLoaded', function () {
    const formulario = document.querySelector('#formulario');
    const email = document.querySelector('#email');
    const clave = document.querySelector('#clave');
    const btnLogin = formulario.querySelector('button[type="submit"]');
    const spinner = btnLogin.querySelector('.spinner-border');
    const btnText = btnLogin.querySelector('.btn-text');

    // Configuración de SweetAlert2 para notificaciones
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    // Función para mostrar/ocultar contraseña
    window.togglePassword = function() {
        const passwordInput = document.getElementById('clave');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bx-hide');
            toggleIcon.classList.add('bx-show');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bx-show');
            toggleIcon.classList.add('bx-hide');
        }
    }

    // Validación en tiempo real para el email
    email.addEventListener('input', function() {
        validarCampo(this, 'errorEmail');
    });

    // Validación en tiempo real para la contraseña
    clave.addEventListener('input', function() {
        validarCampo(this, 'errorClave');
    });

    // Función para validar campos
    function validarCampo(campo, errorId) {
        const errorElement = document.getElementById(errorId);
        const valor = campo.value.trim();
        
        if (valor === '') {
            mostrarError(campo, errorElement, `El ${campo.placeholder} es requerido`);
            return false;
        } else if (campo.type === 'email' && !validarEmail(valor)) {
            mostrarError(campo, errorElement, 'Ingrese un correo electrónico válido');
            return false;
        } else {
            limpiarError(campo, errorElement);
            return true;
        }
    }

    // Función para mostrar error
    function mostrarError(campo, errorElement, mensaje) {
        campo.classList.remove('is-valid');
        campo.classList.add('is-invalid');
        errorElement.textContent = mensaje;
    }

    // Función para limpiar error
    function limpiarError(campo, errorElement) {
        campo.classList.remove('is-invalid');
        campo.classList.add('is-valid');
        errorElement.textContent = '';
    }

    // Validar formato de email
    function validarEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    // Manejar el envío del formulario
    formulario.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Validar todos los campos
        const esEmailValido = validarCampo(email, 'errorEmail');
        const esClaveValida = validarCampo(clave, 'errorClave');

        if (!esEmailValido || !esClaveValida) {
            return;
        }

        // Mostrar loading en el botón
        btnLogin.disabled = true;
        btnLogin.classList.add('loading');
        spinner.classList.remove('d-none');

        try {
            const formData = new FormData(this);
            const response = await fetch(base_url + 'home/validar', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error('Error en la conexión');
            }

            const data = await response.json();

            if (data.type === 'success') {
                // Mostrar mensaje de éxito
                Swal.fire({
                    icon: 'success',
                    title: '¡Bienvenido!',
                    text: data.msg,
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                }).then(() => {
                    window.location = base_url + 'admin';
                });
            } else {
                // Mostrar mensaje de error
                Toast.fire({
                    icon: data.type,
                    title: data.msg
                });

                // Resetear el botón
                btnLogin.disabled = false;
                btnLogin.classList.remove('loading');
                spinner.classList.add('d-none');
            }
        } catch (error) {
            console.error('Error:', error);
            
            // Mostrar mensaje de error
            Toast.fire({
                icon: 'error',
                title: 'Error al conectar con el servidor'
            });

            // Resetear el botón
            btnLogin.disabled = false;
            btnLogin.classList.remove('loading');
            spinner.classList.add('d-none');
        }
    });

    // Recordar usuario si existe en localStorage
    const recordarCheckbox = document.getElementById('recordar');
    const emailGuardado = localStorage.getItem('userEmail');
    
    if (emailGuardado) {
        email.value = emailGuardado;
        recordarCheckbox.checked = true;
    }

    // Guardar email en localStorage si se marca "Recordarme"
    recordarCheckbox.addEventListener('change', function() {
        if (this.checked && email.value) {
            localStorage.setItem('userEmail', email.value);
        } else {
            localStorage.removeItem('userEmail');
        }
    });

    // Actualizar localStorage cuando se modifica el email
    email.addEventListener('change', function() {
        if (recordarCheckbox.checked) {
            localStorage.setItem('userEmail', this.value);
        }
    });
});
