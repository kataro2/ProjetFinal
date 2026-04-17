<?php
require_once 'config/database.php';

class Manutencao {
    private $conn;
    private $table_name = "manutencoes";

    public $id;
    public $veiculo_id;
    public $data_manutencao;
    public $tipo;
    public $descricao;
    public $custo;
    public $km_atual;
    public $proxima_manutencao;
    public $status;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET veiculo_id=:veiculo_id, data_manutencao=:data_manutencao, 
                      tipo=:tipo, descricao=:descricao, custo=:custo, 
                      km_atual=:km_atual, proxima_manutencao=:proxima_manutencao, 
                      status=:status";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":veiculo_id", $this->veiculo_id);
        $stmt->bindParam(":data_manutencao", $this->data_manutencao);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":custo", $this->custo);
        $stmt->bindParam(":km_atual", $this->km_atual);
        $stmt->bindParam(":proxima_manutencao", $this->proxima_manutencao);
        $stmt->bindParam(":status", $this->status);

        if($stmt->execute()) {
            // Atualizar status do veículo para manutenção
            $veiculo = new Veiculo();
            $veiculo->id = $this->veiculo_id;
            $veiculo->status = 'manutencao';
            $veiculo->update();
            return true;
        }
        return false;
    }

    public function readAll() {
        $query = "SELECT m.*, v.placa, v.modelo 
                  FROM " . $this->table_name . " m
                  JOIN veiculos v ON m.veiculo_id = v.id
                  ORDER BY m.data_manutencao DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readByVeiculo($veiculo_id) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE veiculo_id = :veiculo_id 
                  ORDER BY data_manutencao DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":veiculo_id", $veiculo_id);
        $stmt->execute();
        return $stmt;
    }

    public function updateStatus() {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute() && $this->status == 'realizada') {
            // Se manutenção realizada, atualizar status do veículo para ativo
            $veiculo = new Veiculo();
            $veiculo->id = $this->veiculo_id;
            $veiculo->status = 'ativo';
            $veiculo->update();
        }
        return true;
    }
}
?>