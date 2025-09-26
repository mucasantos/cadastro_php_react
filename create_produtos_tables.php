<?php
require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    echo "<h2>Criando tabelas de produtos...</h2>";
    
    // Ler e executar o arquivo SQL
    $sql = file_get_contents('sql/create_produtos_tables.sql');
    
    // Dividir as queries por ponto e vírgula
    $queries = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($queries as $query) {
        if (!empty($query)) {
            try {
                $db->exec($query);
                echo "<p style='color: green;'>✓ Query executada com sucesso</p>";
            } catch (PDOException $e) {
                echo "<p style='color: orange;'>⚠ Query: " . $e->getMessage() . "</p>";
            }
        }
    }
    
    echo "<h3>Verificando estrutura da tabela produtos:</h3>";
    $stmt = $db->query("DESCRIBE produtos");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Chave</th><th>Padrão</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3>Verificando estrutura da tabela cliente_produtos:</h3>";
    $stmt = $db->query("DESCRIBE cliente_produtos");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Chave</th><th>Padrão</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3>Produtos cadastrados:</h3>";
    $stmt = $db->query("SELECT * FROM produtos");
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($produtos) {
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>ID</th><th>Nome</th><th>Descrição</th><th>Preço</th><th>Status</th></tr>";
        foreach ($produtos as $produto) {
            echo "<tr>";
            echo "<td>" . $produto['id'] . "</td>";
            echo "<td>" . $produto['nome'] . "</td>";
            echo "<td>" . substr($produto['descricao'], 0, 50) . "...</td>";
            echo "<td>R$ " . number_format($produto['preco'], 2, ',', '.') . "</td>";
            echo "<td>" . $produto['status'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<p><strong>Total de produtos: " . count($produtos) . "</strong></p>";
    } else {
        echo "<p>Nenhum produto encontrado.</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erro: " . $e->getMessage() . "</p>";
}
?>