<?php
require_once "db/dbconn.php";

/**
 * Clase para interactuar con la base de datos de personas.
 */
class PersonaDb {
    protected $pdoConn;
    protected $personaApi; // Agregar una propiedad para almacenar la referencia a PersonaApi

    /**
     * Constructor de la clase. Inicializa la conexión PDO.
     */
    public function __construct() {
        try {
            $this->pdoConn = BaseDatos::conectar();
        } catch (PDOException $e) {
            $this->response(500, 'Error interno del servidor');
            exit;
        }
    }

    /**
     * Obtiene datos de una persona por ID.
     *
     * @param int $id El ID de la persona.
     * @return array|false Un array con los datos de la persona o false en caso de error.
     */
    public function getPeople($id = 0) {
        $stmt = $this->pdoConn->prepare("SELECT * FROM persona WHERE idPersona = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $people = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $people;
    }

    /**
     * Obtiene todos los datos de personas.
     *
     * @return array|false Un array con los datos de las personas o false en caso de error.
     */
    public function getPeoples() {
        $stmt = $this->pdoConn->query("SELECT * FROM persona");
        $peoples = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $peoples;
    }

    /**
     * Guarda los datos de una nueva persona.
     *
     * @param string $name El nombre de la persona.
     * @return bool true si se guardó correctamente, false en caso contrario.
     */
    public function savePeople($name) {
        $stmt = $this->pdoConn->prepare("INSERT INTO persona(nombre) VALUES(:name)");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $result = $stmt->execute();

        return $result;
    }

    /**
     * Actualiza los datos de una persona.
     *
     * @param int    $id      El ID de la persona a actualizar.
     * @param string $newName El nuevo nombre para la persona.
     * @return bool true si se actualizó correctamente, false en caso contrario.
     */
    public function updatePeople($id, $newName) {
        if ($this->checkID($id)) {
            $stmt = $this->pdoConn->prepare("UPDATE persona SET nombre = :newName WHERE idPersona = :id");
            $stmt->bindParam(':newName', $newName, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
            return $result;
        }
        return false;
    }

    /**
     * Elimina una persona por ID.
     *
     * @param int $id El ID de la persona a eliminar.
     * @return bool true si se eliminó correctamente, false en caso contrario.
     */
    public function deletePeople($id = 0) {
        if ($this->checkID($id)) {
            $stmt = $this->pdoConn->prepare("DELETE FROM persona WHERE idPersona = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
            return $result;
        }
        return false;
    }

    /**
     * Verifica si un ID de persona existe en la base de datos.
     *
     * @param int $id El ID de la persona a verificar.
     * @return bool true si el ID existe en la base de datos, false si no.
     */
    public function checkID($id) {
        $stmt = $this->pdoConn->prepare("SELECT * FROM persona WHERE idPersona = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetch();
            return !empty($result);
        }
        return false;
    }

    /**
     * Responde a la solicitud con el código de estado y un mensaje JSON.
     *
     * @param int    $code Código de estado HTTP.
     * @param string $status Estado de la respuesta.
     * @param string $message Mensaje de la respuesta.
     */
    private function response($code = 200, $status = "", $message = "") {
        // Utilizar la referencia a PersonaApi para responder
        $this->personaApi->response($code, $status, $message);
    }
}
?>