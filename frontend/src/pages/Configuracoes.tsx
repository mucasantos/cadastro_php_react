const Configuracoes = () => {
  return (
    <div>
      <h1 className="mb-4">Configurações</h1>
      
      <div className="row">
        <div className="col-md-8">
          <div className="card">
            <div className="card-header">
              <h5 className="mb-0">Configurações do Sistema</h5>
            </div>
            <div className="card-body">
              <form>
                <div className="mb-3">
                  <label htmlFor="nomeEmpresa" className="form-label">Nome da Empresa</label>
                  <input 
                    type="text" 
                    className="form-control" 
                    id="nomeEmpresa"
                    defaultValue="Sistema de Gestão"
                  />
                </div>
                
                <div className="mb-3">
                  <label htmlFor="emailContato" className="form-label">Email de Contato</label>
                  <input 
                    type="email" 
                    className="form-control" 
                    id="emailContato"
                    defaultValue="contato@empresa.com"
                  />
                </div>
                
                <div className="mb-3">
                  <label htmlFor="telefone" className="form-label">Telefone</label>
                  <input 
                    type="tel" 
                    className="form-control" 
                    id="telefone"
                    defaultValue="(11) 99999-9999"
                  />
                </div>
                
                <div className="mb-3">
                  <label htmlFor="endereco" className="form-label">Endereço</label>
                  <textarea 
                    className="form-control" 
                    id="endereco" 
                    rows={3}
                    defaultValue="Rua Exemplo, 123 - São Paulo, SP"
                  />
                </div>
                
                <div className="mb-3">
                  <div className="form-check">
                    <input 
                      className="form-check-input" 
                      type="checkbox" 
                      id="notificacoes"
                      defaultChecked
                    />
                    <label className="form-check-label" htmlFor="notificacoes">
                      Receber notificações por email
                    </label>
                  </div>
                </div>
                
                <div className="mb-3">
                  <div className="form-check">
                    <input 
                      className="form-check-input" 
                      type="checkbox" 
                      id="backupAutomatico"
                      defaultChecked
                    />
                    <label className="form-check-label" htmlFor="backupAutomatico">
                      Backup automático diário
                    </label>
                  </div>
                </div>
                
                <button type="submit" className="btn btn-primary">
                  <i className="fas fa-save me-2"></i>
                  Salvar Configurações
                </button>
              </form>
            </div>
          </div>
        </div>
        
        <div className="col-md-4">
          <div className="card">
            <div className="card-header">
              <h5 className="mb-0">Informações do Sistema</h5>
            </div>
            <div className="card-body">
              <p><strong>Versão:</strong> 1.0.0</p>
              <p><strong>Última Atualização:</strong> 15/01/2024</p>
              <p><strong>Banco de Dados:</strong> MySQL</p>
              <p><strong>Servidor:</strong> Apache</p>
              
              <hr />
              
              <h6>Ações do Sistema</h6>
              <div className="d-grid gap-2">
                <button className="btn btn-warning btn-sm">
                  <i className="fas fa-download me-2"></i>
                  Fazer Backup
                </button>
                <button className="btn btn-info btn-sm">
                  <i className="fas fa-sync me-2"></i>
                  Limpar Cache
                </button>
                <button className="btn btn-secondary btn-sm">
                  <i className="fas fa-file-alt me-2"></i>
                  Ver Logs
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

export default Configuracoes