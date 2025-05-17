<?php
namespace app\modelos;

use app\config\Database;

class Usuario {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Registrar un nuevo usuario
     * 
     * @param array $datos Datos del usuario (nombre, email, password)
     * @return bool Éxito o fallo de la operación
     */
    public function registrar($datos) {
        // Verificar si la columna fecha_registro existe
        $this->db->query("SHOW COLUMNS FROM usuarios LIKE 'fecha_registro'");
        $tieneFechaRegistro = $this->db->rowCount() > 0;

        // Construir la consulta dinámicamente
        $sql = 'INSERT INTO usuarios (nombre, email, password, role' . 
               ($tieneFechaRegistro ? ', fecha_registro' : '') . 
               ') VALUES(:nombre, :email, :password, :role' . 
               ($tieneFechaRegistro ? ', NOW()' : '') . ')';
        
        $this->db->query($sql);
        $this->db->bind(':nombre', $datos['nombre']);
        $this->db->bind(':email', $datos['email']);
        $this->db->bind(':password', $datos['password']);
        $this->db->bind(':role', 'user');

        return $this->db->execute();
    }

    /**
     * Iniciar sesión de un usuario
     * 
     * @param string $email Email del usuario
     * @param string $password Contraseña del usuario
     * @return mixed Objeto usuario si es válido, false si no
     */
    public function login($email, $password) {
        $this->db->query('SELECT * FROM usuarios WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();

        if ($row && password_verify($password, $row->password)) {
            return $row;
        }
        return false;
    }

    /**
     * Verificar si un usuario existe por email
     * 
     * @param string $email Email del usuario
     * @return bool True si existe, false si no
     */
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM usuarios WHERE email = :email');
        $this->db->bind(':email', $email);
        $this->db->single();
        
        return $this->db->rowCount() > 0;
    }

    // Alias para mantener compatibilidad
    public function encontrarUsuarioPorEmail($email) {
        return $this->findUserByEmail($email);
    }

    /**
     * Obtener un usuario por ID
     * 
     * @param int $id ID del usuario
     * @return object Objeto usuario
     */
    public function obtenerUsuarioPorId($id) {
        $this->db->query('SELECT * FROM usuarios WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Obtener todos los usuarios
     * 
     * @return array Conjunto de usuarios
     */
    public function obtenerTodosUsuarios() {
        $this->db->query('SELECT * FROM usuarios ORDER BY id ASC');
        return $this->db->resultSet();
    }

    /**
     * Obtener usuarios por rol para informes
     * 
     * @param string $rol Rol del usuario (opcional)
     * @return array Conjunto de usuarios
     */
    public function obtenerUsuariosPorRol($rol = '') {
        // Verificar si existen las columnas rol, is_admin, fecha_registro y ultima_actividad
        $this->db->query("SHOW COLUMNS FROM usuarios LIKE 'rol'");
        $tieneRol = $this->db->rowCount() > 0;
        
        $this->db->query("SHOW COLUMNS FROM usuarios LIKE 'is_admin'");
        $tieneIsAdmin = $this->db->rowCount() > 0;
        
        $this->db->query("SHOW COLUMNS FROM usuarios LIKE 'fecha_registro'");
        $tieneFechaRegistro = $this->db->rowCount() > 0;
        
        $this->db->query("SHOW COLUMNS FROM usuarios LIKE 'ultima_actividad'");
        $tieneUltimaActividad = $this->db->rowCount() > 0;

        // Construir la consulta SQL dinámicamente
        $sql = 'SELECT id, nombre, email';
        if ($tieneFechaRegistro) {
            $sql .= ', fecha_registro';
        }
        if ($tieneUltimaActividad) {
            $sql .= ', ultima_actividad';
        }
        if ($tieneRol) {
            $sql .= ', rol';
        } elseif ($tieneIsAdmin) {
            $sql .= ', is_admin';
        }
        $sql .= ' FROM usuarios';

        if ($tieneRol && !empty($rol)) {
            $sql .= ' WHERE rol = :rol';
        } elseif ($tieneIsAdmin && !empty($rol)) {
            if ($rol == 'admin') {
                $sql .= ' WHERE is_admin = 1';
            } elseif ($rol == 'usuario') {
                $sql .= ' WHERE is_admin = 0';
            }
        }

        $sql .= ' ORDER BY nombre';

        $this->db->query($sql);
        
        if ($tieneRol && !empty($rol)) {
            $this->db->bind(':rol', $rol);
        }
        
        return $this->db->resultSet();
    }

    /**
     * Contar total de usuarios
     * 
     * @return int Total de usuarios
     */
    public function contarUsuarios() {
        $this->db->query('SELECT COUNT(*) as total FROM usuarios');
        $resultado = $this->db->single();
        return $resultado->total;
    }

    /**
     * Cambiar rol de usuario
     * 
     * @param int $id ID del usuario
     * @param int $isAdmin 1 para admin, 0 para usuario normal
     * @return bool Éxito o fallo de la operación
     */
    public function cambiarRolUsuario($id, $isAdmin) {
        // Verificar si existe la columna is_admin
        $this->db->query("SHOW COLUMNS FROM usuarios LIKE 'is_admin'");
        $tieneIsAdmin = $this->db->rowCount() > 0;
        
        if ($tieneIsAdmin) {
            $this->db->query('UPDATE usuarios SET is_admin = :is_admin WHERE id = :id');
            $this->db->bind(':id', $id);
            $this->db->bind(':is_admin', $isAdmin);
            return $this->db->execute();
        } else {
            // Si no existe is_admin, intentar actualizar el rol
            $this->db->query('UPDATE usuarios SET role = :role WHERE id = :id');
            $this->db->bind(':id', $id);
            $this->db->bind(':role', $isAdmin ? 'admin' : 'user');
            return $this->db->execute();
        }
    }
}