import React from 'react';
import './Footer.css';

const Footer: React.FC = () => {
  return (
    <footer className="footer">
      <div className="container">
        <div className="footer-content">
          <div className="footer-section">
            <h3>Deliméter</h3>
            <p>Seu portal para uma vida mais saudável, inteligente e conectada!</p>
          </div>
          
          <div className="footer-section">
            <h4>Links Úteis</h4>
            <ul>
              <li><a href="/">Início</a></li>
              <li><a href="/calculo">Calculadora</a></li>
              <li><a href="/sobre">Sobre</a></li>
            </ul>
          </div>
          
          <div className="footer-section">
            <h4>Contato</h4>
            <p>Email: projetodelimeter@gmail.com</p>
          </div>
          
          <div className="footer-section">
            <h4>Redes Sociais</h4>
            <div className="social-links">
              <a href="#" aria-label="Instagram">
                <img src="/assets/images/instagram.png" alt="Instagram" />
              </a>
              <a href="#" aria-label="LinkedIn">
                <img src="/assets/images/linkedin.png" alt="LinkedIn" />
              </a>
              <a href="#" aria-label="WhatsApp">
                <img src="/assets/images/whatsapp.png" alt="WhatsApp" />
              </a>
            </div>
          </div>
        </div>
        
        <div className="footer-bottom">
          <p>&copy; 2025 Deliméter. Todos os direitos reservados.</p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
