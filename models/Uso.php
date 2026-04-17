<?php
require_once 'config/database.php';

class Uso {
    private $conn;
    private $table_name = "usos";

    public $id;
    public $veiculo_id;
    public $usuario_id;
    public $data_saida;
    public $data_retorno;
    public $km_saida;
    public $km_retorno;
    public $destino;
    public $motivo;
    public $status;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function iniciarUso() {
        $this->conn->beginTransaction();
        
        try {
            // Verificar se veículo está disponível
            $query = "SELECT status FROM veiculos WHERE id = :veiculo_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":veiculo_id", $this->veiculo_id);
            $stmt->execute();
            $veiculo = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($veiculo['status'] != 'ativo') {
                return false;
            }
            
            // Registrar uso
            $query = "INSERT INTO " . $this->table_name . "
                      SET veiculo_id=:veiculo_id, usuario_id=:usuario_id, 
                          data_saida=:data_saida, km_saida=:km_saida, 
                          destino=:destino, motivo=:motivo, status='em_uso'";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":veiculo_id", $this->veiculo_id);
            $stmt->bindParam(":usuario_id", $this->usuario_id);
            $stmt->bindParam(":data_saida", $this->data_saida);
            $stmt->bindParam(":km_saida", $this->km_saida);
            $stmt->bindParam(":destino", $this->destino);
            $stmt->bindParam(":motivo", $this->motivo);
            $stmt->execute();
            
            // Atualizar status do veículo
            $query = "UPDATE veiculos SET status = 'em_uso' WHERE id = :veiculo_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":veiculo_id", $this->veiculo_id);
            $stmt->execute();
            
            $this->conn->commit();
            return true;
        } catch(Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function finalizarUso() {
        $this->conn->beginTransaction();
        
        try {
            $query = "UPDATE " . $this->table_name . "
                      SET data_retorno=:data_retorno, km_retorno=:km_retorno, status='finalizado'
                      WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":data_retorno", $this->data_retorno);
            $stmt->bindParam(":km_retorno", $this->km_retorno);
            $stmt->bindParam(":id", $this->id);
            $stmt->execute();
            
            // Atualizar km do veículo
            $veiculo = new Veiculo();
            $veiculo->updateKm($this->veiculo_id, $this->km_retorno);
            
            // Atualizar status do veículo
            $query = "UPDATE veiculos SET status = 'ativo' WHERE id = :veiculo_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":veiculo_id", $this->veiculo_id);
            $stmt->execute();
            
            $this->conn->commit();
            return true;
        } catch(Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function readAll() {
        $query = "SELECT u.*, v.placa, v.modelo, us.nome as usuario_nome
                  FROM " . $this->table_name . " u
                  JOIN veiculos v ON u.veiculo_id = v.id
                  JOIN usuarios us ON u.usuario_id = us.id
                  ORDER BY u.data_saida DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readAtivos() {
        $query = "SELECT u.*, v.placa, v.modelo, us.nome as usuario_nome
                  FROM " . $this->table_name . " u
                  JOIN veiculos v ON u.veiculo_id = v.id
                  JOIN usuarios us ON u.usuario_id = us.id
                  WHERE u.status = 'em_uso'
                  ORDER BY u.data_saida DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>