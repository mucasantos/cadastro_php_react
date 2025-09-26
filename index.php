<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateX(0);
            transition: transform 0.3s ease;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar.active {
            transform: translateX(0);
        }
        
        /* Mobile: esconder sidebar por padrão */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
        }
        
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header h3 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-menu a {
            display: block;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: rgba(255,255,255,0.1);
            padding-left: 30px;
        }
        
        .sidebar-menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
            padding: 0 15px;
        }
        
        /* Mobile: sem margem por padrão */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
            
            .main-content.sidebar-open {
                margin-left: 0;
            }
        }
        
        .main-content.sidebar-open {
            margin-left: 250px;
        }
        
        /* Top Bar */
        .top-bar {
            background: white;
            padding: 15px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .menu-toggle {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #667eea;
            cursor: pointer;
            margin-right: 15px;
        }
        
        /* Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        /* Responsive */
        @media (min-width: 768px) {
            .sidebar {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 250px;
            }
            
            .sidebar-overlay {
                display: none;
            }
            
            .menu-toggle {
                display: none;
            }
        }
        
        @media (max-width: 767px) {
            .main-content.sidebar-open {
                margin-left: 0;
            }
        }
        
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
        .btn-primary {
            background-color: #667eea;
            border-color: #667eea;
        }
        .table th {
            background-color: #e9ecef;
        }
        .loading {
            display: none;
        }
        
        /* Hide tabs navigation */
        .nav-tabs {
            display: none;
        }
        
        /* Content sections */
        .content-section {
            display: none;
        }
        
        .content-section.active {
            display: block;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-cogs"></i> Sistema</h3>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="#" class="menu-item" data-section="dashboard">
                    <i class="fas fa-chart-bar"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="#" class="menu-item" data-section="clientes">
                    <i class="fas fa-users"></i> Clientes
                </a>
            </li>
            <li>
                <a href="#" class="menu-item" data-section="produtos">
                    <i class="fas fa-box"></i> Produtos
                </a>
            </li>
            <li>
                <a href="#" class="menu-item" data-section="relatorios">
                    <i class="fas fa-file-alt"></i> Relatórios
                </a>
            </li>
            <li>
                <a href="#" class="menu-item" data-section="configuracoes">
                    <i class="fas fa-cog"></i> Configurações
                </a>
            </li>
        </ul>
    </div>
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Bar -->
        <div class="top-bar">
            <button class="menu-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <h4>Dashboard</h4>
        </div>
        
        <div class="container-fluid">
            <!-- Clientes Section -->
            <div class="content-section" id="clientes-content">
                <!-- Botão para abrir modal de cadastro -->
                <div class="row mb-4">
                    <div class="col-12">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#clienteModal">
                            <i class="fas fa-user-plus"></i> Novo Cliente
                        </button>
                    </div>
                </div>

                <!-- Busca -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="searchInput" placeholder="Buscar por nome ou email...">
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" id="statusFilter">
                                            <option value="">Todos os Status</option>
                                            <option value="ativo">Ativo</option>
                                            <option value="inativo">Inativo</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-outline-primary w-100" id="searchBtn">
                                            <i class="fas fa-search"></i> Buscar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

        <!-- Lista de Clientes -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list"></i> Lista de Clientes
                        </h5>
                        <button class="btn btn-outline-primary btn-sm" id="refreshBtn">
                            <i class="fas fa-sync-alt"></i> Atualizar
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="loading text-center">
                            <i class="fas fa-spinner fa-spin"></i> Carregando...
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Telefone</th>
                                        <th>Data Nascimento</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="clientesTable">
                                    <!-- Dados serão carregados via JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Gerenciar Produtos do Cliente -->
        <div class="modal fade" id="produtosClienteModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-boxes"></i> Produtos do Cliente: <span id="clienteNomeModal"></span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Produtos Associados -->
                        <div class="mb-4">
                            <h6><i class="fas fa-check-circle text-success"></i> Produtos Associados</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Preço</th>
                                            <th>Data Associação</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody id="produtosAssociadosTable">
                                        <!-- Dados carregados via JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Produtos Disponíveis -->
                        <div>
                            <h6><i class="fas fa-plus-circle text-primary"></i> Associar Novos Produtos</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Descrição</th>
                                            <th>Preço</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody id="produtosDisponiveisTable">
                                        <!-- Dados carregados via JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
            </div>
            
            <!-- Produtos Section -->
            <div class="content-section" id="produtos-content">
                <div class="container-fluid">
                    <!-- Botão para abrir modal de cadastro -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#produtoModal">
                                <i class="fas fa-box-open"></i> Novo Produto
                            </button>
                        </div>
                    </div>

                    <!-- Busca de Produtos -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" id="produto-searchInput" placeholder="Buscar por nome...">
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-control" id="produto-statusFilter">
                                                <option value="">Todos os Status</option>
                                                <option value="ativo">Ativo</option>
                                                <option value="inativo">Inativo</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-primary" id="produto-searchBtn">
                                                <i class="fas fa-search"></i> Buscar
                                            </button>
                                            <button type="button" class="btn btn-secondary" id="produto-clearBtn">
                                                <i class="fas fa-eraser"></i> Limpar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Lista de Produtos -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-list"></i> Lista de Produtos
                                </h5>
                                <div class="loading" id="produto-loading">
                                    <i class="fas fa-spinner fa-spin"></i> Carregando...
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nome</th>
                                                <th>Descrição</th>
                                                <th>Preço</th>
                                                <th>Status</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody id="produtosTable">
                                            <!-- Dados serão carregados via JavaScript -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Dashboard Section -->
            <div class="content-section active" id="dashboard-content">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-chart-bar"></i> Dashboard
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Cards de Estatísticas -->
                                <div class="row mb-4">
                                    <div class="col-md-3 mb-3">
                                        <div class="card bg-primary text-white">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <h4 id="totalClientes">0</h4>
                                                        <p class="mb-0">Total Clientes</p>
                                                    </div>
                                                    <div class="align-self-center">
                                                        <i class="fas fa-users fa-2x"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="card bg-success text-white">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <h4 id="totalProdutos">0</h4>
                                                        <p class="mb-0">Total Produtos</p>
                                                    </div>
                                                    <div class="align-self-center">
                                                        <i class="fas fa-box fa-2x"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="card bg-info text-white">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <h4 id="clientesAtivos">0</h4>
                                                        <p class="mb-0">Clientes Ativos</p>
                                                    </div>
                                                    <div class="align-self-center">
                                                        <i class="fas fa-user-check fa-2x"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="card bg-warning text-white">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <h4 id="produtosAtivos">0</h4>
                                                        <p class="mb-0">Produtos Ativos</p>
                                                    </div>
                                                    <div class="align-self-center">
                                                        <i class="fas fa-box-open fa-2x"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ações Rápidas -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-bolt"></i> Ações Rápidas
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <button class="btn btn-primary btn-lg w-100" id="dashboardNovoCliente">
                                                            <i class="fas fa-user-plus"></i>
                                                            <div class="mt-1">
                                                                <strong>Novo Cliente</strong>
                                                                <br><small>Cadastrar novo cliente</small>
                                                            </div>
                                                        </button>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <button class="btn btn-success btn-lg w-100" id="dashboardNovoProduto">
                                                            <i class="fas fa-plus-circle"></i>
                                                            <div class="mt-1">
                                                                <strong>Novo Produto</strong>
                                                                <br><small>Cadastrar novo produto</small>
                                                            </div>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Relatórios Section -->
            <div class="content-section" id="relatorios-content">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-file-alt"></i> Relatórios
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Seção de relatórios em desenvolvimento...</p>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6><i class="fas fa-users"></i> Relatório de Clientes</h6>
                                                <p class="text-muted">Gerar relatório completo de clientes</p>
                                                <button class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-download"></i> Gerar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6><i class="fas fa-box"></i> Relatório de Produtos</h6>
                                                <p class="text-muted">Gerar relatório completo de produtos</p>
                                                <button class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-download"></i> Gerar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Configurações Section -->
            <div class="content-section" id="configuracoes-content">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-cog"></i> Configurações
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Seção de configurações em desenvolvimento...</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Configurações Gerais</h6>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="notificacoes">
                                            <label class="form-check-label" for="notificacoes">
                                                Receber notificações
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="modoEscuro">
                                            <label class="form-check-label" for="modoEscuro">
                                                Modo escuro
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Backup</h6>
                                        <button class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-download"></i> Fazer Backup
                                        </button>
                                        <button class="btn btn-outline-warning btn-sm ms-2">
                                            <i class="fas fa-upload"></i> Restaurar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Cliente -->
    <div class="modal fade" id="clienteModal" tabindex="-1" aria-labelledby="clienteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clienteModalLabel">
                        <i class="fas fa-user-plus"></i> 
                        <span id="form-title">Cadastrar Cliente</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="clienteForm">
                        <input type="hidden" id="clienteId">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nome" class="form-label">Nome *</label>
                                <input type="text" class="form-control" id="nome" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control" id="telefone">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                                <input type="date" class="form-control" id="data_nascimento">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="endereco" class="form-label">Endereço</label>
                                <textarea class="form-control" id="endereco" rows="3"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-control" id="status" required>
                                    <option value="ativo">Ativo</option>
                                    <option value="inativo">Inativo</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" form="clienteForm" class="btn btn-primary">
                        <i class="fas fa-save"></i> <span id="btn-text">Salvar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Produto -->
    <div class="modal fade" id="produtoModal" tabindex="-1" aria-labelledby="produtoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="produtoModalLabel">
                        <i class="fas fa-box-open"></i> 
                        <span id="produto-form-title">Cadastrar Produto</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="produtoForm">
                        <input type="hidden" id="produtoId">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="produto-nome" class="form-label">Nome *</label>
                                <input type="text" class="form-control" id="produto-nome" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="produto-preco" class="form-label">Preço *</label>
                                <input type="number" step="0.01" class="form-control" id="produto-preco" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="produto-descricao" class="form-label">Descrição</label>
                                <textarea class="form-control" id="produto-descricao" rows="3"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="produto-status" class="form-label">Status *</label>
                                <select class="form-control" id="produto-status" required>
                                    <option value="ativo">Ativo</option>
                                    <option value="inativo">Inativo</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" form="produtoForm" class="btn btn-primary">
                        <i class="fas fa-save"></i> <span id="produto-btn-text">Salvar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</html>