<?php
/**
 * Script para corrigir a estrutura do banco de dados
 * Execute este arquivo uma vez para garantir que todas as tabelas estejam corretas
 */

include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    die("Erro: Não foi possível conectar ao banco de dados.");
}

echo "<h2>Corrigindo estrutura do banco de dados...</h2>";

try {
    // 1. Verificar e adicionar campo status na tabela clientes
    echo "<h3>1. Verificando tabela clientes...</h3>";
    
    $checkStatusClientes = $db->query("SHOW COLUMNS FROM clientes LIKE 'status'");
    if ($checkStatusClientes->rowCount() == 0) {
        echo "- Adicionando campo 'status' na tabela clientes...<br>";
        $db->exec("ALTER TABLE clientes ADD COLUMN status ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo'");
        $db->exec("UPDATE clientes SET status = 'ativo' WHERE status IS NULL OR status = ''");
        echo "✅ Campo 'status' adicionado com sucesso!<br>";
    } else {
        echo "✅ Campo 'status' já existe na tabela clientes.<br>";
    }

    // 2. Criar tabela produtos se não existir
    echo "<h3>2. Verificando tabela produtos...</h3>";
    
    $checkProdutos = $db->query("SHOW TABLES LIKE 'produtos'");
    if ($checkProdutos->rowCount() == 0) {
        echo "- Criando tabela produtos...<br>";
        $createProdutos = "
        CREATE TABLE produtos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            descricao TEXT,
            preco DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            status ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
            data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        $db->exec($createProdutos);
        echo "✅ Tabela produtos criada com sucesso!<br>";
        
        // Inserir produtos de exemplo
        echo "- Inserindo produtos de exemplo...<br>";
        $insertProdutos = "
        INSERT INTO produtos (nome, descricao, preco, status) VALUES
        ('Notebook Dell Inspiron', 'Notebook Dell Inspiron 15 3000, Intel Core i5, 8GB RAM, 256GB SSD', 2499.99, 'ativo'),
        ('Mouse Logitech MX Master', 'Mouse sem fio Logitech MX Master 3, sensor de alta precisão', 349.90, 'ativo'),
        ('Teclado Mecânico Corsair', 'Teclado mecânico Corsair K95 RGB, switches Cherry MX', 899.99, 'ativo'),
        ('Monitor LG UltraWide', 'Monitor LG UltraWide 29\" IPS, resolução 2560x1080', 1299.99, 'ativo'),
        ('Webcam Logitech C920', 'Webcam Logitech C920 HD Pro, 1080p, microfone integrado', 299.99, 'inativo')";
        $db->exec($insertProdutos);
        echo "✅ Produtos de exemplo inseridos!<br>";
    } else {
        echo "✅ Tabela produtos já existe.<br>";
    }

    // 3. Criar tabela cliente_produtos se não existir
    echo "<h3>3. Verificando tabela cliente_produtos...</h3>";
    
    $checkClienteProdutos = $db->query("SHOW TABLES LIKE 'cliente_produtos'");
    if ($checkClienteProdutos->rowCount() == 0) {
        echo "- Criando tabela cliente_produtos...<br>";
        $createClienteProdutos = "
        CREATE TABLE cliente_produtos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            cliente_id INT NOT NULL,
            produto_id INT NOT NULL,
            data_associacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
            FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
            UNIQUE KEY unique_cliente_produto (cliente_id, produto_id)
        )";
        $db->exec($createClienteProdutos);
        echo "✅ Tabela cliente_produtos criada com sucesso!<br>";
    } else {
        echo "✅ Tabela cliente_produtos já existe.<br>";
    }

    // 4. Verificar se há clientes sem status definido
    echo "<h3>4. Verificando dados dos clientes...</h3>";
    $updateClientes = $db->exec("UPDATE clientes SET status = 'ativo' WHERE status IS NULL OR status = ''");
    if ($updateClientes > 0) {
        echo "✅ Atualizados $updateClientes clientes para status 'ativo'.<br>";
    } else {
        echo "✅ Todos os clientes já possuem status definido.<br>";
    }

    echo "<h2 style='color: green;'>✅ Estrutura do banco de dados corrigida com sucesso!</h2>";
    echo "<p><strong>Próximos passos:</strong></p>";
    echo "<ul>";
    echo "<li>Acesse o <a href='index.php'>sistema principal</a></li>";
    echo "<li>Teste o dashboard para ver se os cards aparecem</li>";
    echo "<li>Teste o cadastro de clientes com email duplicado</li>";
    echo "</ul>";

} catch (PDOException $e) {
    echo "<h2 style='color: red;'>❌ Erro ao corrigir estrutura do banco:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>