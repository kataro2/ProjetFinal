<?php
require_once 'controllers/AuthController.php';

class UsoController {
    private $uso;
    private $auth;
    
    public function __construct() {
        $this->uso = new Uso();
        $this->auth = new AuthController();
    }
    
    public function index() {
        $this->auth->checkAuth();
        $stmt = $this->uso->readAll();
        $usos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'views/usos/index.php';
    }
    
    public function create() {
        $this->auth->checkAuth();
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->uso->veiculo_id = $_POST['veiculo_id'];
            $this->uso->usuario_id = $_SESSION['user_id'];
            $this->uso->data_saida = date('Y-m-d H:i:s');
            $this->uso->km_saida = $_POST['km_saida'];
            $this->uso->destino = $_POST['destino'];
            $this->uso->motivo = $_POST['motivo'];
            
            if($this->uso->iniciarUso()) {
                header("Location: index.php?controller=uso&action=index");
                exit();
            } else {
                $error = "Veículo não disponível para uso!";
                $veiculos = $this->getVeiculosDisponiveis();
                include 'views/usos/create.php';
            }
        } else {
            $veiculos = $this->getVeiculosDisponiveis();
            include 'views/usos/create.php';
        }
    }
    
    public function finalizar() {
        $this->auth->checkAuth();
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->uso->id = $_POST['id'];
            $this->uso->veiculo_id = $_POST['veiculo_id'];
            $this->uso->data_retorno = date('Y-m-d H:i:s');
            $this->uso->km_retorno = $_POST['km_retorno'];
            
            if($this->uso->finalizarUso()) {
                header("Location: index.php?controller=uso&action=index");
                exit();
            }
        }
    }
    
    private function getVeiculosDisponiveis() {
        $veiculo = new Veiculo();
        $stmt = $veiculo->readAll();
        $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_filter($todos, function($v) {
            return $v['status'] == 'ativo';
        });
    }
}
?>