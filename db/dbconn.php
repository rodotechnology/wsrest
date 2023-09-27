<?php
require_once "config.php";

class baseDatos {
    // Atributos de clase
    private $conexion;
    private $db;

    /**
     * Establece una conexión PDO con la base de datos.
     *
     * @return PDO|false Una instancia de PDO o false en caso de error.
     */
    public static function conectar()
    {
        try {
            $dsn = "mysql:host=".host.";dbname=".dbname.";port=".port;
            $conexion = new PDO($dsn, user, pass);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conexion;
        } catch (PDOException $e) {
            die("Lo sentimos, no se pudo establecer la conexión con MySQL/MariaDB: " . $e->getMessage());
        }
    }

    /**
     * Cierra la conexión PDO.
     *
     * @param PDO $conexion La instancia de PDO a desconectar.
     */
    public static function desconectar($conexion)
    {
        $conexion = null; // Cierra la conexión PDO
    }
}
?>