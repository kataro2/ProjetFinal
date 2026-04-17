<?php
session_start();

class AuthController {
    private $user;
    
    public function __construct() {
        $this->user = new User();
    }
    
    public function login() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->user->email = $_POST['email'];
            $this->user->senha = $_POST['senha'];
            
            if($this->user->login()) {
                $_SESSION['user_id'] = $this->user->id;
                $_SESSION['user_nome'] = $this->user->nome;
                $_SESSION['user_cargo'] = $this->user->cargo;
                header("Location: index.php?controller=dashboard&action=index");
                exit();
            } else {
                $error = "Email ou senha inválidos!";
                include 'views/auth/login.php';
            }
        } else {
            include 'views/auth/login.php';
        }
    }
    
    public function register() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->user->nome = $_POST['nome'];
            $this->user->email = $_POST['email'];
            $this->user->senha = $_POST['senha'];
            $this->user->cargo = $_POST['cargo'];
            
            if($this->user->emailExists()) {
                $error = "Email já cadastrado!";
                include 'views/auth/register.php';
            } else if($this->user->register()) {
                header("Location: index.php?controller=auth&action=login");
                exit();
            } else {
                $error = "Erro ao cadastrar usuário!";
                include 'views/auth/register.php';
            }
        } else {
            include 'views/auth/register.php';
        }
    }
    
    public function logout() {
        session_destroy();
        header("Location: index.php?controller=auth&action=login");
        exit();
    }
    
    public function checkAuth() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
    }
    
    public function checkAdmin() {
        $this->checkAuth();
        if($_SESSION['user_cargo'] != 'admin') {
            header("Location: index.php?controller=dashboard&action=index");
            exit();
        }
    }
}
?>