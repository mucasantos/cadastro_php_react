import React, { useState, useEffect } from 'react';
import { clienteService } from '../services/clienteService';
import type { Cliente } from '../services/clienteService';
import ClienteModal from '../components/ClienteModal';

const Clientes: React.FC = () => {
  const [clientes, setClientes] = useState<Cliente[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [searchTerm, setSearchTerm] = useState('');
  const [statusFilter, setStatusFilter] = useState('');
  
  // Modal states
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [modalMode, setModalMode] = useState<'create' | 'edit'>('create');
  const [selectedCliente, setSelectedCliente] = useState<Cliente | null>(null);

  useEffect(() => {
    fetchClientes();
  }, []);

  const fetchClientes = async () => {
    try {
      setLoading(true);
      setError(null);
      const response = await clienteService.getAll();
      setClientes(response.records || []);
    } catch (err) {
      console.error('Erro ao carregar clientes:', err);
      setError('Erro ao carregar clientes');
    } finally {
      setLoading(false);
    }
  };

  const handleSearch = async () => {
    try {
      setLoading(true);
      setError(null);
      const response = await clienteService.search(searchTerm, statusFilter);
      setClientes(response.records || []);
    } catch (err) {
      console.error('Erro ao pesquisar clientes:', err);
      setError('Erro ao pesquisar clientes');
    } finally {
      setLoading(false);
    }
  };

  const formatDate = (dateString: string) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('pt-BR');
  };

  const handleCreateCliente = () => {
    setModalMode('create');
    setSelectedCliente(null);
    setIsModalOpen(true);
  };

  const handleEditCliente = (cliente: Cliente) => {
    setModalMode('edit');
    setSelectedCliente(cliente);
    setIsModalOpen(true);
  };

  const handleDeleteCliente = async (cliente: Cliente) => {
    if (window.confirm(`Tem certeza que deseja excluir o cliente "${cliente.nome}"?`)) {
      try {
        // Note: Assuming delete endpoint exists in API
        await clienteService.delete(cliente.id);
        await fetchClientes();
      } catch (err) {
        console.error('Erro ao excluir cliente:', err);
        setError('Erro ao excluir cliente');
      }
    }
  };

  const handleModalSave = () => {
    fetchClientes();
  };

  const handleModalClose = () => {
    setIsModalOpen(false);
    setSelectedCliente(null);
  };

  return (
    <div className="container-fluid">
      <div className="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 className="h2">Clientes</h1>
      </div>
      
      <div className="card">
        <div className="card-header d-flex justify-content-between align-items-center">
          <h5 className="mb-0">Lista de Clientes</h5>
          <button 
            className="btn btn-primary"
            onClick={handleCreateCliente}
          >
            <i className="fas fa-plus me-2"></i>
            Novo Cliente
          </button>
        </div>
        <div className="card-body">
          <div className="row mb-3">
            <div className="col-md-6">
              <input 
                type="text" 
                className="form-control" 
                placeholder="Pesquisar clientes..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
              />
            </div>
            <div className="col-md-3">
              <select 
                className="form-select"
                value={statusFilter}
                onChange={(e) => setStatusFilter(e.target.value)}
              >
                <option value="">Todos os status</option>
                <option value="ativo">Ativo</option>
                <option value="inativo">Inativo</option>
              </select>
            </div>
            <div className="col-md-3">
              <button 
                className="btn btn-outline-primary w-100"
                onClick={handleSearch}
                disabled={loading}
              >
                <i className="fas fa-search me-2"></i>
                Pesquisar
              </button>
            </div>
          </div>

          {error && (
            <div className="alert alert-danger" role="alert">
              <i className="fas fa-exclamation-triangle me-2"></i>
              {error}
            </div>
          )}
          
          <div className="table-responsive">
            <table className="table table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nome</th>
                  <th>Email</th>
                  <th>Telefone</th>
                  <th>Status</th>
                  <th>Data Cadastro</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody>
                {loading ? (
                  <tr>
                    <td colSpan={7} className="text-center py-4">
                      <div className="spinner-border text-primary" role="status">
                        <span className="visually-hidden">Carregando...</span>
                      </div>
                      <p className="mt-2 mb-0">Carregando clientes...</p>
                    </td>
                  </tr>
                ) : clientes.length === 0 ? (
                  <tr>
                    <td colSpan={7} className="text-center py-4">
                      <i className="fas fa-users fa-3x text-muted mb-3"></i>
                      <p className="text-muted">Nenhum cliente encontrado</p>
                    </td>
                  </tr>
                ) : (
                  clientes.map((cliente) => (
                    <tr key={cliente.id}>
                      <td>{cliente.id}</td>
                      <td>{cliente.nome}</td>
                      <td>{cliente.email}</td>
                      <td>{cliente.telefone || '-'}</td>
                      <td>
                        <span className={`badge ${cliente.status === 'ativo' ? 'bg-success' : 'bg-secondary'}`}>
                          {cliente.status}
                        </span>
                      </td>
                      <td>{formatDate(cliente.created_at)}</td>
                      <td>
                        <div className="btn-group" role="group">
                          <button 
                            className="btn btn-sm btn-outline-primary" 
                            title="Editar"
                            onClick={() => handleEditCliente(cliente)}
                          >
                            <i className="fas fa-edit"></i>
                          </button>
                          <button className="btn btn-sm btn-outline-info" title="Ver detalhes">
                            <i className="fas fa-eye"></i>
                          </button>
                          <button 
                            className="btn btn-sm btn-outline-danger" 
                            title="Excluir"
                            onClick={() => handleDeleteCliente(cliente)}
                          >
                            <i className="fas fa-trash"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <ClienteModal
        isOpen={isModalOpen}
        onClose={handleModalClose}
        onSave={handleModalSave}
        cliente={selectedCliente}
        mode={modalMode}
      />
    </div>
  );
};

export default Clientes