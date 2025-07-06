import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import './Header.css';

const Header = () => {
  const [menuOpen, setMenuOpen] = useState(false);
  const [userSession, setUserSession] = useState(null);
  const navigate = useNavigate();

  // Simulando a verificação de sessão do usuário
  useEffect(() => {
    // Em uma aplicação real, verificaria o estado da sessão com uma chamada de API
    // Por enquanto, vamos checar o localStorage como exemplo
    const userData = localStorage.getItem('userSession');
    if (userData) {
      setUserSession(JSON.parse(userData));
    }
  }, []);

  const toggleMenu = () => {
    setMenuOpen(!menuOpen);
  };

  const aumentarFonte = () => {
    document.body.style.fontSize = parseInt(window.getComputedStyle(document.body).fontSize) + 2 + 'px';
  };

  const diminuirFonte = () => {
    document.body.style.fontSize = parseInt(window.getComputedStyle(document.body).fontSize) - 2 + 'px';
  };

  const toggleContraste = () => {
    document.body.classList.toggle('alto-contraste');
  };

  const toggleDaltonismo = (tipo) => {
    document.body.className = document.body.className
      .replace(/protanopia|deuteranopia|tritanopia/g, '')
      .trim();
    document.body.classList.add(tipo);
  };

  const resetarAcessibilidade = () => {
    document.body.className = document.body.className
      .replace(/protanopia|deuteranopia|tritanopia|alto-contraste/g, '')
      .trim();
    document.body.style.fontSize = '';
  };

  return (
    <header>
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
          onChange={toggleMenu}
        />
        <label htmlFor="menu-toggle" className="menu-icon">
          <div className="linha"></div>
          <div className="linha"></div>
          <div className="linha"></div>
        </label>
        <div className={`overlay ${menuOpen ? 'active' : ''}`}>
          <nav>
            <ul aria-label="Acessibilidade" className="acessibilidade">
              <li><Link to="/delimeter/sobre" className="link" onClick={toggleMenu}>Sobre Nós</Link></li>
              <li><Link to="/delimeter/calculo" className="link" onClick={toggleMenu}>Cálculo nutricional</Link></li>
              
              {userSession ? (
                <>
                  {userSession.tipo === 'paciente' && (
                    <li><Link to="/paciente" className="link" onClick={toggleMenu}>Painel</Link></li>
                  )}
                  {userSession.tipo === 'nutricionista' && (
                    <li><Link to="/nutricionista" className="link" onClick={toggleMenu}>Painel</Link></li>
                  )}
                  {userSession.tipo === 'medico' && (
                    <li><Link to="/medico" className="link" onClick={toggleMenu}>Painel</Link></li>
                  )}
                  <li><Link to="/conta" className="link" onClick={toggleMenu}>Conta</Link></li>
                  <li><Link to="/usuario" className="link" onClick={toggleMenu}>Home</Link></li>
                </>
              ) : (
                <>
                  <li><Link to="/usuario/cadastro" className="link" onClick={toggleMenu}>Cadastrar-se</Link></li>
                  <li><Link to="/usuario/login" className="link" onClick={toggleMenu}>Login</Link></li>
                </>
              )}
              
              <li><p>Modificar tamanho da fonte</p></li>
              <li><button onClick={aumentarFonte} id="aumentar-fonte-btn" aria-label="Aumentar tamanho da fonte" accessKey="2" tabIndex="2">A+</button></li>
              <li><button onClick={diminuirFonte} id="diminuir-fonte-btn" aria-label="Diminuir tamanho da fonte" accessKey="3" tabIndex="3">A-</button></li>
              <li><p>Modificar estilo da exibição</p></li>
              <li><button onClick={toggleContraste} id="contraste-btn" aria-pressed="false" aria-label="Ativar ou desativar alto contraste">Alto Contraste</button></li>
              <li><button onClick={() => toggleDaltonismo('protanopia')} aria-label="Simular protanopia">Protanopia</button></li>
              <li><button onClick={() => toggleDaltonismo('deuteranopia')} aria-label="Simular deuteranopia">Deuteranopia</button></li>
              <li><button onClick={() => toggleDaltonismo('tritanopia')} aria-label="Simular tritanopia">Tritanopia</button></li>
              <button onClick={resetarAcessibilidade} aria-label="Restaurar configurações de acessibilidade">Voltar ao normal</button>
            </ul>
          </nav>
        </div>
      </div>
    </header>
  );
};

export default Header;