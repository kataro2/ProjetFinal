<?php
require_once 'controllers/AuthController.php';

class ManutencaoController {
    private $manutencao;
    private $auth;
    
    public function __construct() {
        $this->manutencao = new Manutencao();
        $this->auth = new AuthController();
    }
    
    public function index() {
        $this->auth->checkAuth();
        $stmt = $this->manutencao->readAll();
        $manutencoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'views/manutencoes/index.php';
    }
    
    public function create() {
        $this->auth->checkAdmin();
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->manutencao->veiculo_id = $_POST['veiculo_id'];
            $this->manutencao->data_manutencao = $_POST['data_manutencao'];
            $this->manutencao->tipo = $_POST['tipo'];
            $this->manutencao->descricao = $_POST['descricao'];
            $this->manutencao->custo = $_POST['custo'];
            $this->manutencao->km_atual = $_POST['km_atual'];
            $this->manutencao->proxima_manutencao = $_POST['proxima_manutencao'];
            $this->manutencao->status = $_POST['status'];
            
            if($this->manutencao->create()) {
                header("Location: index.php?controller=manutencao&action=index");
                exit();
            } else {
                $error = "Erro ao cadastrar manutenção!";
                $veiculos = $this->getVeiculos();
                include 'views/manutencoes/create.php';
            }
        } else {
            $veiculos = $this->getVeiculos();
            include 'views/manutencoes/create.php';
        }
    }
    
    private function getVeiculos() {
        $veiculo = new Veiculo();
        $stmt = $veiculo->readAll();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>