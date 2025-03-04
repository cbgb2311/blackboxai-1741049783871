<?php
class Home extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
    }

    public function index()
    {
        // Verificar si ya hay una sesión activa
        if (isset($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL . 'admin');
            exit;
        }
        
        $data['title'] = 'Iniciar Sesión';
        $this->views->getView('principal', 'login', $data);
    }

    public function validar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $res = array('msg' => 'Método no permitido', 'type' => 'error');
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            return;
        }

        try {
            // Validar campos requeridos
            if (empty($_POST['email']) || empty($_POST['clave'])) {
                $campo = empty($_POST['email']) ? 'correo' : 'contraseña';
                $res = array(
                    'msg' => 'El campo ' . $campo . ' es requerido',
                    'type' => 'warning'
                );
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                return;
            }

            // Limpiar y validar el correo
            $email = strClean($_POST['email']);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $res = array(
                    'msg' => 'Formato de correo electrónico inválido',
                    'type' => 'warning'
                );
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                return;
            }

            // Limpiar contraseña
            $clave = strClean($_POST['clave']);

            // Obtener datos del usuario
            $data = $this->model->getDatos($email);

            if (empty($data)) {
                $res = array(
                    'msg' => 'El correo electrónico no está registrado',
                    'type' => 'error'
                );
            } else {
                // Verificar contraseña
                if (password_verify($clave, $data['password'])) {
                    // Verificar si el usuario está activo
                    if ($data['estado'] != 1) {
                        $res = array(
                            'msg' => 'Usuario inactivo, contacte al administrador',
                            'type' => 'warning'
                        );
                    } else {
                        // Crear sesión
                        $_SESSION['id_usuario'] = $data['id'];
                        $_SESSION['nombre_usuario'] = $data['nombre'] . ' ' . $data['apellidos'];
                        $_SESSION['email'] = $data['email'];
                        $_SESSION['rol'] = $data['rol_id'];
                        $_SESSION['rol_nombre'] = $data['rol'];

                        // Actualizar último login
                        $this->model->actualizarUltimoLogin($data['id']);

                        $res = array(
                            'msg' => '¡Bienvenido al Sistema!',
                            'type' => 'success'
                        );
                    }
                } else {
                    $res = array(
                        'msg' => 'Contraseña incorrecta',
                        'type' => 'error'
                    );
                }
            }

        } catch (Exception $e) {
            // Registrar error en logs
            error_log("Error en login: " . $e->getMessage());
            
            $res = array(
                'msg' => 'Error en el servidor',
                'type' => 'error'
            );
        }

        echo json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    public function salir()
    {
        session_destroy();
        header('Location: ' . BASE_URL);
    }
}
