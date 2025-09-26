-- Criar tabela de produtos
CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    status ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Criar tabela de relacionamento cliente_produtos (muitos para muitos)
CREATE TABLE IF NOT EXISTS cliente_produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    produto_id INT NOT NULL,
    data_associacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cliente_produto (cliente_id, produto_id)
);

-- Inserir alguns produtos de exemplo
INSERT INTO produtos (nome, descricao, preco, status) VALUES
('Notebook Dell Inspiron', 'Notebook Dell Inspiron 15 3000, Intel Core i5, 8GB RAM, 256GB SSD', 2499.99, 'ativo'),
('Mouse Logitech MX Master', 'Mouse sem fio Logitech MX Master 3, sensor de alta precisão', 349.90, 'ativo'),
('Teclado Mecânico Corsair', 'Teclado mecânico Corsair K95 RGB, switches Cherry MX', 899.99, 'ativo'),
('Monitor LG UltraWide', 'Monitor LG UltraWide 29" IPS, resolução 2560x1080', 1299.99, 'ativo'),
('Webcam Logitech C920', 'Webcam Logitech C920 HD Pro, 1080p, microfone integrado', 299.99, 'inativo');