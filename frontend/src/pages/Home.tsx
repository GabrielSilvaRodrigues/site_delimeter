import React, { useState, useEffect } from 'react';
import { delimeterService } from '../services/api';
import './Home.css';

interface HomeData {
  title: string;
  description: string;
  features: string[];
}

const Home: React.FC = () => {
  const [data, setData] = useState<HomeData | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await delimeterService.getHome();
        if (response.success) {
          setData(response.data);
        } else {
          setError('Erro ao carregar dados da página inicial');
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
    <div className="home">
      <section className="hero">
        <div className="container">
          <div className="hero-content">
            <h1>{data?.title}</h1>
            <p className="hero-description">{data?.description}</p>
            <div className="hero-buttons">
              <a href="/calculo" className="btn btn-primary">
                Começar Cálculo
              </a>
              <a href="/sobre" className="btn btn-secondary">
                Saiba Mais
              </a>
            </div>
          </div>
          <div className="hero-image">
            <img src="/assets/images/delimeter.png" alt="Deliméter" />
          </div>
        </div>
      </section>

      <section className="features">
        <div className="container">
          <h2>Funcionalidades</h2>
          <div className="features-grid">
            {data?.features.map((feature, index) => (
              <div key={index} className="feature-card">
                <div className="feature-icon">
                  <span>✨</span>
                </div>
                <h3>{feature}</h3>
              </div>
            ))}
          </div>
        </div>
      </section>

      <section className="about-preview">
        <div className="container">
          <div className="about-content">
            <h2>Cuide da sua saúde de forma inteligente</h2>
            <p>
              O Deliméter oferece ferramentas precisas para o cálculo nutricional,
              ajudando você a manter uma vida mais saudável e equilibrada.
            </p>
            <div className="stats">
              <div className="stat">
                <h3>IMC</h3>
                <p>Índice de Massa Corporal</p>
              </div>
              <div className="stat">
                <h3>GET</h3>
                <p>Gasto Energético Total</p>
              </div>
              <div className="stat">
                <h3>Macros</h3>
                <p>Macronutrientes</p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};

export default Home;
