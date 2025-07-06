import React from 'react';
import './Home.css';

const Home = () => {
  return (
    <div className="home">
      <section className="hero">
        <div className="hero-content">
          <img src="/assets/images/pexels-fauxels-3184195.jpg" alt="Alimentação saudável" className="hero-image" />
          <div className="hero-text">
            <h1>PRIORIZAMOS A SUA ALIMENTAÇÃO</h1>
            <p>Uma plataforma completa voltada à alimentação e saúde</p>
          </div>
        </div>
      </section>

      <section className="features">
        <div className="container">
          <div className="feature-grid">
            <div className="feature-card">
              <h2>Sobre o Delímiter</h2>
              <p>Uma plataforma nova voltada à alimentação</p>
              <a href="/about" className="feature-link">Saiba mais</a>
            </div>
            
            <div className="feature-card">
              <h2>Dados métricos</h2>
              <p>Calcule o seu gasto energético basal</p>
              <a href="/calculo" className="feature-link">Saiba mais</a>
            </div>
          </div>
        </div>
      </section>

      <section className="partnerships">
        <div className="container">
          <h2>PARCERIAS</h2>
          <p>Conheça nossos parceiros</p>
          <div className="partner-logos">
            <img src="/assets/images/sus.jpeg" alt="SUS" />
            <img src="/assets/images/crn3.jpeg" alt="CRN3" />
            <img src="/assets/images/cremesp.jpeg" alt="CREMESP" />
          </div>
        </div>
      </section>

      <section className="services">
        <div className="container">
          <h2>FUNCIONALIDADES</h2>
          <div className="service-grid">
            <div className="service-card">
              <img src="/assets/images/nutricionista.jpg" alt="Nutricionista" />
              <h3>Marque uma consulta com uma nutricionista</h3>
              <p>Para acompanhar sua alimentação.</p>
            </div>
            
            <div className="service-card">
              <img src="/assets/images/dieta.jpg" alt="Dieta" />
              <h3>Dietas focadas no seu perfil</h3>
              <p>Através do mapeamento de dados.</p>
            </div>
            
            <div className="service-card">
              <img src="/assets/images/crianca.jpg" alt="Criança" />
              <h3>Plano alimentar junto ao cadúnico</h3>
              <p>Associar famílias de baixa renda a fornecedores de alimentos.</p>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};

export default Home;