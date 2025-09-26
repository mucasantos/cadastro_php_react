<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/Cliente.php';

$database = new Database();
$db = $database->getConnection();

$cliente = new Cliente($db);

$cliente->id = isset($_GET['id']) ? $_GET['id'] : die();

if($cliente->readOne()){
    $cliente_arr = array(
        "id" => $cliente->id,
        "nome" => $cliente->nome,
        "email" => $cliente->email,
        "telefone" => $cliente->telefone,
        "endereco" => $cliente->endereco,
        "data_nascimento" => $cliente->data_nascimento,
        "created_at" => $cliente->created_at,
        "updated_at" => $cliente->updated_at
    );

    http_response_code(200);
    echo json_encode($cliente_arr);
}
else{
    http_response_code(404);
    echo json_encode(array("message" => "Cliente não encontrado."));
}
?>