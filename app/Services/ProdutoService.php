<?php
    namespace App\Services;

    use App\Config\Database;
    // importar o PDO
    use PDO;

    class ProdutoService{
        private PDO $db;

        public function __construct() {
            // instaciar a conexão
            $this->db = Database::getConnection();
        }

        public function create(
            string $nome, 
            int $categoria_id
        ): int{
            $sql = "INSERT INTO produto (nome, categoria_id)
                VALUES (:nome, :categoria_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':nome' => $nome,
                ':categoria_id' => $categoria_id
            ]);
            return $this->db->lastInsertId();
        }

        public function update(int $id, string $nome, int $categoria_id) {
            $sql = "UPDATE produto 
                SET nome = :nome, categoria_id = :categoria_id
                WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindValue(':categoria_id', $categoria_id, PDO::PARAM_INT);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        }

        public function delete(int $id) {
            // instrução sql DELETE
            $sql = "DELETE FROM produto WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        }

        public function list(?int $id = null): array {
        $sql = "SELECT p.*, c.nome AS categoria_nome
                FROM produto p
                LEFT JOIN categoria c ON p.categoria_id = c.id";
        if ($id) {
            $sql .= " WHERE p.id = :id";
        }

        $stmt = $this->db->prepare($sql);
        if ($id) $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    

        public function findById(int $id): ?array {
            $sql = "SELECT * FROM produto WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        }
    }

?>