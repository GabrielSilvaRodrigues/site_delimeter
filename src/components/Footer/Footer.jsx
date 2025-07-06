import React from 'react';
import './Footer.css';

const Footer = () => {
  return (
    <footer className="app-footer">
      <div className="footer-content">
        <div className="social-links">
          <a href="https://www.instagram.com/delim3ter/" target="_blank" rel="noopener noreferrer">
            <img src="/assets/images/instagram.png" alt="Instagram" />
          </a>
          <a href="#" aria-label="WhatsApp">
            <img src="/assets/images/whatsapp.png" alt="WhatsApp" />
          </a>
          <a href="#" aria-label="LinkedIn">
            <img src="/assets/images/linkedin.png" alt="LinkedIn" />
          </a>
        </div>
        
        <div className="footer-links">
          <a href="/privacy">Política de Privacidade</a>
          <span>|</span>
          <a href="/contact">Contato</a>
          <span>|</span>
          <a href="/terms">Termos de Uso</a>
        </div>
        
        <div className="copyright">
          <p>&copy; {new Date().getFullYear()} - Deliméter LTDA - Todos os direitos reservados.</p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;