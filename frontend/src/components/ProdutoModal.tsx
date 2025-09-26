import React, { useState, useEffect } from 'react';
import { produtoService } from '../services/produtoService';
import type { Produto } from '../services/produtoService';

interface ProdutoModalProps {
  isOpen: boolean;
  onClose: () => void;
  onSave: () => void;
  produto?: Produto | null;
  mode: 'create' | 'edit';
}

const ProdutoModal: React.FC<ProdutoModalProps> = ({
  isOpen,
  onClose,
  onSave,
  produto,
  mode
}) => {
  const [formData, setFormData] = useState({
    nome: '',
    descricao: '',
    preco: 0,
    status: 'ativo'
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    if (mode === 'edit' && produto) {
      setFormData({
        nome: produto.nome || '',
        descricao: produto.descricao || '',
        preco: produto.preco || 0,
        status: produto.status || 'ativo'
      });
    } else {
      setFormData({
        nome: '',
        descricao: '',
        preco: 0,
        status: 'ativo'
      });
    }
    setError(null);
  }, [mode, produto, isOpen]);

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: name === 'preco' ? parseFloat(value) || 0 : value
    }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    try {
      if (mode === 'create') {
        await produtoService.create(formData);
      } else if (mode === 'edit' && produto) {
        await produtoService.update({
          ...produto,
          ...formData
        });
      }
      onSave();
      onClose();
    } catch (err: any) {
      console.error('Erro ao salvar produto:', err);
      setError(err.response?.data?.message || 'Erro ao salvar produto');
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
              {mode === 'create' ? 'Novo Produto' : 'Editar Produto'}
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
                <label htmlFor="descricao" className="form-label">
                  Descrição
                </label>
                <textarea
                  className="form-control"
                  id="descricao"
                  name="descricao"
                  rows={3}
                  value={formData.descricao}
                  onChange={handleInputChange}
                  disabled={loading}
                />
              </div>

              <div className="mb-3">
                <label htmlFor="preco" className="form-label">
                  Preço <span className="text-danger">*</span>
                </label>
                <div className="input-group">
                  <span className="input-group-text">R$</span>
                  <input
                    type="number"
                    className="form-control"
                    id="preco"
                    name="preco"
                    value={formData.preco}
                    onChange={handleInputChange}
                    min="0"
                    step="0.01"
                    required
                    disabled={loading}
                  />
                </div>
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

export default ProdutoModal;