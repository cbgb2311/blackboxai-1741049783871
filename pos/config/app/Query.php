<?php
class Query extends Conexion
{
    private $pdo, $con;

    public function __construct()
    {
        $this->pdo = new Conexion();
        $this->con = $this->pdo->conectar();
    }

    public function select(string $sql, array $params = [])
    {
        try {
            $stmt = $this->con->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en Query::select: " . $e->getMessage());
            return null;
        }
    }

    public function selectAll(string $sql, array $params = [])
    {
        try {
            $stmt = $this->con->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en Query::selectAll: " . $e->getMessage());
            return [];
        }
    }

    public function insertar(string $sql, array $params = [])
    {
        try {
            $stmt = $this->con->prepare($sql);
            $result = $stmt->execute($params);
            
            if ($result) {
                return $this->con->lastInsertId();
            }
            return 0;
        } catch (PDOException $e) {
            error_log("Error en Query::insertar: " . $e->getMessage());
            return 0;
        }
    }

    public function save(string $sql, array $params = [])
    {
        try {
            $stmt = $this->con->prepare($sql);
            return $stmt->execute($params) ? 1 : 0;
        } catch (PDOException $e) {
            error_log("Error en Query::save: " . $e->getMessage());
            return 0;
        }
    }

    public function delete(string $sql, array $params = [])
    {
        try {
            $stmt = $this->con->prepare($sql);
            return $stmt->execute($params) ? 1 : 0;
        } catch (PDOException $e) {
            error_log("Error en Query::delete: " . $e->getMessage());
            return 0;
        }
    }

    public function beginTransaction()
    {
        return $this->con->beginTransaction();
    }

    public function commit()
    {
        return $this->con->commit();
    }

    public function rollback()
    {
        return $this->con->rollBack();
    }
}
