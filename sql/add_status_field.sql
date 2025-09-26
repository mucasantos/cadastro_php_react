-- Adicionar campo status na tabela clientes
ALTER TABLE clientes 
ADD COLUMN status ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo';

-- Atualizar todos os registros existentes para ativo
UPDATE clientes SET status = 'ativo' WHERE status IS NULL OR status = '';

-- Verificar a estrutura da tabela
DESCRIBE clientes;