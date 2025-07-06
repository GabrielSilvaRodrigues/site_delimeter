import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import './Footer.css';

const Footer = () => {
  const [userSession, setUserSession] = useState(null);
  
  // Simulando a verificação de sessão do usuário
  useEffect(() => {
    const userData = localStorage.getItem('userSession');
    if (userData) {
      setUserSession(JSON.parse(userData));
    }
  }, []);

  return (
    <footer className="usuario-footer">
      <div className="social">
        <a href="https://www.instagram.com/delim3ter/" target="_blank" rel="noopener noreferrer">
          <img src="/public/assets/images/instagram.png" alt="Instagram" />
        </a>
        <a href="#" target="_blank" rel="noopener noreferrer">
          <img src="/public/assets/images/whatsapp.png" alt="WhatsApp" />
        </a>
        <a href="#" target="_blank" rel="noopener noreferrer">
          <img src="/public/assets/images/linkedin.png" alt="LinkedIn" />
        </a>
      </div>
      <div className="links">
        <Link to="#">Política de Privacidade</Link> |
        <Link to="#">Contato</Link> |
        <Link to="#">Termos de uso</Link>
      </div>
      <p>&copy; {new Date().getFullYear()} - Deliméter LTDA - Todos os direitos reservados.</p>
    </footer>
  );
};

export default Footer;