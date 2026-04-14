<?php
require_once 'config/database.php';

class Veiculo {
    private $conn;
    private $table_name = "veiculos";

    public $id;
    public $placa;
    public $modelo;
    public $marca;
    public $ano;
    public $cor;
    public $km_atual;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET placa=:placa, modelo=:modelo, marca=:marca, 
                      ano=:ano, cor=:cor, km_atual=:km_atual, status=:status";

        $stmt = $this->conn->prepare($query);

        $this->placa = htmlspecialchars(strip_tags($this->placa));
        $this->modelo = htmlspecialchars(strip_tags($this->modelo));
        $this->marca = htmlspecialchars(strip_tags($this->marca));
        $this->ano = htmlspecialchars(strip_tags($this->ano));
        $this->cor = htmlspecialchars(strip_tags($this->cor));
        $this->km_atual = htmlspecialchars(strip_tags($this->km_atual));
        $this->status = htmlspecialchars(strip_tags($this->status));

        $stmt->bindParam(":placa", $this->placa);
        $stmt->bindParam(":modelo", $this->modelo);
        $stmt->bindParam(":marca", $this->marca);
        $stmt->bindParam(":ano", $this->ano);
        $stmt->bindParam(":cor", $this->cor);
        $stmt->bindParam(":km_atual", $this->km_atual);
        $stmt->bindParam(":status", $this->status);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->placa = $row['placa'];
        $this->modelo = $row['modelo'];
        $this->marca = $row['marca'];
        $this->ano = $row['ano'];
        $this->cor = $row['cor'];
        $this->km_atual = $row['km_atual'];
        $this->status = $row['status'];
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET placa=:placa, modelo=:modelo, marca=:marca, 
                      ano=:ano, cor=:cor, km_atual=:km_atual, status=:status
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->placa = htmlspecialchars(strip_tags($this->placa));
        $this->modelo = htmlspecialchars(strip_tags($this->modelo));
        $this->marca = htmlspecialchars(strip_tags($this->marca));
        $this->ano = htmlspecialchars(strip_tags($this->ano));
        $this->cor = htmlspecialchars(strip_tags($this->cor));
        $this->km_atual = htmlspecialchars(strip_tags($this->km_atual));
        $this->status = htmlspecialchars(strip_tags($this->status));

        $stmt->bindParam(":placa", $this->placa);
        $stmt->bindParam(":modelo", $this->modelo);
        $stmt->bindParam(":marca", $this->marca);
        $stmt->bindParam(":ano", $this->ano);
        $stmt->bindParam(":cor", $this->cor);
        $stmt->bindParam(":km_atual", $this->km_atual);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateKm($veiculo_id, $novo_km) {
        $query = "UPDATE " . $this->table_name . " SET km_atual = :km_atual WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":km_atual", $novo_km);
        $stmt->bindParam(":id", $veiculo_id);
        return $stmt->execute();
    }
}
?>