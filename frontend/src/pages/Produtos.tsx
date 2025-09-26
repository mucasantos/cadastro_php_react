import React, { useState, useEffect } from 'react';
import { produtoService, type Produto } from '../services/produtoService';
import ProdutoModal from '../components/ProdutoModal';

function Produtos() {
  const [produtos, setProdutos] = useState<Produto[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [searchTerm, setSearchTerm] = useState('');
  const [statusFilter, setStatusFilter] = useState('');
  
  // Modal states
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [modalMode, setModalMode] = useState<'create' | 'edit'>('create');
  const [selectedProduto, setSelectedProduto] = useState<Produto | null>(null);

  useEffect(() => {
    fetchProdutos();
  }, []);

  const fetchProdutos = async () => {
    try {
      setLoading(true);
      setError(null);
      const response = await produtoService.getAll();
      setProdutos(response.records || []);
    } catch (err) {
      setError('Erro ao carregar produtos');
      console.error('Erro ao buscar produtos:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleSearch = async () => {
    try {
      setLoading(true);
      setError(null);
      const response = await produtoService.search(searchTerm, statusFilter);
      setProdutos(response.records || []);
    } catch (err) {
      setError('Erro ao pesquisar produtos');
      console.error('Erro na pesquisa:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleCreateProduto = () => {
    setModalMode('create');
    setSelectedProduto(null);
    setIsModalOpen(true);
  };

  const handleEditProduto = (produto: Produto) => {
    setModalMode('edit');
    setSelectedProduto(produto);
    setIsModalOpen(true);
  };

  const handleDeleteProduto = async (produto: Produto) => {
    if (window.confirm(`Tem certeza que deseja excluir o produto "${produto.nome}"?`)) {
      try {
        await produtoService.delete(produto.id);
        await fetchProdutos(); // Recarrega a lista
      } catch (err) {
        setError('Erro ao excluir produto');
        console.error('Erro ao excluir produto:', err);
      }
    }
  };

  const handleSaveProduto = async () => {
    await fetchProdutos(); // Recarrega a lista após salvar
    setIsModalOpen(false);
  };

  const handleCloseModal = () => {
    setIsModalOpen(false);
    setSelectedProduto(null);
  };

  const formatPrice = (price: number) => {
    return new Intl.NumberFormat('pt-BR', {
      style: 'currency',
      currency: 'BRL'
    }).format(price);
  };

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('pt-BR');
  };

  return (
    <div className="p-6">
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-2xl font-bold text-gray-900">Produtos</h1>
        <button 
          onClick={handleCreateProduto}
          className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
        >
          Novo Produto
        </button>
      </div>

      {/* Filtros */}
      <div className="bg-white p-4 rounded-lg shadow mb-6">
        <div className="flex gap-4">
          <div className="flex-1">
            <input
              type="text"
              placeholder="Pesquisar produtos..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          <div>
            <select
              value={statusFilter}
              onChange={(e) => setStatusFilter(e.target.value)}
              className="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Todos os status</option>
              <option value="ativo">Ativo</option>
              <option value="inativo">Inativo</option>
            </select>
          </div>
          <button
            onClick={handleSearch}
            className="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors"
          >
            Pesquisar
          </button>
        </div>
      </div>

      {/* Lista de Produtos */}
      <div className="bg-white rounded-lg shadow">
        <div className="overflow-x-auto">
          <table className="min-w-full divide-y divide-gray-200">
            <thead className="bg-gray-50">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Nome
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Descrição
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Preço
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Data Criação
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Ações
                </th>
              </tr>
            </thead>
            <tbody className="bg-white divide-y divide-gray-200">
              {loading ? (
                <tr>
                  <td colSpan={6} className="px-6 py-4 text-center">
                    <div className="flex justify-center items-center">
                      <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                      <span className="ml-2">Carregando produtos...</span>
                    </div>
                  </td>
                </tr>
              ) : error ? (
                <tr>
                  <td colSpan={6} className="px-6 py-4 text-center text-red-600">
                    {error}
                  </td>
                </tr>
              ) : produtos.length === 0 ? (
                <tr>
                  <td colSpan={6} className="px-6 py-4 text-center text-gray-500">
                    Nenhum produto encontrado
                  </td>
                </tr>
              ) : (
                produtos.map((produto) => (
                  <tr key={produto.id} className="hover:bg-gray-50">
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="text-sm font-medium text-gray-900">
                        {produto.nome}
                      </div>
                    </td>
                    <td className="px-6 py-4">
                      <div className="text-sm text-gray-900 max-w-xs truncate">
                        {produto.descricao}
                      </div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="text-sm font-medium text-gray-900">
                        {formatPrice(produto.preco)}
                      </div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <span className={`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
                        produto.status === 'ativo' 
                          ? 'bg-green-100 text-green-800' 
                          : 'bg-red-100 text-red-800'
                      }`}>
                        {produto.status}
                      </span>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {formatDate(produto.data_criacao)}
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <button
                        onClick={() => handleEditProduto(produto)}
                        className="text-blue-600 hover:text-blue-900 mr-3"
                      >
                        Editar
                      </button>
                      <button
                        onClick={() => handleDeleteProduto(produto)}
                        className="text-red-600 hover:text-red-900"
                      >
                        Excluir
                      </button>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </div>

      {/* Modal */}
      <ProdutoModal
        isOpen={isModalOpen}
        onClose={handleCloseModal}
        onSave={handleSaveProduto}
        produto={selectedProduto}
        mode={modalMode}
      />
    </div>
  );
}

export default Produtos;