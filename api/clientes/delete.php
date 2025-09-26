<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/Cliente.php';

$database = new Database();
$db = $database->getConnection();

$cliente = new Cliente($db);

$data = json_decode(file_get_contents("php://input"));

$cliente->id = $data->id;

if($cliente->delete()){
    http_response_code(200);
    echo json_encode(array("message" => "Cliente deletado com sucesso."));
}
else{
    http_response_code(503);
    echo json_encode(array("message" => "Não foi possível deletar o cliente."));
}
?>