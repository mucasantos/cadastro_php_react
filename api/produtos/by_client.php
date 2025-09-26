<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/Produto.php';

$database = new Database();
$db = $database->getConnection();

$produto = new Produto($db);

$cliente_id = isset($_GET['cliente_id']) ? $_GET['cliente_id'] : die();

$stmt = $produto->getProductsByClient($cliente_id);
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
            "preco" => floatval($preco),
            "status" => $status,
            "data_associacao" => $data_associacao
        );

        array_push($produtos_arr["records"], $produto_item);
    }

    http_response_code(200);
    echo json_encode($produtos_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Nenhum produto associado a este cliente."));
}
?>