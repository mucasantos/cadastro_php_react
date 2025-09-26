const Relatorios = () => {
  return (
    <div>
      <h1 className="mb-4">Relatórios</h1>
      
      <div className="row">
        <div className="col-md-6 mb-4">
          <div className="card">
            <div className="card-header">
              <h5 className="mb-0">Relatório de Clientes</h5>
            </div>
            <div className="card-body">
              <p className="card-text">Visualize estatísticas e dados dos seus clientes.</p>
              <button className="btn btn-primary">
                <i className="fas fa-chart-bar me-2"></i>
                Gerar Relatório
              </button>
            </div>
          </div>
        </div>
        
        <div className="col-md-6 mb-4">
          <div className="card">
            <div className="card-header">
              <h5 className="mb-0">Relatório de Produtos</h5>
            </div>
            <div className="card-body">
              <p className="card-text">Analise o desempenho dos seus produtos.</p>
              <button className="btn btn-success">
                <i className="fas fa-chart-line me-2"></i>
                Gerar Relatório
              </button>
            </div>
          </div>
        </div>
        
        <div className="col-md-6 mb-4">
          <div className="card">
            <div className="card-header">
              <h5 className="mb-0">Relatório de Vendas</h5>
            </div>
            <div className="card-body">
              <p className="card-text">Acompanhe suas vendas e faturamento.</p>
              <button className="btn btn-warning">
                <i className="fas fa-chart-pie me-2"></i>
                Gerar Relatório
              </button>
            </div>
          </div>
        </div>
        
        <div className="col-md-6 mb-4">
          <div className="card">
            <div className="card-header">
              <h5 className="mb-0">Relatório Financeiro</h5>
            </div>
            <div className="card-body">
              <p className="card-text">Visualize dados financeiros detalhados.</p>
              <button className="btn btn-info">
                <i className="fas fa-dollar-sign me-2"></i>
                Gerar Relatório
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

export default Relatorios