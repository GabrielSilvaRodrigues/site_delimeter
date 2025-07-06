import React from 'react';
import './Footer.css';

const Footer = () => {
  return (
    <footer className="footer">
      <div className="footer-container">
        <div className="social">
          <a href="https://www.instagram.com/delim3ter/" target="_blank" rel="noopener noreferrer">
            <img src="/assets/images/instagram.png" alt="Instagram" />
          </a>
          <a href="#" target="_blank" rel="noopener noreferrer">
            <img src="/assets/images/whatsapp.png" alt="WhatsApp" />
          </a>
          <a href="#" target="_blank" rel="noopener noreferrer">
            <img src="/assets/images/linkedin.png" alt="LinkedIn" />
          </a>
        </div>
        
        <div className="links">
          <a href="#">Política de Privacidade</a>
          <span> | </span>
          <a href="#">Contato</a>
          <span> | </span>
          <a href="#">Termos de uso</a>
        </div>
        
        <p>&copy; {new Date().getFullYear()} - Deliméter LTDA - Todos os direitos reservados.</p>
      </div>
    </footer>
  );
};

export default Footer;