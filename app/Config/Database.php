<?php
namespace App\Config;

use PDO;
use PDOException;

class Database{
    private static ?PDO $conn = null;
    
    public static function getConnection(): PDO{
        if(self::$conn) return self::$conn;

        // Lê as variáveis de ambiente definidas no docker-compose.yml
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT');
        $dbname = getenv('DB_DATABASE');
        $user = getenv('DB_USERNAME');
        $pass = getenv('DB_PASSWORD');
        $charset = "utf8mb4";

        // criar a nossa string de conexão
        // Note que o host agora é 'db' (o nome do serviço)
        $dns = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";

        // estabelecer a conexão
        try {
            self::$conn = new PDO($dns, $user, $pass);
            return self::$conn;
        } catch (PDOException $e) {
            // exibir o erro
            die("Erro na conexão com o banco: " . $e->getMessage());
        }
    }
}
?>