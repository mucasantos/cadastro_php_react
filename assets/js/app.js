// Classe para gerenciar o menu lateral
class SidebarManager {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.setActiveSection('dashboard'); // Seção inicial
    }

    bindEvents() {
        // Toggle do menu lateral
        const sidebarToggle = document.getElementById('sidebarToggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                this.toggleSidebar();
            });
        }

        // Links do menu
        const menuLinks = document.querySelectorAll('.sidebar-menu a');
        menuLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const sectionId = link.getAttribute('data-section');
                this.setActiveSection(sectionId);
            });
        });

        // Fechar sidebar ao clicar fora (mobile)
        document.addEventListener('click', (e) => {
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !sidebarToggle.contains(e.target) &&
                sidebar.classList.contains('active')) {
                this.closeSidebar();
            }
        });

        // Responsividade
        window.addEventListener('resize', () => {
            this.handleResize();
        });
    }

    toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        
        sidebar.classList.toggle('active');
        mainContent.classList.toggle('sidebar-open');
    }

    closeSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        
        sidebar.classList.remove('active');
        mainContent.classList.remove('sidebar-open');
    }

    setActiveSection(sectionId) {
        console.log('🔄 Mudando para seção:', sectionId);
        
        // Remover classe active de todos os links
        const menuLinks = document.querySelectorAll('.sidebar-menu a');
        console.log('📋 Links encontrados:', menuLinks.length);
        menuLinks.forEach(link => {
            link.classList.remove('active');
        });

        // Adicionar classe active ao link selecionado
        const activeLink = document.querySelector(`[data-section="${sectionId}"]`);
        if (activeLink) {
            activeLink.classList.add('active');
            console.log('✅ Link ativado:', activeLink.textContent.trim());
        } else {
            console.error('❌ Link não encontrado para seção:', sectionId);
        }

        // Remover classe active de todas as seções
        const sections = document.querySelectorAll('.content-section');
        console.log('📄 Seções encontradas:', sections.length);
        sections.forEach(section => {
            section.classList.remove('active');
            console.log('🙈 Removendo active da seção:', section.id);
        });

        // Adicionar classe active à seção selecionada
        const activeSection = document.getElementById(`${sectionId}-content`);
        if (activeSection) {
            activeSection.classList.add('active');
            console.log('👁️ Adicionando active à seção:', activeSection.id);
        } else {
            console.error('❌ Seção não encontrada:', `${sectionId}-content`);
        }

        // Atualizar título da página
        this.updatePageTitle(sectionId);

        // Carregar dados específicos da seção
        this.loadSectionData(sectionId);

        // Fechar sidebar em mobile após seleção
        if (window.innerWidth <= 768) {
            this.closeSidebar();
        }
    }

    updatePageTitle(sectionId) {
        const titles = {
            'dashboard': 'Dashboard',
            'clientes': 'Clientes',
            'produtos': 'Produtos',
            'relatorios': 'Relatórios',
            'configuracoes': 'Configurações'
        };

        const pageTitle = document.querySelector('.top-bar h4');
        if (pageTitle) {
            pageTitle.textContent = titles[sectionId] || 'Sistema';
        }
    }

    loadSectionData(sectionId) {
        switch (sectionId) {
            case 'dashboard':
                this.loadDashboardData();
                break;
            case 'clientes':
                if (window.clienteManager) {
                    window.clienteManager.loadClientes();
                }
                break;
            case 'produtos':
                if (window.produtoManager) {
                    window.produtoManager.loadProdutos();
                }
                break;
        }
    }

    async loadDashboardData() {
        try {
            // Aguardar um pouco para garantir que o DOM está pronto
            await new Promise(resolve => setTimeout(resolve, 200));
            
            // Verificar se estamos na seção dashboard
            const dashboardSection = document.getElementById('dashboard-content');
            if (!dashboardSection || dashboardSection.style.display === 'none') {
                return;
            }

            const [clientesResponse, produtosResponse] = await Promise.all([
                fetch('api/clientes/read.php'),
                fetch('api/produtos/read.php')
            ]);

            let totalClientes = 0, totalProdutos = 0, clientesAtivos = 0, produtosAtivos = 0;

            // Processar dados dos clientes
            if (clientesResponse.ok) {
                const clientesData = await clientesResponse.json();
                const clientes = Array.isArray(clientesData) ? clientesData : (clientesData.records || []);
                totalClientes = clientes.length;
                clientesAtivos = clientes.filter(c => c.status === 'ativo').length;
            }

            // Processar dados dos produtos
            if (produtosResponse.ok) {
                const produtosData = await produtosResponse.json();
                const produtos = Array.isArray(produtosData) ? produtosData : (produtosData.records || []);
                totalProdutos = produtos.length;
                produtosAtivos = produtos.filter(p => p.status === 'ativo').length;
            }

            // Atualizar elementos do DOM com verificação de existência
            this.updateDashboardElement('totalClientes', totalClientes);
            this.updateDashboardElement('totalProdutos', totalProdutos);
            this.updateDashboardElement('clientesAtivos', clientesAtivos);
            this.updateDashboardElement('produtosAtivos', produtosAtivos);

        } catch (error) {
            console.error('Erro ao carregar dados do dashboard:', error);
            // Mostrar valores padrão em caso de erro
            this.updateDashboardElement('totalClientes', 0);
            this.updateDashboardElement('totalProdutos', 0);
            this.updateDashboardElement('clientesAtivos', 0);
            this.updateDashboardElement('produtosAtivos', 0);
        }
    }

    updateDashboardElement(elementId, value) {
        const element = document.getElementById(elementId);
        if (element) {
            element.textContent = value;
        }
    }

    handleResize() {
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        
        if (window.innerWidth > 768) {
            // Desktop: sempre mostrar sidebar
            sidebar.classList.add('active');
            mainContent.classList.add('sidebar-open');
        } else {
            // Mobile: esconder sidebar por padrão
            sidebar.classList.remove('active');
            mainContent.classList.remove('sidebar-open');
        }
    }
}

class ClienteManager {
    constructor() {
        this.apiUrl = 'api/clientes/';
        this.isEditing = false;
        this.editingId = null;
        this.init();
    }

    // Gerenciar produtos do cliente
    async gerenciarProdutos(clienteId, clienteNome) {
        this.currentClienteId = clienteId;
        document.getElementById('clienteNomeModal').textContent = clienteNome;
        
        // Carregar produtos associados e disponíveis
        await this.loadProdutosAssociados(clienteId);
        await this.loadProdutosDisponiveis(clienteId);
        
        // Mostrar modal
        const modal = new bootstrap.Modal(document.getElementById('produtosClienteModal'));
        modal.show();
    }

    async loadProdutosAssociados(clienteId) {
        try {
            const response = await fetch(`api/produtos/by_client.php?cliente_id=${clienteId}`);
            const data = await response.json();

            const tbody = document.getElementById('produtosAssociadosTable');
            
            if (data.records && data.records.length > 0) {
                tbody.innerHTML = data.records.map(produto => `
                    <tr>
                        <td>${produto.nome}</td>
                        <td>R$ ${parseFloat(produto.preco).toFixed(2)}</td>
                        <td>${new Date(produto.data_associacao).toLocaleDateString('pt-BR')}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger" onclick="clienteManager.desassociarProduto(${produto.id})">
                                <i class="fas fa-unlink"></i> Remover
                            </button>
                        </td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            <i class="fas fa-info-circle"></i> Nenhum produto associado
                        </td>
                    </tr>
                `;
            }
        } catch (error) {
            console.error('Erro ao carregar produtos associados:', error);
        }
    }

    async loadProdutosDisponiveis(clienteId) {
        try {
            // Usar o método do Cliente.php para obter produtos disponíveis
            const response = await fetch(`api/clientes/available_products.php?cliente_id=${clienteId}`);
            const data = await response.json();

            const tbody = document.getElementById('produtosDisponiveisTable');
            
            if (data.records && data.records.length > 0) {
                tbody.innerHTML = data.records.map(produto => `
                    <tr>
                        <td>${produto.nome}</td>
                        <td>${produto.descricao || '-'}</td>
                        <td>R$ ${parseFloat(produto.preco).toFixed(2)}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-success" onclick="clienteManager.associarProduto(${produto.id})">
                                <i class="fas fa-link"></i> Associar
                            </button>
                        </td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            <i class="fas fa-info-circle"></i> Todos os produtos já estão associados
                        </td>
                    </tr>
                `;
            }
        } catch (error) {
            console.error('Erro ao carregar produtos disponíveis:', error);
        }
    }

    async associarProduto(produtoId) {
        try {
            const response = await fetch('api/produtos/associate.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    cliente_id: this.currentClienteId,
                    produto_id: produtoId
                })
            });

            const data = await response.json();

            if (response.ok) {
                this.showAlert(data.message, 'success');
                // Recarregar as listas
                await this.loadProdutosAssociados(this.currentClienteId);
                await this.loadProdutosDisponiveis(this.currentClienteId);
            } else {
                this.showAlert(data.message, 'error');
            }
        } catch (error) {
            console.error('Erro ao associar produto:', error);
            this.showAlert('Erro ao associar produto', 'error');
        }
    }

    async desassociarProduto(produtoId) {
        if (!confirm('Tem certeza que deseja remover esta associação?')) {
            return;
        }

        try {
            const response = await fetch('api/produtos/dissociate.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    cliente_id: this.currentClienteId,
                    produto_id: produtoId
                })
            });

            const data = await response.json();

            if (response.ok) {
                this.showAlert(data.message, 'success');
                // Recarregar as listas
                await this.loadProdutosAssociados(this.currentClienteId);
                await this.loadProdutosDisponiveis(this.currentClienteId);
            } else {
                this.showAlert(data.message, 'error');
            }
        } catch (error) {
            console.error('Erro ao desassociar produto:', error);
            this.showAlert('Erro ao desassociar produto', 'error');
        }
    }

    init() {
        this.bindEvents();
        this.loadClientes();
    }

    bindEvents() {
        // Formulário
        const clienteForm = document.getElementById('clienteForm');
        if (clienteForm) {
            clienteForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.saveCliente();
            });
        }

        // Botão Novo Cliente
        const novoClienteBtn = document.getElementById('novoClienteBtn');
        if (novoClienteBtn) {
            novoClienteBtn.addEventListener('click', () => {
                this.resetForm();
                const modal = new bootstrap.Modal(document.getElementById('clienteModal'));
                modal.show();
            });
        }

        // Busca
        const searchBtn = document.getElementById('searchBtn');
        if (searchBtn) {
            searchBtn.addEventListener('click', () => {
                this.searchClientes();
            });
        }

        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    this.searchClientes();
                }
            });
        }

        // Filtro de status
        const statusFilter = document.getElementById('statusFilter');
        if (statusFilter) {
            statusFilter.addEventListener('change', () => {
                this.searchClientes();
            });
        }

        // Atualizar
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => {
                this.loadClientes();
            });
        }
    }

    async loadClientes() {
        try {
            this.showLoading(true);
            const response = await fetch(this.apiUrl + 'read.php');
            const data = await response.json();

            if (response.ok && data.records) {
                this.renderClientes(data.records);
            } else {
                this.renderEmptyState();
            }
        } catch (error) {
            console.error('Erro ao carregar clientes:', error);
            this.showAlert('Erro ao carregar clientes', 'danger');
        } finally {
            this.showLoading(false);
        }
    }

    async searchClientes() {
        const searchTerm = document.getElementById('searchInput').value.trim();
        const statusFilter = document.getElementById('statusFilter').value;
        
        // Se não há termo de busca nem filtro de status, carrega todos
        if (!searchTerm && !statusFilter) {
            this.loadClientes();
            return;
        }

        try {
            this.showLoading(true);
            
            // Construir URL com parâmetros
            let url = `${this.apiUrl}search.php?`;
            const params = [];
            
            if (searchTerm) {
                params.push(`s=${encodeURIComponent(searchTerm)}`);
            }
            
            if (statusFilter) {
                params.push(`status=${encodeURIComponent(statusFilter)}`);
            }
            
            url += params.join('&');
            
            const response = await fetch(url);
            const data = await response.json();

            if (response.ok && data.records) {
                this.renderClientes(data.records);
            } else {
                this.renderEmptyState('Nenhum cliente encontrado com os critérios de busca.');
            }
        } catch (error) {
            console.error('Erro ao buscar clientes:', error);
            this.showAlert('Erro ao buscar clientes', 'danger');
        } finally {
            this.showLoading(false);
        }
    }

    async saveCliente() {
        const formData = this.getFormData();
        
        if (!this.validateForm(formData)) {
            return;
        }

        try {
            const url = this.isEditing ? this.apiUrl + 'update.php' : this.apiUrl + 'create.php';
            const method = 'POST';
            
            if (this.isEditing) {
                formData.id = this.editingId;
            }

            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok) {
                this.showAlert(data.message, 'success');
                this.resetForm();
                this.loadClientes();
                // Fechar modal se estiver aberto
                const modal = bootstrap.Modal.getInstance(document.getElementById('clienteModal'));
                if (modal) {
                    modal.hide();
                }
            } else {
                // Garantir que sempre temos uma mensagem de erro
                let errorMessage = 'Erro ao salvar cliente';
                
                try {
                    // Se data.message existe, usar ela
                    if (data && data.message) {
                        errorMessage = data.message;
                    }
                } catch (e) {
                    // Se não conseguir parsear a resposta, usar mensagem padrão
                }

                // Tratamento específico para diferentes tipos de erro
                if (response.status === 409) {
                    this.showAlert(errorMessage || 'Este email já está cadastrado no sistema.', 'warning');
                } else if (response.status === 400) {
                    this.showAlert(errorMessage || 'Dados inválidos. Verifique os campos e tente novamente.', 'danger');
                } else if (response.status === 500 || response.status === 503) {
                    this.showAlert('Erro interno do servidor. Tente novamente mais tarde.', 'danger');
                } else {
                    this.showAlert(errorMessage, 'danger');
                }
            }
        } catch (error) {
            console.error('Erro ao salvar cliente:', error);
            this.showAlert('Erro de conexão. Verifique sua internet e tente novamente.', 'danger');
        }
    }

    async deleteCliente(id) {
        if (!confirm('Tem certeza que deseja deletar este cliente?')) {
            return;
        }

        try {
            const response = await fetch(this.apiUrl + 'delete.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: id })
            });

            const data = await response.json();

            if (response.ok) {
                this.showAlert(data.message, 'success');
                this.loadClientes();
            } else {
                this.showAlert(data.message, 'danger');
            }
        } catch (error) {
            console.error('Erro ao deletar cliente:', error);
            this.showAlert('Erro ao deletar cliente', 'danger');
        }
    }

    editCliente(cliente) {
        this.isEditing = true;
        this.editingId = cliente.id;

        // Preencher formulário do modal
        document.getElementById('clienteId').value = cliente.id;
        document.getElementById('nome').value = cliente.nome;
        document.getElementById('email').value = cliente.email;
        document.getElementById('telefone').value = cliente.telefone || '';
        document.getElementById('endereco').value = cliente.endereco || '';
        document.getElementById('data_nascimento').value = cliente.data_nascimento || '';
        document.getElementById('status').value = cliente.status || 'ativo';

        // Atualizar interface do modal
        document.getElementById('clienteModalLabel').textContent = 'Editar Cliente';
        document.getElementById('btn-text').textContent = 'Atualizar';

        // Abrir modal
        const modal = new bootstrap.Modal(document.getElementById('clienteModal'));
        modal.show();
    }

    resetForm() {
        this.isEditing = false;
        this.editingId = null;

        // Limpar formulário
        document.getElementById('clienteForm').reset();
        document.getElementById('clienteId').value = '';

        // Resetar interface do modal
        document.getElementById('clienteModalLabel').textContent = 'Cadastrar Cliente';
        document.getElementById('btn-text').textContent = 'Salvar';
    }

    getFormData() {
        return {
            nome: document.getElementById('nome').value.trim(),
            email: document.getElementById('email').value.trim(),
            telefone: document.getElementById('telefone').value.trim(),
            endereco: document.getElementById('endereco').value.trim(),
            data_nascimento: document.getElementById('data_nascimento').value || null,
            status: document.getElementById('status').value
        };
    }

    validateForm(data) {
        if (!data.nome || !data.email) {
            this.showAlert('Nome e email são obrigatórios', 'warning');
            return false;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(data.email)) {
            this.showAlert('Email inválido', 'warning');
            return false;
        }

        return true;
    }

    renderClientes(clientes) {
        const tbody = document.getElementById('clientesTable');
        
        if (!clientes || clientes.length === 0) {
            this.renderEmptyState();
            return;
        }

        tbody.innerHTML = clientes.map(cliente => `
            <tr>
                <td>${cliente.id}</td>
                <td>${cliente.nome}</td>
                <td>${cliente.email}</td>
                <td>${cliente.telefone || '-'}</td>
                <td>${cliente.data_nascimento ? new Date(cliente.data_nascimento).toLocaleDateString('pt-BR') : '-'}</td>
                <td>
                    <span class="badge ${cliente.status === 'ativo' ? 'bg-success' : 'bg-secondary'}">
                        ${cliente.status === 'ativo' ? 'Ativo' : 'Inativo'}
                    </span>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-primary me-1" onclick="clienteManager.editCliente(${JSON.stringify(cliente).replace(/"/g, '&quot;')})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-info me-1" onclick="clienteManager.gerenciarProdutos(${cliente.id}, '${cliente.nome}')">
                        <i class="fas fa-boxes"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="clienteManager.deleteCliente(${cliente.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    renderEmptyState(message = 'Nenhum cliente cadastrado.') {
        const tbody = document.getElementById('clientesTable');
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-muted py-4">
                    <i class="fas fa-users fa-2x mb-2"></i><br>
                    ${message}
                </td>
            </tr>
        `;
    }

    showLoading(show) {
        const loading = document.querySelector('.loading');
        const table = document.querySelector('.table-responsive');
        
        if (show) {
            loading.style.display = 'block';
            table.style.display = 'none';
        } else {
            loading.style.display = 'none';
            table.style.display = 'block';
        }
    }

    showAlert(message, type = 'info') {
        // Remove alertas existentes
        const existingAlerts = document.querySelectorAll('.alert');
        existingAlerts.forEach(alert => alert.remove());

        // Criar novo alerta
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.style.position = 'fixed';
        alert.style.top = '20px';
        alert.style.right = '20px';
        alert.style.zIndex = '9999';
        alert.style.minWidth = '300px';
        alert.innerHTML = `
            <strong>${this.getAlertIcon(type)}</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        // Inserir no body
        document.body.appendChild(alert);

        // Auto-remover após 5 segundos
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    }

    getAlertIcon(type) {
        const icons = {
            'success': '<i class="fas fa-check-circle"></i>',
            'danger': '<i class="fas fa-exclamation-triangle"></i>',
            'warning': '<i class="fas fa-exclamation-circle"></i>',
            'info': '<i class="fas fa-info-circle"></i>'
        };
        return icons[type] || icons['info'];
    }
}

class ProdutoManager {
    constructor() {
        this.apiUrl = 'api/produtos/';
        this.isEditing = false;
        this.editingId = null;
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadProdutos();
    }

    bindEvents() {
        // Formulário
        const produtoForm = document.getElementById('produtoForm');
        if (produtoForm) {
            produtoForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.saveProduto();
            });
        }

        // Botão Novo Produto
        const novoProdutoBtn = document.getElementById('novoProdutoBtn');
        if (novoProdutoBtn) {
            novoProdutoBtn.addEventListener('click', () => {
                this.resetForm();
                const modal = new bootstrap.Modal(document.getElementById('produtoModal'));
                modal.show();
            });
        }

        // Busca
        const searchBtn = document.getElementById('produto-searchBtn');
        if (searchBtn) {
            searchBtn.addEventListener('click', () => {
                this.searchProdutos();
            });
        }

        const searchInput = document.getElementById('produto-searchInput');
        if (searchInput) {
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    this.searchProdutos();
                }
            });
        }

        // Filtro de status
        const statusFilter = document.getElementById('produto-statusFilter');
        if (statusFilter) {
            statusFilter.addEventListener('change', () => {
                this.searchProdutos();
            });
        }

        // Limpar busca
        const clearBtn = document.getElementById('produto-clearBtn');
        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                const searchInput = document.getElementById('produto-searchInput');
                const statusFilter = document.getElementById('produto-statusFilter');
                if (searchInput) searchInput.value = '';
                if (statusFilter) statusFilter.value = '';
                this.loadProdutos();
            });
        }
    }

    async loadProdutos() {
        try {
            this.showLoading(true);
            const response = await fetch(this.apiUrl + 'read.php');
            const data = await response.json();

            if (data.records) {
                this.renderProdutos(data.records);
            } else {
                this.renderEmptyState();
            }
        } catch (error) {
            console.error('Erro ao carregar produtos:', error);
            this.showAlert('Erro ao carregar produtos', 'error');
        } finally {
            this.showLoading(false);
        }
    }

    async searchProdutos() {
        try {
            this.showLoading(true);
            const searchTerm = document.getElementById('produto-searchInput').value.trim();
            const statusFilter = document.getElementById('produto-statusFilter').value;

            if (!searchTerm && !statusFilter) {
                this.loadProdutos();
                return;
            }

            let url = this.apiUrl + 'search.php?';
            const params = new URLSearchParams();

            if (searchTerm) {
                params.append('keywords', searchTerm);
            }
            if (statusFilter) {
                params.append('status', statusFilter);
            }

            const response = await fetch(url + params.toString());
            const data = await response.json();

            if (data.records) {
                this.renderProdutos(data.records);
            } else {
                this.renderEmptyState('Nenhum produto encontrado.');
            }
        } catch (error) {
            console.error('Erro ao buscar produtos:', error);
            this.showAlert('Erro ao buscar produtos', 'error');
        } finally {
            this.showLoading(false);
        }
    }

    async saveProduto() {
        try {
            const formData = this.getFormData();
            
            if (!this.validateForm(formData)) {
                return;
            }

            const url = this.isEditing ? this.apiUrl + 'update.php' : this.apiUrl + 'create.php';
            const method = 'POST';

            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok) {
                this.showAlert(data.message, 'success');
                this.resetForm();
                this.loadProdutos();
            } else {
                // Tratamento específico para diferentes tipos de erro
                if (response.status === 409 || (data.message && data.message.includes('Duplicate'))) {
                    this.showAlert('Este produto já está cadastrado. Por favor, use um nome diferente.', 'warning');
                } else if (response.status === 400) {
                    this.showAlert(data.message || 'Dados inválidos. Verifique os campos e tente novamente.', 'danger');
                } else if (response.status === 500) {
                    this.showAlert('Erro interno do servidor. Tente novamente mais tarde.', 'danger');
                } else {
                    this.showAlert(data.message || 'Erro ao salvar produto', 'danger');
                }
            }
        } catch (error) {
            console.error('Erro ao salvar produto:', error);
            this.showAlert('Erro de conexão. Verifique sua internet e tente novamente.', 'danger');
        }
    }

    async deleteProduto(id) {
        if (!confirm('Tem certeza que deseja excluir este produto?')) {
            return;
        }

        try {
            const response = await fetch(this.apiUrl + 'delete.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: id })
            });

            const data = await response.json();

            if (response.ok) {
                this.showAlert(data.message, 'success');
                this.loadProdutos();
            } else {
                this.showAlert(data.message, 'error');
            }
        } catch (error) {
            console.error('Erro ao excluir produto:', error);
            this.showAlert('Erro ao excluir produto', 'error');
        }
    }

    editProduto(produto) {
        this.isEditing = true;
        this.editingId = produto.id;

        // Preencher formulário do modal
        document.getElementById('produtoId').value = produto.id;
        document.getElementById('produto-nome').value = produto.nome;
        document.getElementById('produto-descricao').value = produto.descricao || '';
        document.getElementById('produto-preco').value = produto.preco;
        document.getElementById('produto-status').value = produto.status || 'ativo';

        // Atualizar interface do modal
        document.getElementById('produtoModalLabel').textContent = 'Editar Produto';
        document.getElementById('produto-btn-text').textContent = 'Atualizar';

        // Abrir modal
        const modal = new bootstrap.Modal(document.getElementById('produtoModal'));
        modal.show();
    }

    resetForm() {
        this.isEditing = false;
        this.editingId = null;

        // Limpar formulário
        document.getElementById('produtoForm').reset();
        document.getElementById('produtoId').value = '';

        // Resetar interface do modal
        document.getElementById('produtoModalLabel').textContent = 'Cadastrar Produto';
        document.getElementById('produto-btn-text').textContent = 'Salvar';
    }

    getFormData() {
        const data = {
            nome: document.getElementById('produto-nome').value.trim(),
            descricao: document.getElementById('produto-descricao').value.trim(),
            preco: parseFloat(document.getElementById('produto-preco').value),
            status: document.getElementById('produto-status').value
        };

        if (this.isEditing) {
            data.id = this.editingId;
        }

        return data;
    }

    validateForm(data) {
        if (!data.nome) {
            this.showAlert('Nome é obrigatório', 'error');
            return false;
        }

        if (!data.preco || data.preco <= 0) {
            this.showAlert('Preço deve ser maior que zero', 'error');
            return false;
        }

        return true;
    }

    renderProdutos(produtos) {
        const tbody = document.getElementById('produtosTable');
        tbody.innerHTML = '';

        produtos.forEach(produto => {
            const row = document.createElement('tr');
            
            const statusBadge = produto.status === 'ativo' 
                ? '<span class="badge bg-success">Ativo</span>'
                : '<span class="badge bg-secondary">Inativo</span>';

            row.innerHTML = `
                <td>${produto.id}</td>
                <td>${produto.nome}</td>
                <td>${produto.descricao || '-'}</td>
                <td>R$ ${parseFloat(produto.preco).toFixed(2)}</td>
                <td>${statusBadge}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary me-1" onclick="produtoManager.editProduto(${JSON.stringify(produto).replace(/"/g, '&quot;')})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="produtoManager.deleteProduto(${produto.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    renderEmptyState(message = 'Nenhum produto cadastrado.') {
        const tbody = document.getElementById('produtosTable');
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-muted py-4">
                    <i class="fas fa-box fa-2x mb-2"></i><br>
                    ${message}
                </td>
            </tr>
        `;
    }

    showLoading(show) {
        const loading = document.getElementById('produto-loading');
        if (loading) {
            loading.style.display = show ? 'block' : 'none';
        }
    }

    showAlert(message, type = 'info') {
        // Reutilizar a função de alert do ClienteManager
        if (window.clienteManager) {
            window.clienteManager.showAlert(message, type);
        } else {
            alert(message);
        }
    }
}

// Inicializar sistema quando DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Inicializando sistema...');
    
    try {
        // Inicializar gerenciadores
        window.sidebarManager = new SidebarManager();
        window.clienteManager = new ClienteManager();
        window.produtoManager = new ProdutoManager();
        
        console.log('✅ Gerenciadores inicializados');

        // Eventos dos botões do dashboard
        const dashboardNovoCliente = document.getElementById('dashboardNovoCliente');
        const dashboardNovoProduto = document.getElementById('dashboardNovoProduto');

        if (dashboardNovoCliente) {
            dashboardNovoCliente.addEventListener('click', () => {
                window.clienteManager.resetForm();
                const modal = new bootstrap.Modal(document.getElementById('clienteModal'));
                modal.show();
            });
        }

        if (dashboardNovoProduto) {
            dashboardNovoProduto.addEventListener('click', () => {
                window.produtoManager.resetForm();
                const modal = new bootstrap.Modal(document.getElementById('produtoModal'));
                modal.show();
            });
        }
        
        // Forçar carregamento do dashboard após um delay
        setTimeout(() => {
            if (window.sidebarManager) {
                console.log('📊 Carregando dashboard inicial...');
                window.sidebarManager.loadDashboardData();
            }
        }, 1000);
        
        console.log('🎉 Sistema inicializado com sucesso!');
        
    } catch (error) {
        console.error('❌ Erro na inicialização:', error);
    }
});