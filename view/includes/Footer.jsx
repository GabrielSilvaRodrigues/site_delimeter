import React from 'react';
import './Footer.css'; // Importando o CSS específico para o Footer

const Footer = () => {
    return (
        <footer className="usuario-footer" style={{ marginTop: '50px' }}>
            <div className="social">
                <a href="https://www.instagram.com/delim3ter/">
                    <img src="/assets/images/instagram.png" alt="Instagram" />
                </a>
                <a href="#">
                    <img src="/assets/images/whatsapp.png" alt="WhatsApp" />
                </a>
                <a href="#">
                    <img src="/assets/images/linkedin.png" alt="LinkedIn" />
                </a>
            </div>
            <div className="links">
                <a href="#">Política de Privacidade</a> |
                <a href="#">Contato</a> |
                <a href="#">Termos de uso</a>
            </div>
            <p>&copy; {new Date().getFullYear()} - Deliméter LTDA - Todos os direitos reservados.</p>
        </footer>
    );
}

export default Footer;
