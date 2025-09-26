import React, { useState, useEffect } from 'react';
import { clienteService } from '../services/clienteService';
import type { Cliente } from '../services/clienteService';

interface ClienteModalProps {
  isOpen: boolean;
  onClose: () => void;
  onSave: () => void;
  cliente?: Cliente | null;
  mode: 'create' | 'edit';
}

const ClienteModal: React.FC<ClienteModalProps> = ({
  isOpen,
  onClose,
  onSave,
  cliente,
  mode
}) => {
  const [formData, setFormData] = useState({
    nome: '',
    email: '',
    telefone: '',
    endereco: '',
    data_nascimento: '',
    status: 'ativo'
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    if (mode === 'edit' && cliente) {
      setFormData({
        nome: cliente.nome || '',
        email: cliente.email || '',
        telefone: cliente.telefone || '',
        endereco: cliente.endereco || '',
        data_nascimento: cliente.data_nascimento || '',
        status: cliente.status || 'ativo'
      });
    } else {
      setFormData({
        nome: '',
        email: '',
        telefone: '',
        endereco: '',
        data_nascimento: '',
        status: 'ativo'
      });
    }
    setError(null);
  }, [mode, cliente, isOpen]);

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    try {
      if (mode === 'create') {
        await clienteService.create(formData);
      } else if (mode === 'edit' && cliente) {
        await clienteService.update({
          ...cliente,
          ...formData
        });
      }
      onSave();
      onClose();
    } catch (err: any) {
      console.error('Erro ao salvar cliente:', err);
      setError(err.response?.data?.message || 'Erro ao salvar cliente');
    } finally {
      setLoading(false);
    }
  };

  if (!isOpen) return null;

  return (
    <div className="modal fade show d-block" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
      <div className="modal-dialog">
        <div className="modal-content">
          <div className="modal-header">
            <h5 className="modal-title">
              {mode === 'create' ? 'Novo Cliente' : 'Editar Cliente'}
            </h5>
            <button
              type="button"
              className="btn-close"
              onClick={onClose}
              disabled={loading}
            ></button>
          </div>
          <form onSubmit={handleSubmit}>
            <div className="modal-body">
              {error && (
                <div className="alert alert-danger" role="alert">
                  <i className="fas fa-exclamation-triangle me-2"></i>
                  {error}
                </div>
              )}
              
              <div className="mb-3">
                <label htmlFor="nome" className="form-label">
                  Nome <span className="text-danger">*</span>
                </label>
                <input
                  type="text"
                  className="form-control"
                  id="nome"
                  name="nome"
                  value={formData.nome}
                  onChange={handleInputChange}
                  required
                  disabled={loading}
                />
              </div>

              <div className="mb-3">
                <label htmlFor="email" className="form-label">
                  Email <span className="text-danger">*</span>
                </label>
                <input
                  type="email"
                  className="form-control"
                  id="email"
                  name="email"
                  value={formData.email}
                  onChange={handleInputChange}
                  required
                  disabled={loading}
                />
              </div>

              <div className="mb-3">
                <label htmlFor="telefone" className="form-label">
                  Telefone
                </label>
                <input
                  type="tel"
                  className="form-control"
                  id="telefone"
                  name="telefone"
                  value={formData.telefone}
                  onChange={handleInputChange}
                  disabled={loading}
                />
              </div>

              <div className="mb-3">
                <label htmlFor="endereco" className="form-label">
                  Endereço
                </label>
                <input
                  type="text"
                  className="form-control"
                  id="endereco"
                  name="endereco"
                  value={formData.endereco}
                  onChange={handleInputChange}
                  disabled={loading}
                />
              </div>

              <div className="mb-3">
                <label htmlFor="data_nascimento" className="form-label">
                  Data de Nascimento
                </label>
                <input
                  type="date"
                  className="form-control"
                  id="data_nascimento"
                  name="data_nascimento"
                  value={formData.data_nascimento}
                  onChange={handleInputChange}
                  disabled={loading}
                />
              </div>

              <div className="mb-3">
                <label htmlFor="status" className="form-label">
                  Status
                </label>
                <select
                  className="form-select"
                  id="status"
                  name="status"
                  value={formData.status}
                  onChange={handleInputChange}
                  disabled={loading}
                >
                  <option value="ativo">Ativo</option>
                  <option value="inativo">Inativo</option>
                </select>
              </div>
            </div>
            <div className="modal-footer">
              <button
                type="button"
                className="btn btn-secondary"
                onClick={onClose}
                disabled={loading}
              >
                Cancelar
              </button>
              <button
                type="submit"
                className="btn btn-primary"
                disabled={loading}
              >
                {loading ? (
                  <>
                    <span className="spinner-border spinner-border-sm me-2" role="status"></span>
                    Salvando...
                  </>
                ) : (
                  <>
                    <i className="fas fa-save me-2"></i>
                    {mode === 'create' ? 'Criar' : 'Salvar'}
                  </>
                )}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
};

export default ClienteModal;