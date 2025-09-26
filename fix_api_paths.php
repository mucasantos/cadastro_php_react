<?php
/**
 * Script para corrigir caminhos dos includes nas APIs
 */

$apiFiles = [
    'api/clientes/search.php',
    'api/clientes/available_products.php', 
    'api/clientes/update.php',
    'api/clientes/delete.php',
    'api/clientes/read_one.php',
    'api/produtos/read_one.php',
    'api/produtos/create.php',
    'api/produtos/associate.php',
    'api/produtos/by_client.php',
    'api/produtos/dissociate.php',
    'api/produtos/delete.php',
    'api/produtos/search.php',
    'api/produtos/update.php'
];

echo "<h2>Corrigindo caminhos dos includes nas APIs...</h2>";

foreach ($apiFiles as $file) {
    if (file_exists($file)) {
        echo "<p>Corrigindo: $file</p>";
        
        $content = file_get_contents($file);
        
        // Corrigir includes
        $content = str_replace(
            "include_once '../../config/database.php';",
            "include_once __DIR__ . '/../../config/database.php';",
            $content
        );
        
        $content = str_replace(
            "include_once '../../models/Cliente.php';",
            "include_once __DIR__ . '/../../models/Cliente.php';",
            $content
        );
        
        $content = str_replace(
            "include_once '../../models/Produto.php';",
            "include_once __DIR__ . '/../../models/Produto.php';",
            $content
        );
        
        file_put_contents($file, $content);
        echo "<span style='color: green;'>✅ Corrigido</span><br>";
    } else {
        echo "<p style='color: red;'>❌ Arquivo não encontrado: $file</p>";
    }
}

echo "<h2 style='color: green;'>✅ Todos os caminhos corrigidos!</h2>";
echo "<p><strong>Próximo passo:</strong> Teste o dashboard agora!</p>";
?>