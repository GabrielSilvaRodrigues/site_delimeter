import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import AccessibilityMenu from '../AccessibilityMenu/AccessibilityMenu';
import './Header.css';

const Header = () => {
  const { user, logout } = useAuth();
  const [menuOpen, setMenuOpen] = useState(false);

  const handleMenuToggle = () => {
    setMenuOpen(!menuOpen);
  };

  const handleLogout = () => {
    logout();
    setMenuOpen(false);
  };

  return (
    <header className="header">
      <div className="logo">
        <Link to="/delimeter">
          <img src="/public/assets/images/logo.png" alt="Logo Delímiter" />
        </Link>
      </div>
      
      <div className="menu-hamburguer">
        <input 
          type="checkbox" 
          id="menu-toggle" 
          checked={menuOpen}
          onChange={handleMenuToggle}
        />
        <label htmlFor="menu-toggle" className="menu-icon">
          <div className="linha"></div>
          <div className="linha"></div>
          <div className="linha"></div>
        </label>
        
        <div className={`overlay ${menuOpen ? 'open' : ''}`}>
          <nav>
            <ul>
              <li><Link to="/delimeter/sobre" onClick={() => setMenuOpen(false)}>Sobre Nós</Link></li>
              <li><Link to="/delimeter/calculo" onClick={() => setMenuOpen(false)}>Cálculo nutricional</Link></li>
              
              {user ? (
                <>
                  {user.tipo === 'paciente' && (
                    <li><Link to="/paciente" onClick={() => setMenuOpen(false)}>Painel</Link></li>
                  )}
                  {user.tipo === 'nutricionista' && (
                    <li><Link to="/nutricionista" onClick={() => setMenuOpen(false)}>Painel</Link></li>
                  )}
                  {user.tipo === 'medico' && (
                    <li><Link to="/medico" onClick={() => setMenuOpen(false)}>Painel</Link></li>
                  )}
                  <li><Link to="/conta" onClick={() => setMenuOpen(false)}>Conta</Link></li>
                  <li><Link to="/usuario" onClick={() => setMenuOpen(false)}>Home</Link></li>
                  <li><button onClick={handleLogout} className="logout-btn">Sair</button></li>
                </>
              ) : (
                <>
                  <li><Link to="/usuario/cadastro" onClick={() => setMenuOpen(false)}>Cadastrar-se</Link></li>
                  <li><Link to="/usuario/login" onClick={() => setMenuOpen(false)}>Login</Link></li>
                </>
              )}
            </ul>
            
            <AccessibilityMenu />
          </nav>
        </div>
      </div>
    </header>
  );
};

export default Header;