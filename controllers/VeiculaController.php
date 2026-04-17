<?php
require_once 'controllers/AuthController.php';

class VeiculoController {
    private $veiculo;
    private $auth;
    
    public function __construct() {
        $this->veiculo = new Veiculo();
        $this->auth = new AuthController();
    }
    
    public function index() {
        $this->auth->checkAuth();
        $stmt = $this->veiculo->readAll();
        $veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'views/veiculos/index.php';
    }
    
    public function create() {
        $this->auth->checkAdmin();
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->veiculo->placa = $_POST['placa'];
            $this->veiculo->modelo = $_POST['modelo'];
            $this->veiculo->marca = $_POST['marca'];
            $this->veiculo->ano = $_POST['ano'];
            $this->veiculo->cor = $_POST['cor'];
            $this->veiculo->km_atual = $_POST['km_atual'];
            $this->veiculo->status = $_POST['status'];
            
            if($this->veiculo->create()) {
                header("Location: index.php?controller=veiculo&action=index");
                exit();
            } else {
                $error = "Erro ao cadastrar veículo!";
                include 'views/veiculos/create.php';
            }
        } else {
            include 'views/veiculos/create.php';
        }
    }
    
    public function edit() {
        $this->auth->checkAdmin();
        $this->veiculo->id = $_GET['id'];
        $this->veiculo->readOne();
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->veiculo->placa = $_POST['placa'];
            $this->veiculo->modelo = $_POST['modelo'];
            $this->veiculo->marca = $_POST['marca'];
            $this->veiculo->ano = $_POST['ano'];
            $this->veiculo->cor = $_POST['cor'];
            $this->veiculo->km_atual = $_POST['km_atual'];
            $this->veiculo->status = $_POST['status'];
            
            if($this->veiculo->update()) {
                header("Location: index.php?controller=veiculo&action=index");
                exit();
            } else {
                $error = "Erro ao atualizar veículo!";
                include 'views/veiculos/edit.php';
            }
        } else {
            include 'views/veiculos/edit.php';
        }
    }
    
    public function delete() {
        $this->auth->checkAdmin();
        $this->veiculo->id = $_GET['id'];
        
        if($this->veiculo->delete()) {
            header("Location: index.php?controller=veiculo&action=index");
            exit();
        }
    }
    
    public function show() {
        $this->auth->checkAuth();
        $this->veiculo->id = $_GET['id'];
        $this->veiculo->readOne();
        
        // Carregar manutenções do veículo
        $manutencao = new Manutencao();
        $manutencoes = $manutencao->readByVeiculo($this->veiculo->id);
        
        include 'views/veiculos/show.php';
    }
}
?>