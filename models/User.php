<?php
require_once 'config/database.php';

class User {
    private $conn;
    private $table_name = "usuarios";

    public $id;
    public $nome;
    public $email;
    public $senha;
    public $cargo;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function register() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET nome=:nome, email=:email, senha=:senha, cargo=:cargo";

        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->senha = password_hash($this->senha, PASSWORD_DEFAULT);
        $this->cargo = htmlspecialchars(strip_tags($this->cargo));

        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":senha", $this->senha);
        $stmt->bindParam(":cargo", $this->cargo);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function login() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(password_verify($this->senha, $row['senha'])) {
                $this->id = $row['id'];
                $this->nome = $row['nome'];
                $this->cargo = $row['cargo'];
                return true;
            }
        }
        return false;
    }

    public function emailExists() {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
?>