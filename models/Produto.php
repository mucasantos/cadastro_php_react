<?php
class Produto {
    private $conn;
    private $table_name = "produtos";

    // Propriedades do produto
    public $id;
    public $nome;
    public $descricao;
    public $preco;
    public $status;
    public $data_criacao;
    public $data_atualizacao;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Criar produto
    function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET nome=:nome, descricao=:descricao, preco=:preco, status=:status";

        $stmt = $this->conn->prepare($query);

        // Sanitizar dados
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->descricao = htmlspecialchars(strip_tags($this->descricao));
        $this->preco = htmlspecialchars(strip_tags($this->preco));
        $this->status = htmlspecialchars(strip_tags($this->status));

        // Bind dos parâmetros
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":preco", $this->preco);
        $stmt->bindParam(":status", $this->status);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Ler todos os produtos
    function read() {
        $query = "SELECT id, nome, descricao, preco, status, data_criacao, data_atualizacao 
                  FROM " . $this->table_name . " 
                  ORDER BY data_criacao DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Ler um produto específico
    function readOne() {
        $query = "SELECT id, nome, descricao, preco, status, data_criacao, data_atualizacao 
                  FROM " . $this->table_name . " 
                  WHERE id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->nome = $row['nome'];
            $this->descricao = $row['descricao'];
            $this->preco = $row['preco'];
            $this->status = $row['status'];
            $this->data_criacao = $row['data_criacao'];
            $this->data_atualizacao = $row['data_atualizacao'];
            return true;
        }

        return false;
    }

    // Atualizar produto
    function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET nome=:nome, descricao=:descricao, preco=:preco, status=:status 
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Sanitizar dados
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->descricao = htmlspecialchars(strip_tags($this->descricao));
        $this->preco = htmlspecialchars(strip_tags($this->preco));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind dos parâmetros
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":preco", $this->preco);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Deletar produto
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Buscar produtos
    function search($keywords) {
        $query = "SELECT id, nome, descricao, preco, status, data_criacao, data_atualizacao 
                  FROM " . $this->table_name . " 
                  WHERE nome LIKE ? OR descricao LIKE ? 
                  ORDER BY data_criacao DESC";

        $stmt = $this->conn->prepare($query);

        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->execute();

        return $stmt;
    }

    // Ler produtos por status
    function readByStatus($status) {
        $query = "SELECT id, nome, descricao, preco, status, data_criacao, data_atualizacao 
                  FROM " . $this->table_name . " 
                  WHERE status = ? 
                  ORDER BY data_criacao DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $status);
        $stmt->execute();

        return $stmt;
    }

    // Buscar produtos com filtro de status
    function searchWithStatus($keywords, $status) {
        $query = "SELECT id, nome, descricao, preco, status, data_criacao, data_atualizacao 
                  FROM " . $this->table_name . " 
                  WHERE (nome LIKE ? OR descricao LIKE ?) AND status = ? 
                  ORDER BY data_criacao DESC";

        $stmt = $this->conn->prepare($query);

        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $status);
        $stmt->execute();

        return $stmt;
    }

    // Obter produtos associados a um cliente
    function getProductsByClient($cliente_id) {
        $query = "SELECT p.id, p.nome, p.descricao, p.preco, p.status, cp.data_associacao 
                  FROM " . $this->table_name . " p 
                  INNER JOIN cliente_produtos cp ON p.id = cp.produto_id 
                  WHERE cp.cliente_id = ? 
                  ORDER BY cp.data_associacao DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $cliente_id);
        $stmt->execute();

        return $stmt;
    }

    // Associar produto a um cliente
    function associateToClient($cliente_id, $produto_id) {
        $query = "INSERT INTO cliente_produtos (cliente_id, produto_id) VALUES (?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $cliente_id);
        $stmt->bindParam(2, $produto_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Desassociar produto de um cliente
    function dissociateFromClient($cliente_id, $produto_id) {
        $query = "DELETE FROM cliente_produtos WHERE cliente_id = ? AND produto_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $cliente_id);
        $stmt->bindParam(2, $produto_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>