import React, { useState, useEffect } from 'react';
import { delimeterService } from '../services/api';
import './Sobre.css';

interface SobreData {
  title: string;
  description: string;
  mission: string;
  technologies: string[];
}

const Sobre: React.FC = () => {
  const [data, setData] = useState<SobreData | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await delimeterService.getSobre();
        if (response.success) {
          setData(response.data);
        } else {
          setError('Erro ao carregar dados da página sobre');
        }
      } catch (err) {
        setError('Erro de conexão com o servidor');
        console.error('Erro:', err);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  if (loading) {
    return <div className="loading">Carregando...</div>;
  }

  if (error) {
    return <div className="error">{error}</div>;
  }

  return (
    <div className="sobre">
      <div className="container">
        <header className="page-header">
          <h1>{data?.title}</h1>
          <p className="page-description">{data?.description}</p>
        </header>

        <section className="mission">
          <div className="mission-content">
            <h2>Nossa Missão</h2>
            <p>{data?.mission}</p>
          </div>
          <div className="mission-image">
            <img src="/assets/images/nutricionista.jpg" alt="Nutricionista" />
          </div>
        </section>

        <section className="technologies">
          <h2>Tecnologias Utilizadas</h2>
          <div className="tech-grid">
            {data?.technologies.map((tech, index) => (
              <div key={index} className="tech-card">
                <div className="tech-icon">
                  <span>⚡</span>
                </div>
                <h3>{tech}</h3>
              </div>
            ))}
          </div>
        </section>

        <section className="accessibility">
          <h2>Acessibilidade</h2>
          <div className="accessibility-content">
            <p>
              O Deliméter foi desenvolvido pensando na acessibilidade para todos.
              Oferecemos recursos como:
            </p>
            <ul>
              <li>Alto contraste para facilitar a leitura</li>
              <li>Ajuste do tamanho da fonte</li>
              <li>Navegação por teclado</li>
              <li>Compatibilidade com leitores de tela</li>
              <li>Interface responsiva para todos os dispositivos</li>
            </ul>
          </div>
        </section>

        <section className="team">
          <h2>Equipe</h2>
          <div className="team-content">
            <p>
              O projeto Deliméter é desenvolvido por uma equipe dedicada
              a promover saúde e bem-estar através da tecnologia.
            </p>
            <div className="contact-info">
              <h3>Entre em contato</h3>
              <p>Email: projetodelimeter@gmail.com</p>
            </div>
          </div>
        </section>
      </div>
    </div>
  );
};

export default Sobre;
