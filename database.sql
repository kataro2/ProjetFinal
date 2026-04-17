CREATE DATABASE IF NOT EXISTS frota_sistema;
USE frota_sistema;

-- Tabela de usuários
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    cargo ENUM('admin', 'operador') DEFAULT 'operador',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de veículos
CREATE TABLE veiculos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    placa VARCHAR(10) UNIQUE NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    marca VARCHAR(50) NOT NULL,
    ano INT NOT NULL,
    cor VARCHAR(30),
    km_atual INT DEFAULT 0,
    status ENUM('ativo', 'manutencao', 'inativo') DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de manutenções
CREATE TABLE manutencoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    veiculo_id INT NOT NULL,
    data_manutencao DATE NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    descricao TEXT,
    custo DECIMAL(10,2),
    km_atual INT NOT NULL,
    proxima_manutencao DATE,
    status ENUM('agendada', 'realizada', 'cancelada') DEFAULT 'agendada',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (veiculo_id) REFERENCES veiculos(id) ON DELETE CASCADE
);

-- Tabela de uso dos veículos
CREATE TABLE usos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    veiculo_id INT NOT NULL,
    usuario_id INT NOT NULL,
    data_saida DATETIME NOT NULL,
    data_retorno DATETIME,
    km_saida INT NOT NULL,
    km_retorno INT,
    destino VARCHAR(200),
    motivo VARCHAR(200),
    status ENUM('em_uso', 'finalizado', 'cancelado') DEFAULT 'em_uso',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (veiculo_id) REFERENCES veiculos(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabela de histórico (log)
CREATE TABLE historico (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT,
    acao VARCHAR(100),
    tabela_afetada VARCHAR(50),
    registro_id INT,
    descricao TEXT,
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Inserir usuário admin padrão
INSERT INTO usuarios (nome, email, senha, cargo) 
VALUES ('Administrador', 'admin@frota.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- Senha: password