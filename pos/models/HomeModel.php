<?php
class HomeModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getDatos($email)
    {
        try {
            $sql = "SELECT u.id, u.username, u.nombre, u.apellidos, 
                           u.password, u.email, u.estado, 
                           u.rol_id, r.nombre as rol 
                    FROM usuarios u 
                    INNER JOIN roles r ON u.rol_id = r.id 
                    WHERE u.email = :email";
            
            $array = ['email' => $email];
            
            return $this->select($sql, $array);
        } catch (PDOException $e) {
            error_log("Error en HomeModel::getDatos: " . $e->getMessage());
            return null;
        }
    }

    public function actualizarUltimoLogin($id_usuario)
    {
        try {
            $sql = "UPDATE usuarios 
                    SET ultimo_login = NOW() 
                    WHERE id = :id_usuario";
            
            $array = ['id_usuario' => $id_usuario];
            
            return $this->save($sql, $array);
        } catch (PDOException $e) {
            error_log("Error en HomeModel::actualizarUltimoLogin: " . $e->getMessage());
            return false;
        }
    }
}
