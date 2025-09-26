<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/Cliente.php';

$database = new Database();
$db = $database->getConnection();

$cliente = new Cliente($db);

$stmt = $cliente->read();
$num = $stmt->rowCount();

if($num > 0){
    $clientes_arr = array();
    $clientes_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $cliente_item = array(
            "id" => $id,
            "nome" => $nome,
            "email" => $email,
            "telefone" => $telefone,
            "endereco" => $endereco,
            "data_nascimento" => $data_nascimento,
            "status" => $status,
            "created_at" => $created_at,
            "updated_at" => $updated_at
        );

        array_push($clientes_arr["records"], $cliente_item);
    }

    http_response_code(200);
    echo json_encode($clientes_arr);
}
else{
    http_response_code(404);
    echo json_encode(
        array("message" => "Nenhum cliente encontrado.")
    );
}
?>