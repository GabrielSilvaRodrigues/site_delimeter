import React from 'react';
import './Footer.css';

const Footer = () => {
  const currentYear = new Date().getFullYear();

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
        <a href="#">Política de Privacidade</a> |
        <a href="#">Contato</a> |
        <a href="#">Termos de uso</a>
      </div>
      
      <p>&copy; {currentYear} - Deliméter LTDA - Todos os direitos reservados.</p>
    </footer>
  );
};

export default Footer;