import React, { useState, useEffect } from 'react';
import { clienteService } from '../services/clienteService';
import { produtoService } from '../services/produtoService';

interface DashboardStats {
  totalClientes: number;
  totalProdutos: number;
  clientesAtivos: number;
  produtosAtivos: number;
  precoMedioProdutos: number;
}

const Dashboard: React.FC = () => {
  const [stats, setStats] = useState<DashboardStats>({
    totalClientes: 0,
    totalProdutos: 0,
    clientesAtivos: 0,
    produtosAtivos: 0,
    precoMedioProdutos: 0
  });
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchStats = async () => {
      try {
        setLoading(true);
        setError(null);

        // Buscar estatísticas de clientes e produtos em paralelo
        const [clienteStats, produtoStats] = await Promise.all([
          clienteService.getStats(),
          produtoService.getStats()
        ]);

        setStats({
          totalClientes: clienteStats.total,
          totalProdutos: produtoStats.total,
          clientesAtivos: clienteStats.ativos,
          produtosAtivos: produtoStats.ativos,
          precoMedioProdutos: produtoStats.preco_medio
        });
      } catch (err) {
        console.error('Erro ao carregar estatísticas:', err);
        setError('Erro ao carregar dados do dashboard');
      } finally {
        setLoading(false);
      }
    };

    fetchStats();
  }, []);

  if (loading) {
    return (
      <div className="d-flex justify-content-center align-items-center" style={{ height: '400px' }}>
        <div className="spinner-border text-primary" role="status">
          <span className="visually-hidden">Carregando...</span>
        </div>
      </div>
    )
  }

  return (
    <div>
      <h1 className="mb-4">Dashboard</h1>
      
      <div className="row">
        <div className="col-md-3 mb-4">
          <div className="card bg-primary text-white">
            <div className="card-body">
              <div className="d-flex justify-content-between">
                <div>
                  <h4 className="card-title">{stats.totalClientes}</h4>
                  <p className="card-text">Total de Clientes</p>
                </div>
                <div className="align-self-center">
                  <i className="fas fa-users fa-2x"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="col-md-3 mb-4">
          <div className="card bg-success text-white">
            <div className="card-body">
              <div className="d-flex justify-content-between">
                <div>
                  <h4 className="card-title">{stats.totalProdutos}</h4>
                  <p className="card-text">Total de Produtos</p>
                </div>
                <div className="align-self-center">
                  <i className="fas fa-box fa-2x"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="col-md-3 mb-4">
          <div className="card bg-warning text-white">
            <div className="card-body">
              <div className="d-flex justify-content-between">
                <div>
                  <h4 className="card-title">{stats.clientesAtivos}</h4>
                  <p className="card-text">Clientes Ativos</p>
                </div>
                <div className="align-self-center">
                  <i className="fas fa-user-check fa-2x"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="col-md-3 mb-4">
          <div className="card bg-info text-white">
            <div className="card-body">
              <div className="d-flex justify-content-between">
                <div>
                  <h4 className="card-title">R$ {(stats.precoMedioProdutos || 0).toFixed(2)}</h4>
                  <p className="card-text">Preço Médio</p>
                </div>
                <div className="align-self-center">
                  <i className="fas fa-dollar-sign fa-2x"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div className="row mt-4">
        <div className="col-12">
          <div className="card">
            <div className="card-header">
              <h5 className="card-title mb-0">Ações Rápidas</h5>
            </div>
            <div className="card-body">
              <div className="row">
                <div className="col-md-3 mb-3">
                  <button className="btn btn-primary w-100">
                    <i className="fas fa-user-plus me-2"></i>
                    Novo Cliente
                  </button>
                </div>
                <div className="col-md-3 mb-3">
                  <button className="btn btn-success w-100">
                    <i className="fas fa-plus me-2"></i>
                    Novo Produto
                  </button>
                </div>
                <div className="col-md-3 mb-3">
                  <button className="btn btn-warning w-100">
                    <i className="fas fa-chart-line me-2"></i>
                    Ver Relatórios
                  </button>
                </div>
                <div className="col-md-3 mb-3">
                  <button className="btn btn-info w-100">
                    <i className="fas fa-cog me-2"></i>
                    Configurações
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

export default Dashboard