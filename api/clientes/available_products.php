<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/Cliente.php';

$database = new Database();
$db = $database->getConnection();

$cliente = new Cliente($db);

// Verificar se o cliente_id foi fornecido
if (!isset($_GET['cliente_id']) || empty($_GET['cliente_id'])) {
    http_response_code(400);
    echo json_encode(array("message" => "ID do cliente é obrigatório."));
    exit();
}

$cliente_id = $_GET['cliente_id'];

// Verificar se o cliente existe
$cliente->id = $cliente_id;
if (!$cliente->readOne()) {
    http_response_code(404);
    echo json_encode(array("message" => "Cliente não encontrado."));
    exit();
}

// Obter produtos disponíveis
$stmt = $cliente->getAvailableProducts();
$num = $stmt->rowCount();

if ($num > 0) {
    $produtos_arr = array();
    $produtos_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $produto_item = array(
            "id" => $id,
            "nome" => $nome,
            "descricao" => $descricao,
            "preco" => $preco,
            "status" => $status
        );

        array_push($produtos_arr["records"], $produto_item);
    }

    http_response_code(200);
    echo json_encode($produtos_arr);
} else {
    http_response_code(200);
    echo json_encode(array("message" => "Nenhum produto disponível encontrado."));
}
?>