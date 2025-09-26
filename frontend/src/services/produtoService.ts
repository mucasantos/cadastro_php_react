import api from './api';

export interface Produto {
  id: number;
  nome: string;
  descricao: string;
  preco: number;
  status: string;
  data_criacao: string;
  data_atualizacao: string;
}

export interface ProdutoResponse {
  records: Produto[];
}

export const produtoService = {
  // Buscar todos os produtos
  getAll: async (): Promise<ProdutoResponse> => {
    const response = await api.get('/produtos/read.php');
    return response.data;
  },

  // Buscar produto por ID
  getById: async (id: number): Promise<Produto> => {
    const response = await api.get(`/produtos/read_one.php?id=${id}`);
    return response.data;
  },

  // Pesquisar produtos
  search: async (keywords?: string, status?: string): Promise<ProdutoResponse> => {
    const params = new URLSearchParams();
    if (keywords) params.append('keywords', keywords);
    if (status) params.append('status', status);
    const response = await api.get(`/produtos/search.php?${params.toString()}`);
    return response.data;
  },

  // Criar produto
  create: async (produto: Omit<Produto, 'id' | 'data_criacao' | 'data_atualizacao'>): Promise<any> => {
    const response = await api.post('/produtos/create.php', produto);
    return response.data;
  },

  // Atualizar produto
  update: async (produto: Produto): Promise<any> => {
    const response = await api.put('/produtos/update.php', produto);
    return response.data;
  },

  // Excluir produto
  delete: async (id: number): Promise<any> => {
    const response = await api.delete(`/produtos/delete.php?id=${id}`);
    return response.data;
  },

  // Estatísticas de produtos
  getStats: async (): Promise<{ total: number; ativos: number; preco_medio: number }> => {
    try {
      const response = await produtoService.getAll();
      const produtos = response.records || [];
      
      const stats = {
        total: produtos.length,
        ativos: produtos.filter(p => p.status === 'ativo').length,
        preco_medio: produtos.length > 0 
          ? produtos.reduce((sum, p) => sum + Number(p.preco), 0) / produtos.length 
          : 0
      };
      
      return stats;
    } catch (error) {
      return { total: 0, ativos: 0, preco_medio: 0 };
    }
  }
};