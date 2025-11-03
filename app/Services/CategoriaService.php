<?php
    namespace App\Services;

    use App\Config\Database;
    use PDO;

    class CategoriaService{
        private PDO $db;

        public function __construct() {
            // instaciar a conexão
            $this->db = Database::getConnection();
        }

        public function create(
            string $nome
        ){
            $sql = "INSERT INTO categoria (
                nome
            ) VALUES (:nome)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':nome' => $nome
            ]);
            return $this->db->lastInsertId();
        }

        public function update(
            int $id,
            string $nome
        ) {
            // instrução sql UPDATE
            $sql = "UPDATE categoria SET nome = :nome WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            // passar parametros
            $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
    
        }

        public function delete(int $id) {
            // instrução sql DELETE
            $sql = "DELETE FROM categoria WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        }

         public function list(?int $id = null): array {
            $sql = "SELECT * FROM categoria";
            if ($id) {
                $sql .= " WHERE id = :id";
            }

            $stmt = $this->db->prepare($sql);
            if ($id) {
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function findById(int $id): ?array {
            $sql = "SELECT * FROM categoria WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        }

    }
?>