<?php
    class Faq {
        private $id_faq;
        private $pregunta;
        private $respuesta;
        private $fecha_actualizacion;
        private $id_categoria;

    public function __construct($id_faq, $pregunta, $respuesta, $id_categoria, $fecha_actualizacion = '') {
        $this->id_faq = $id_faq;
        $this->pregunta = $pregunta;
        $this->respuesta = $respuesta;
        $this->id_categoria = $id_categoria;
        $this->fecha_actualizacion = $fecha_actualizacion ?: date('Y-m-d');
    }

    /**
     * Obtiene el ID de la pregunta frecuente
     * @return int
     */
    public function getIdFaq() {
        return $this->id_faq;
    }

    /**
     * Obtiene la pregunta
     * @return string
     */
    public function getPregunta() {
        return $this->pregunta;
    }

    /**
     * Obtiene la respuesta
     * @return string
     */
    public function getRespuesta() {
        return $this->respuesta;
    }

    /**
     * Obtiene la fecha de actualización
     * @return string
     */
    public function getFechaActualizacion() {
        return $this->fecha_actualizacion;
    }

    /**
     * Obtiene el ID de la categoría
     * @return int
     */
    public function getIdCategoria() {
        return $this->id_categoria;
    }

    /**
     * Establece la pregunta
     * @param string $pregunta
     */
    public function setPregunta($pregunta) {
        $this->pregunta = $pregunta;
    }

    /**
     * Establece la respuesta
     * @param string $respuesta
     */
    public function setRespuesta($respuesta) {
        $this->respuesta = $respuesta;
    }

    /**
     * Establece el ID de la categoría
     * @param int $id_categoria
     */
    public function setIdCategoria($id_categoria) {
        $this->id_categoria = $id_categoria;
    }

    /**
     * Actualiza la fecha de actualización a la fecha actual
     */
    public function actualizarFecha() {
        $this->fecha_actualizacion = date('Y-m-d');
    }

    // ---- MÉTODOS ESTÁTICOS DE BD ----

    /**
     * Obtiene todas las preguntas frecuentes
     * @param PDO $db Conexión a la base de datos
     * @return array|null Array de objetos Faq
     */
    public static function obtenerTodas($db) {
        $faqs = [];
        $sql = "SELECT id_faq, pregunta, respuesta, id_categoria, fecha_actualizacion FROM faq ORDER BY id_faq ASC";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $faqs[] = new self(
                    $row['id_faq'],
                    $row['pregunta'],
                    $row['respuesta'],
                    $row['id_categoria'],
                    $row['fecha_actualizacion']
                );
            }
        } catch (PDOException $e) {
            echo "Error al obtener FAQs: " . $e->getMessage();
            return null;
        }
        return $faqs;
    }

    /**
     * Obtiene una pregunta frecuente por su ID
     * @param PDO $db Conexión a la base de datos
     * @param int $id_faq ID del FAQ
     * @return Faq|null
     */
    public static function obtenerPorId($db, $id_faq) {
        $sql = "SELECT id_faq, pregunta, respuesta, id_categoria, fecha_actualizacion FROM faq WHERE id_faq = :id_faq";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id_faq', intval($id_faq), PDO::PARAM_INT);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return new self(
                    $row['id_faq'],
                    $row['pregunta'],
                    $row['respuesta'],
                    $row['id_categoria'],
                    $row['fecha_actualizacion']
                );
            }
        } catch (PDOException $e) {
            echo "Error al obtener FAQ: " . $e->getMessage();
        }
        return null;
    }

    /**
     * Obtiene todas las FAQs de una categoría específica
     * @param PDO $db Conexión a la base de datos
     * @param int $id_categoria ID de la categoría
     * @return array|null Array de objetos Faq
     */
    public static function obtenerPorCategoria($db, $id_categoria) {
        $faqs = [];
        $sql = "SELECT id_faq, pregunta, respuesta, id_categoria, fecha_actualizacion FROM faq WHERE id_categoria = :id_categoria ORDER BY id_faq ASC";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id_categoria', intval($id_categoria), PDO::PARAM_INT);
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $faqs[] = new self(
                    $row['id_faq'],
                    $row['pregunta'],
                    $row['respuesta'],
                    $row['id_categoria'],
                    $row['fecha_actualizacion']
                );
            }
        } catch (PDOException $e) {
            echo "Error al obtener FAQs por categoría: " . $e->getMessage();
            return null;
        }
        return $faqs;
    }

    /**
     * Inserta esta pregunta frecuente en la base de datos
     * @param PDO $db Conexión a la base de datos
     * @return int ID del nuevo FAQ o false si hay error
     */
    public function insertar($db) {
        $sql = "INSERT INTO faq (pregunta, respuesta, id_categoria) VALUES (:pregunta, :respuesta, :id_categoria)";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':pregunta', $this->pregunta, PDO::PARAM_STR);
            $stmt->bindValue(':respuesta', $this->respuesta, PDO::PARAM_STR);
            $stmt->bindValue(':id_categoria', intval($this->id_categoria), PDO::PARAM_INT);
            $stmt->execute();
            $this->id_faq = $db->lastInsertId();
            return $this->id_faq;
        } catch (PDOException $e) {
            echo "Error al insertar FAQ: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Actualiza esta pregunta frecuente en la base de datos
     * @param PDO $db Conexión a la base de datos
     * @return bool true si se actualizó correctamente
     */
    public function actualizar($db) {
        $sql = "UPDATE faq SET pregunta = :pregunta, respuesta = :respuesta, id_categoria = :id_categoria, fecha_actualizacion = CURRENT_DATE WHERE id_faq = :id_faq";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':pregunta', $this->pregunta, PDO::PARAM_STR);
            $stmt->bindValue(':respuesta', $this->respuesta, PDO::PARAM_STR);
            $stmt->bindValue(':id_categoria', intval($this->id_categoria), PDO::PARAM_INT);
            $stmt->bindValue(':id_faq', intval($this->id_faq), PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al actualizar FAQ: " . $e->getMessage();
            return false;
        }
    }
        }
    }

    /**
     * Elimina una pregunta frecuente
     * @param PDO $db Conexión a la base de datos
     * @param int $id_faq ID del FAQ a eliminar
     * @return bool true si se eliminó correctamente
     */
    public static function eliminar($db, $id_faq) {
        $sql = "DELETE FROM faq WHERE id_faq = :id_faq";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id_faq', intval($id_faq), PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al eliminar FAQ: " . $e->getMessage();
            return false;
        }
    }
    }
?>