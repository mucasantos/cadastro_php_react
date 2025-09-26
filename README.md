# CRUD de Clientes - PHP + MySQL

Sistema completo de gerenciamento de clientes com API REST e interface web.

## 📋 Funcionalidades

- ✅ CRUD completo de clientes (Create, Read, Update, Delete)
- ✅ API REST para consumo mobile
- ✅ Interface web responsiva
- ✅ Busca por nome ou email
- ✅ Validação de dados
- ✅ Tratamento de erros

## 🛠️ Tecnologias Utilizadas

- **Backend**: PHP 7.4+
- **Banco de Dados**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Framework CSS**: Bootstrap 5
- **Ícones**: Font Awesome 6

## 📁 Estrutura do Projeto

```
php_crud/
├── api/
│   └── clientes/
│       ├── create.php      # Criar cliente
│       ├── read.php        # Listar clientes
│       ├── read_one.php    # Buscar cliente por ID
│       ├── update.php      # Atualizar cliente
│       ├── delete.php      # Deletar cliente
│       └── search.php      # Buscar clientes
├── assets/
│   └── js/
│       └── app.js          # JavaScript do frontend
├── config/
│   └── database.php        # Configuração do banco
├── models/
│   └── Cliente.php         # Modelo de dados
├── sql/
│   └── create_database.sql # Script de criação do banco
├── .htaccess              # Configurações Apache
├── index.php              # Interface principal
└── README.md              # Este arquivo
```

## 🚀 Instalação

### 1. Pré-requisitos

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Apache com mod_rewrite habilitado
- Extensões PHP: PDO, PDO_MySQL

### 2. Configuração do Banco de Dados

1. Acesse o MySQL:
```bash
mysql -u root -p
```

2. Execute o script SQL:
```bash
mysql -u root -p < sql/create_database.sql
```

Ou importe manualmente o arquivo `sql/create_database.sql` no seu gerenciador MySQL.

### 3. Configuração da Aplicação

1. Clone ou baixe o projeto
2. Coloque os arquivos no diretório do seu servidor web (htdocs, www, etc.)
3. Edite o arquivo `config/database.php` com suas credenciais:

```php
private $host = "localhost";
private $db_name = "crud_clientes";
private $username = "seu_usuario";
private $password = "sua_senha";
```

### 4. Configuração do Apache

Certifique-se de que o mod_rewrite está habilitado:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

## 🌐 Uso da Interface Web

Acesse `http://localhost/php_crud/` no seu navegador.

### Funcionalidades da Interface:
- **Cadastrar**: Preencha o formulário e clique em "Salvar"
- **Listar**: Todos os clientes são exibidos na tabela
- **Editar**: Clique no ícone de edição na tabela
- **Excluir**: Clique no ícone de lixeira na tabela
- **Buscar**: Use o campo de busca para encontrar clientes

## 📱 API REST

Base URL: `http://localhost/php_crud/api/clientes/`

### Endpoints Disponíveis

#### 1. Listar Todos os Clientes
```http
GET /api/clientes/read.php
```

**Resposta de Sucesso (200):**
```json
{
  "records": [
    {
      "id": "1",
      "nome": "João Silva",
      "email": "joao@email.com",
      "telefone": "(11) 99999-9999",
      "endereco": "Rua das Flores, 123",
      "data_nascimento": "1990-05-15",
      "created_at": "2024-01-15 10:30:00",
      "updated_at": "2024-01-15 10:30:00"
    }
  ]
}
```

#### 2. Buscar Cliente por ID
```http
GET /api/clientes/read_one.php?id=1
```

**Resposta de Sucesso (200):**
```json
{
  "id": "1",
  "nome": "João Silva",
  "email": "joao@email.com",
  "telefone": "(11) 99999-9999",
  "endereco": "Rua das Flores, 123",
  "data_nascimento": "1990-05-15",
  "created_at": "2024-01-15 10:30:00",
  "updated_at": "2024-01-15 10:30:00"
}
```

#### 3. Criar Cliente
```http
POST /api/clientes/create.php
Content-Type: application/json

{
  "nome": "Maria Santos",
  "email": "maria@email.com",
  "telefone": "(11) 88888-8888",
  "endereco": "Av. Principal, 456",
  "data_nascimento": "1985-12-20"
}
```

**Resposta de Sucesso (201):**
```json
{
  "message": "Cliente criado com sucesso."
}
```

#### 4. Atualizar Cliente
```http
PUT /api/clientes/update.php
Content-Type: application/json

{
  "id": 1,
  "nome": "João Silva Santos",
  "email": "joao.santos@email.com",
  "telefone": "(11) 99999-9999",
  "endereco": "Rua das Flores, 123",
  "data_nascimento": "1990-05-15"
}
```

**Resposta de Sucesso (200):**
```json
{
  "message": "Cliente atualizado com sucesso."
}
```

#### 5. Deletar Cliente
```http
DELETE /api/clientes/delete.php
Content-Type: application/json

{
  "id": 1
}
```

**Resposta de Sucesso (200):**
```json
{
  "message": "Cliente deletado com sucesso."
}
```

#### 6. Buscar Clientes
```http
GET /api/clientes/search.php?s=joão
```

**Resposta de Sucesso (200):**
```json
{
  "records": [
    {
      "id": "1",
      "nome": "João Silva",
      "email": "joao@email.com",
      "telefone": "(11) 99999-9999",
      "endereco": "Rua das Flores, 123",
      "data_nascimento": "1990-05-15",
      "created_at": "2024-01-15 10:30:00",
      "updated_at": "2024-01-15 10:30:00"
    }
  ]
}
```

### Códigos de Status HTTP

- **200**: Sucesso
- **201**: Criado com sucesso
- **400**: Dados inválidos
- **404**: Não encontrado
- **503**: Erro no servidor

### Headers CORS

A API está configurada para aceitar requisições de qualquer origem:
```
Access-Control-Allow-Origin: *
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
Access-Control-Allow-Headers: Content-Type, Authorization
```

## 📱 Exemplo de Uso Mobile (JavaScript)

```javascript
// Listar clientes
fetch('http://localhost/php_crud/api/clientes/read.php')
  .then(response => response.json())
  .then(data => console.log(data));

// Criar cliente
fetch('http://localhost/php_crud/api/clientes/create.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    nome: 'Novo Cliente',
    email: 'novo@email.com',
    telefone: '(11) 99999-9999'
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

## 🔒 Segurança

- Sanitização de dados de entrada
- Prepared statements para prevenir SQL Injection
- Validação de email
- Headers de segurança configurados

## 🐛 Solução de Problemas

### Erro de Conexão com Banco
- Verifique as credenciais em `config/database.php`
- Certifique-se de que o MySQL está rodando
- Verifique se o banco `crud_clientes` existe

### Erro 404 na API
- Verifique se o mod_rewrite está habilitado
- Confirme se o arquivo `.htaccess` está presente
- Verifique as permissões dos arquivos

### Erro de CORS
- Os headers CORS já estão configurados
- Se necessário, ajuste em cada arquivo da API

## 📄 Licença

Este projeto é de uso livre para fins educacionais e comerciais.

## 👨‍💻 Desenvolvedor

Desenvolvido como exemplo de CRUD completo em PHP com MySQL.# cadastro_php_react
