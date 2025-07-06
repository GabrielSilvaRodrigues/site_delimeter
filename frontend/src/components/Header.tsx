import React from 'react';
import { Link } from 'react-router-dom';
import './Header.css';

const Header: React.FC = () => {
  return (
    <header className="header">
      <div className="container">
        <div className="logo">
          <Link to="/">
            <img src="/assets/images/logo.png" alt="Deliméter" />
            <span>Deliméter</span>
          </Link>
        </div>
        
        <nav className="nav">
          <ul>
            <li><Link to="/">Início</Link></li>
            <li><Link to="/calculo">Calculadora</Link></li>
            <li><Link to="/sobre">Sobre</Link></li>
            <li><Link to="/login">Login</Link></li>
          </ul>
        </nav>
        
        <div className="accessibility-controls">
          <button id="toggleContrast" className="accessibility-btn">
            Alto Contraste
          </button>
          <button id="increaseFontSize" className="accessibility-btn">
            A+
          </button>
          <button id="decreaseFontSize" className="accessibility-btn">
            A-
          </button>
        </div>
      </div>
    </header>
  );
};

export default Header;
