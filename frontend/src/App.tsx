import { Routes, Route } from 'react-router-dom'
import Layout from './components/Layout'
import Dashboard from './pages/Dashboard'
import Clientes from './pages/Clientes'
import Produtos from './pages/Produtos'
import Relatorios from './pages/Relatorios'
import Configuracoes from './pages/Configuracoes'

function App() {
  return (
    <Layout>
      <Routes>
        <Route path="/" element={<Dashboard />} />
        <Route path="/dashboard" element={<Dashboard />} />
        <Route path="/clientes" element={<Clientes />} />
        <Route path="/produtos" element={<Produtos />} />
        <Route path="/relatorios" element={<Relatorios />} />
        <Route path="/configuracoes" element={<Configuracoes />} />
      </Routes>
    </Layout>
  )
}

export default App
