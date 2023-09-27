<?php
require_once "PersonaDb.php";

class PersonaApi {
    private $peopleDB;

    public function __construct() {
        $this->peopleDB = new PersonaDb();
    }

    /**
     * Inicia la API y maneja las solicitudes.
     */
    public function start() {
        header('Content-Type: application/json');

        try {
            $method = $_SERVER['REQUEST_METHOD'];

            switch ($method) {
                case 'GET':
                    $this->handleGetRequest();
                    break;
                case 'POST':
                    $this->handlePostRequest();
                    break;
                case 'PUT':
                    $this->handlePutRequest();
                    break;
                case 'DELETE':
                    $this->handleDeleteRequest();
                    break;
                default:
                    $this->response(405, 'Método no soportado');
                    break;
            }
        } catch (Exception $e) {
            $this->response(500, 'Error interno del servidor: ' . $e->getMessage());
        }
    }

    /**
     * Maneja una solicitud GET.
     */
    public function handleGetRequest() {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'personas') {
            if (isset($_REQUEST['id'])) {
                $id = $_REQUEST['id'];
                $people = $this->peopleDB->getPeople($id);
                if ($people) {
                    $this->response(200, 'success', $people);
                } else {
                    $this->response(404, 'No encontrado', 'La persona no existe');
                }
            } else {
                $people = $this->peopleDB->getPeoples();
                $this->response(200, 'success', $people);
            }
        } else {
            $this->response(400, 'Solicitud incorrecta');
        }
    }

    /**
     * Maneja una solicitud POST.
     */
    public function handlePostRequest() {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'personas') {
            // Decodifica un string de JSON
            $data = json_decode(file_get_contents('php://input'), true);

            if (!empty($data['name'])) {
                $name = $data['name'];
                $result = $this->peopleDB->savePeople($name);
                if ($result) {
                    $this->response(200, 'success', 'Nueva persona agregada');
                } else {
                    $this->response(500, 'Error interno', 'No se pudo agregar la persona');
                }
            } else {
                $this->response(422, 'Datos incompletos', 'El nombre no está definido');
            }
        } else {
            $this->response(400, 'Solicitud incorrecta');
        }
    }

    /**
     * Maneja una solicitud PUT.
     */
    public function handlePutRequest() {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'personas' && isset($_REQUEST['id'])) {
            $id = $_REQUEST['id'];
            $data = json_decode(file_get_contents('php://input'), true);

            if (!empty($data['name'])) {
                $name = $data['name'];
                $result = $this->peopleDB->updatePeople($id, $name);
                if ($result) {
                    $this->response(200, 'success', 'Persona actualizada');
                } else {
                    $this->response(500, 'Error interno', 'No se pudo actualizar la persona');
                }
            } else {
                $this->response(422, 'Datos incompletos', 'El nombre no está definido');
            }
        } else {
            $this->response(400, 'Solicitud incorrecta');
        }
    }

    /**
     * Maneja una solicitud DELETE.
     */
    public function handleDeleteRequest() {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'personas' && isset($_REQUEST['id'])) {
            $id = $_REQUEST['id'];
            $result = $this->peopleDB->deletePeople($id);
            if ($result) {
                $this->response(204, '', '');
            } else {
                $this->response(500, 'Error interno', 'No se pudo eliminar la persona');
            }
        } else {
            $this->response(400, 'Solicitud incorrecta');
        }
    }

    /**
     * Responde a la solicitud con el código de estado y un mensaje JSON.
     *
     * @param int    $code Código de estado HTTP.
     * @param string $status Estado de la respuesta.
     * @param string $message Mensaje de la respuesta.
     */
    private function response($code = 200, $status = "", $message = "") {
        http_response_code($code);
        if (!empty($status) && !empty($message)) {
            $response = array("status" => $status, "message" => $message);
            echo json_encode($response, JSON_PRETTY_PRINT);
        }
    }
}
