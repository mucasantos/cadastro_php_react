<?php
class Cliente {
    private $conn;
    private $table_name = "clientes";

    public $id;
    public $nome;
    public $email;
    public $telefone;
    public $endereco;
    public $data_nascimento;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Criar cliente
    function create() {
        // Verificar se email já existe
        if($this->emailExists()) {
            return false;
        }

        $query = "INSERT INTO " . $this->table_name . " 
                  SET nome=:nome, email=:email, telefone=:telefone, endereco=:endereco, data_nascimento=:data_nascimento, status=:status";

        $stmt = $this->conn->prepare($query);

        // Sanitizar dados
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->telefone = htmlspecialchars(strip_tags($this->telefone));
        $this->endereco = htmlspecialchars(strip_tags($this->endereco));
        $this->data_nascimento = htmlspecialchars(strip_tags($this->data_nascimento));
        $this->status = htmlspecialchars(strip_tags($this->status));

        // Bind dos parâmetros
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":telefone", $this->telefone);
        $stmt->bindParam(":endereco", $this->endereco);
        $stmt->bindParam(":data_nascimento", $this->data_nascimento);
        $stmt->bindParam(":status", $this->status);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Ler todos os clientes
    function read() {
        $query = "SELECT id, nome, email, telefone, endereco, data_nascimento, status, created_at, updated_at 
                  FROM " . $this->table_name . " 
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Ler um cliente específico
    function readOne() {
        $query = "SELECT id, nome, email, telefone, endereco, data_nascimento, status, created_at, updated_at 
                  FROM " . $this->table_name . " 
                  WHERE id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->nome = $row['nome'];
            $this->email = $row['email'];
            $this->telefone = $row['telefone'];
            $this->endereco = $row['endereco'];
            $this->data_nascimento = $row['data_nascimento'];
            $this->status = $row['status'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            return true;
        }

        return false;
    }

    // Atualizar cliente
    function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET nome = :nome, email = :email, telefone = :telefone, endereco = :endereco, data_nascimento = :data_nascimento, status = :status 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitizar dados
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->telefone = htmlspecialchars(strip_tags($this->telefone));
        $this->endereco = htmlspecialchars(strip_tags($this->endereco));
        $this->data_nascimento = htmlspecialchars(strip_tags($this->data_nascimento));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind dos parâmetros
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':telefone', $this->telefone);
        $stmt->bindParam(':endereco', $this->endereco);
        $stmt->bindParam(':data_nascimento', $this->data_nascimento);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Deletar cliente
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Buscar clientes por nome ou email
    function search($keywords) {
        $query = "SELECT id, nome, email, telefone, endereco, data_nascimento, status, created_at, updated_at 
                  FROM " . $this->table_name . " 
                  WHERE nome LIKE ? OR email LIKE ? 
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);

        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->execute();

        return $stmt;
    }

    // Buscar clientes por status
    function readByStatus($status) {
        $query = "SELECT id, nome, email, telefone, endereco, data_nascimento, status, created_at, updated_at 
                  FROM " . $this->table_name . " 
                  WHERE status = ? 
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $status);
        $stmt->execute();

        return $stmt;
    }

    // Buscar clientes por nome/email e status
    function searchWithStatus($keywords, $status) {
        $query = "SELECT id, nome, email, telefone, endereco, data_nascimento, status, created_at, updated_at 
                  FROM " . $this->table_name . " 
                  WHERE (nome LIKE ? OR email LIKE ?) AND status = ? 
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);

        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $status);
        $stmt->execute();

        return $stmt;
    }

    // Obter produtos associados a este cliente
    function getAssociatedProducts() {
        $query = "SELECT p.id, p.nome, p.descricao, p.preco, p.status, cp.data_associacao
                  FROM produtos p
                  INNER JOIN cliente_produtos cp ON p.id = cp.produto_id
                  WHERE cp.cliente_id = ?
                  ORDER BY cp.data_associacao DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        return $stmt;
    }

    // Associar produto a este cliente
    function associateProduct($produto_id) {
        // Verificar se a associação já existe
        $check_query = "SELECT id FROM cliente_produtos WHERE cliente_id = ? AND produto_id = ?";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(1, $this->id);
        $check_stmt->bindParam(2, $produto_id);
        $check_stmt->execute();

        if ($check_stmt->rowCount() > 0) {
            return false; // Já existe associação
        }

        $query = "INSERT INTO cliente_produtos (cliente_id, produto_id, data_associacao) VALUES (?, ?, NOW())";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);
        $stmt->bindParam(2, $produto_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Desassociar produto deste cliente
    function dissociateProduct($produto_id) {
        $query = "DELETE FROM cliente_produtos WHERE cliente_id = ? AND produto_id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);
        $stmt->bindParam(2, $produto_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Obter produtos não associados a este cliente
    function getAvailableProducts() {
        $query = "SELECT id, nome, descricao, preco, status
                  FROM produtos
                  WHERE id NOT IN (
                      SELECT produto_id FROM cliente_produtos WHERE cliente_id = ?
                  ) AND status = 'ativo'
                  ORDER BY nome ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        return $stmt;
    }

    // Verificar se email já existe
    function emailExists() {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            return true;
        }
        
        return false;
    }
}
?>