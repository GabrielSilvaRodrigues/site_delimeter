import React from 'react';
import './Header.css';

const Header = ({ user, logout }) => {
  return (
    <header className="header">
      <div className="header-container">
        <div className="logo">
          <a href="/">
            <img src="/assets/images/logo.png" alt="Logo Delimeter" />
          </a>
        </div>
        
        <nav className="nav">
          <ul>
            <li><a href="/about">Sobre</a></li>
            <li><a href="/calculo">Cálculo</a></li>
            <li><a href="/delimiter-tool">Delimitador</a></li>
            
            {user ? (
              <>
                <li><a href={`/${user.tipo}`}>Painel</a></li>
                <li><a href="/conta">Conta</a></li>
                <li><button onClick={logout} className="logout-btn">Sair</button></li>
              </>
            ) : (
              <>
                <li><a href="/register">Cadastrar</a></li>
                <li><a href="/login">Login</a></li>
              </>
            )}
          </ul>
        </nav>

        <div className="mobile-menu">
          <button className="menu-toggle">☰</button>
        </div>
      </div>
    </header>
  );
};

export default Header;