import { Link, useLocation } from 'react-router-dom'

interface SidebarProps {
  isOpen: boolean
  onToggle: () => void
}

const Sidebar = ({ isOpen }: SidebarProps) => {
  const location = useLocation()

  const menuItems = [
    { path: '/dashboard', label: 'Dashboard', icon: 'fas fa-tachometer-alt' },
    { path: '/clientes', label: 'Clientes', icon: 'fas fa-users' },
    { path: '/produtos', label: 'Produtos', icon: 'fas fa-box' },
    { path: '/relatorios', label: 'Relatórios', icon: 'fas fa-chart-bar' },
    { path: '/configuracoes', label: 'Configurações', icon: 'fas fa-cog' }
  ]

  return (
    <>
      {/* Overlay for mobile */}
      {isOpen && (
        <div 
          className="position-fixed top-0 start-0 w-100 h-100 bg-dark opacity-50 d-lg-none"
          style={{ zIndex: 1040 }}
          onClick={() => {}}
        />
      )}
      
      {/* Sidebar */}
      <div 
        className={`bg-dark text-white position-fixed position-lg-sticky top-0 start-0 h-100 ${
          isOpen ? 'translate-x-0' : 'translate-x-n100'
        } d-lg-block`}
        style={{ 
          width: '250px', 
          zIndex: 1050,
          transform: isOpen ? 'translateX(0)' : 'translateX(-100%)',
          transition: 'transform 0.3s ease-in-out'
        }}
      >
        <div className="p-3">
          <h5 className="text-center mb-4">Menu</h5>
          
          <ul className="nav nav-pills flex-column">
            {menuItems.map((item) => (
              <li key={item.path} className="nav-item mb-2">
                <Link
                  to={item.path}
                  className={`nav-link text-white d-flex align-items-center ${
                    location.pathname === item.path ? 'active bg-primary' : ''
                  }`}
                  style={{ textDecoration: 'none' }}
                >
                  <i className={`${item.icon} me-3`}></i>
                  {item.label}
                </Link>
              </li>
            ))}
          </ul>
        </div>
      </div>
    </>
  )
}

export default Sidebar