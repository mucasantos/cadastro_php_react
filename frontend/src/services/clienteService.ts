import api from './api';

export interface Cliente {
  id: number;
  nome: string;
  email: string;
  telefone: string;
  endereco: string;
  data_nascimento: string;
  status: string;
  created_at: string;
  updated_at: string;
}

export interface ClienteResponse {
  records: Cliente[];
}

export const clienteService = {
  // Buscar todos os clientes
  getAll: async (): Promise<ClienteResponse> => {
    const response = await api.get('/clientes/read.php');
    return response.data;
  },

  // Buscar cliente por ID
  getById: async (id: number): Promise<Cliente> => {
    const response = await api.get(`/clientes/read_one.php?id=${id}`);
    return response.data;
  },

  // Pesquisar clientes
  search: async (keywords?: string, status?: string): Promise<ClienteResponse> => {
    const params = new URLSearchParams();
    if (keywords) params.append('keywords', keywords);
    if (status) params.append('status', status);
    const response = await api.get(`/clientes/search.php?${params.toString()}`);
    return response.data;
  },

  // Criar cliente
  create: async (cliente: Omit<Cliente, 'id' | 'created_at' | 'updated_at'>): Promise<any> => {
    const response = await api.post('/clientes/create.php', cliente);
    return response.data;
  },

  // Atualizar cliente
  update: async (cliente: Cliente): Promise<any> => {
    const response = await api.put('/clientes/update.php', cliente);
    return response.data;
  },

  // Excluir cliente
  delete: async (id: number): Promise<any> => {
    const response = await api.delete(`/clientes/delete.php?id=${id}`);
    return response.data;
  },

  // Buscar produtos disponíveis para um cliente
  getAvailableProducts: async (clienteId: number): Promise<any> => {
    const response = await api.get(`/clientes/available_products.php?cliente_id=${clienteId}`);
    return response.data;
  },

  // Estatísticas de clientes
  getStats: async (): Promise<{ total: number; ativos: number; inativos: number }> => {
    try {
      const response = await clienteService.getAll();
      const clientes = response.records || [];
      
      const stats = {
        total: clientes.length,
        ativos: clientes.filter(c => c.status === 'ativo').length,
        inativos: clientes.filter(c => c.status === 'inativo').length
      };
      
      return stats;
    } catch (error) {
      return { total: 0, ativos: 0, inativos: 0 };
    }
  }
};