import { useState } from 'react'
import type { ReactNode } from 'react'
import Sidebar from './Sidebar'

interface LayoutProps {
  children: ReactNode
}

const Layout = ({ children }: LayoutProps) => {
  const [sidebarOpen, setSidebarOpen] = useState(false)

  const toggleSidebar = () => {
    setSidebarOpen(!sidebarOpen)
  }

  return (
    <div className="d-flex">
      <Sidebar isOpen={sidebarOpen} onToggle={toggleSidebar} />
      
      <div className={`flex-grow-1 ${sidebarOpen ? 'ms-250' : ''}`} style={{ minHeight: '100vh' }}>
        {/* Header */}
        <nav className="navbar navbar-expand-lg navbar-dark bg-primary">
          <div className="container-fluid">
            <button
              className="btn btn-outline-light me-3"
              type="button"
              onClick={toggleSidebar}
            >
              <i className="fas fa-bars"></i>
            </button>
            <span className="navbar-brand mb-0 h1">Sistema de Gestão</span>
          </div>
        </nav>

        {/* Main Content */}
        <main className="p-4">
          {children}
        </main>
      </div>
    </div>
  )
}

export default Layout