import React, { useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import NutritionalCalculator from '../../components/NutritionalCalculator/NutritionalCalculator';
import './Medico.css';

const Medico = () => {
  const { user } = useAuth();
  const navigate = useNavigate();

  useEffect(() => {
    if (!user) {
      navigate('/usuario/login');
    }
  }, [user, navigate]);

  if (!user) {
    return <div>Carregando...</div>;
  }

  return (
    <div className="usuario-container medico-container">
      <main className="usuario-main-content">
        <div className="usuario-header medico-header">
          <h1>🩺 Bem-vindo ao Painel do Médico</h1>
          <p>Gerencie pacientes, visualize históricos e acesse ferramentas clínicas.</p>
        </div>

        <section className="usuario-section" id="home">
          <h2>🏥 Início</h2>
          <p>
            Bem-vindo ao seu <strong>painel médico</strong>! Aqui você pode acompanhar 
            pacientes, acessar prontuários e utilizar ferramentas clínicas.<br />
            <span className="highlight">Use o menu lateral para navegar.</span>
          </p>
        </section>

        <section className="usuario-section" id="calculator">
          <h2>📊 Calculadora Nutricional</h2>
          <p>Utilize nossa calculadora para avaliar pacientes:</p>
          <NutritionalCalculator />
        </section>

        <section className="usuario-section" id="about">
          <h2>ℹ️ Sobre</h2>
          <p>
            Este sistema foi desenvolvido para facilitar o <strong>acompanhamento clínico</strong>, 
            centralizando informações e otimizando o atendimento.
          </p>
          <ul className="features-list">
            <li>Gerencie dados dos pacientes</li>
            <li>Acesse históricos e prontuários</li>
            <li>Ferramentas de apoio à decisão clínica</li>
            <li>Integração com outros profissionais</li>
          </ul>
        </section>

        <section className="usuario-section" id="services">
          <h2>🛠️ Serviços</h2>
          <div className="services-grid">
            <div className="service-card">
              <div className="service-icon">👥</div>
              <h3>Pacientes</h3>
              <p>Gerencie prontuários e consultas</p>
            </div>
            
            <div className="service-card">
              <div className="service-icon">📋</div>
              <h3>Prescrições</h3>
              <p>Crie e gerencie prescrições médicas</p>
            </div>
            
            <div className="service-card">
              <div className="service-icon">📊</div>
              <h3>Relatórios</h3>
              <p>Análises clínicas e estatísticas</p>
            </div>
          </div>
        </section>

        <section className="usuario-section" id="contact">
          <h2>📞 Contato</h2>
          <p>
            Precisa de suporte? Fale com nossa equipe:<br />
            <strong>Email:</strong> 
            <a href="mailto:suporte@delimeter.com">suporte@delimeter.com</a><br />
            Ou utilize o formulário disponível no site.
          </p>
        </section>
      </main>
    </div>
  );
};

export default Medico;