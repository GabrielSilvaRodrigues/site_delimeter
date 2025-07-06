import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import './Header.css';

const Header = () => {
  const { user, logout } = useAuth();
  const [menuOpen, setMenuOpen] = useState(false);

  const handleMenuToggle = () => {
    setMenuOpen(!menuOpen);
  };

  return (
    <header className="app-header">
      <div className="logo">
        <Link to="/">
          <img src="/public/assets/images/logo.png" alt="Logo Delímiter" />
        </Link>
      </div>
      
      <nav className="main-nav">
        <ul>
          <li><Link to="/about">Sobre Nós</Link></li>
          <li><Link to="/calculo">Cálculo Nutricional</Link></li>
          {user ? (
            <>
              <li><Link to={`/${user.tipo}`}>Painel</Link></li>
              <li><Link to="/conta">Conta</Link></li>
              <li>
                <button onClick={logout} className="logout-btn">
                  Sair
                </button>
              </li>
            </>
          ) : (
            <>
              <li><Link to="/register">Cadastrar-se</Link></li>
              <li><Link to="/login">Login</Link></li>
            </>
          )}
        </ul>
      </nav>

      <div className="accessibility-menu">
        <button className="accessibility-toggle" aria-label="Menu de Acessibilidade">
          ♿
        </button>
        <div className="accessibility-dropdown">
          <button onClick={() => document.body.style.fontSize = '18px'}>A+</button>
          <button onClick={() => document.body.style.fontSize = '14px'}>A-</button>
          <button onClick={() => document.body.classList.toggle('high-contrast')}>
            Alto Contraste
          </button>
        </div>
      </div>
    </header>
  );
};

export default Header;