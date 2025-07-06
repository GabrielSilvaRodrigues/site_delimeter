import React, { useEffect } from 'react';
import { useAuth } from '../../contexts/AuthContext';
import { useNavigate } from 'react-router-dom';
import './Home.css';

const Home = () => {
  const { user } = useAuth();
  const navigate = useNavigate();

  useEffect(() => {
    // Se usuário está logado, redireciona para seu painel
    if (user && user.tipo) {
      navigate(`/${user.tipo}`);
    }
  }, [user, navigate]);

  return (
    <main className="home-main">
      <section className="container-main">
        <div className="container-main-image">
          <img src="/assets/images/pexels-fauxels-3184195.jpg" alt="Alimentação saudável" />
          <h1>PRIORIZAMOS A SUA ALIMENTAÇÃO</h1>
        </div>
        
        <div className="caixas">
          <div className="caixaAlfa">
            <h2>Sobre o Delímiter</h2>
            <p>Uma plataforma nova voltada à alimentação</p>
            <a href="/about" className="link">Saiba mais</a>
          </div>
          <div className="caixaAlfa">
            <h2>Dados métricos</h2>
            <p>Calcule o seu gasto energético basal</p>
            <a href="/calculo" className="link">Saiba mais</a>
          </div>
        </div>

        <div className="parceiros">
          <h2>PARCERIAS</h2>
          <p>Conheça nossos parceiros</p>
          <div className="logos">
            <img src="/assets/images/sus.jpeg" alt="SUS" />
            <img src="/assets/images/crn3.jpeg" alt="CRN3" />
            <img src="/assets/images/cremesp.jpeg" alt="CREMESP" />
          </div>
        </div>
      </section>

      <section className="funcionalidades">
        <h2>FUNCIONALIDADES</h2>
        <div className="caixas">
          <div className="caixa">
            <img src="/assets/images/nutricionista.jpg" alt="Nutricionista" />
            <h3>Marque uma consulta com uma nutricionista</h3>
            <p>Para acompanhar sua alimentação.</p>
          </div>
          <div className="caixa">
            <img src="/assets/images/medico.jpg" alt="Médico" />
            <h3>Acompanhamento médico</h3>
            <p>Profissionais especializados em nutrição.</p>
          </div>
          <div className="caixa">
            <img src="/assets/images/calculadora.jpg" alt="Calculadora" />
            <h3>Ferramentas de cálculo</h3>
            <p>Calcule seu gasto energético e macros.</p>
          </div>
        </div>
      </section>
    </main>
  );
};

export default Home;