-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS crud_clientes;
USE crud_clientes;

-- Criar tabela de clientes
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefone VARCHAR(20),
    endereco TEXT,
    data_nascimento DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Inserir alguns dados de exemplo
INSERT INTO clientes (nome, email, telefone, endereco, data_nascimento) VALUES
('João Silva', 'joao@email.com', '(11) 99999-9999', 'Rua das Flores, 123', '1990-05-15'),
('Maria Santos', 'maria@email.com', '(11) 88888-8888', 'Av. Principal, 456', '1985-12-20'),
('Pedro Oliveira', 'pedro@email.com', '(11) 77777-7777', 'Rua da Paz, 789', '1992-03-10');