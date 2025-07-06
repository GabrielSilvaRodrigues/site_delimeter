import React, { useState } from 'react';
import './Login.css';

const Login: React.FC = () => {
  const [formData, setFormData] = useState({
    email: '',
    password: '',
  });

  const [isRegister, setIsRegister] = useState(false);

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // TODO: Implementar autenticação com o backend
    console.log('Form data:', formData);
  };

  return (
    <div className="login">
      <div className="container">
        <div className="login-content">
          <div className="login-form-section">
            <h1>{isRegister ? 'Criar Conta' : 'Entrar'}</h1>
            <p>
              {isRegister 
                ? 'Crie sua conta para acessar todos os recursos do Deliméter'
                : 'Entre com sua conta para acessar o painel personalizado'
              }
            </p>

            <form onSubmit={handleSubmit} className="login-form">
              <div className="form-group">
                <label htmlFor="email">Email</label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  value={formData.email}
                  onChange={handleInputChange}
                  required
                  placeholder="seu@email.com"
                />
              </div>

              <div className="form-group">
                <label htmlFor="password">Senha</label>
                <input
                  type="password"
                  id="password"
                  name="password"
                  value={formData.password}
                  onChange={handleInputChange}
                  required
                  placeholder="Sua senha"
                />
              </div>

              <button type="submit" className="btn btn-primary btn-full">
                {isRegister ? 'Criar Conta' : 'Entrar'}
              </button>
            </form>

            <div className="login-switch">
              {isRegister ? (
                <p>
                  Já tem uma conta?{' '}
                  <button 
                    type="button" 
                    className="link-button"
                    onClick={() => setIsRegister(false)}
                  >
                    Faça login
                  </button>
                </p>
              ) : (
                <p>
                  Não tem uma conta?{' '}
                  <button 
                    type="button" 
                    className="link-button"
                    onClick={() => setIsRegister(true)}
                  >
                    Crie uma conta
                  </button>
                </p>
              )}
            </div>
          </div>

          <div className="login-info-section">
            <h2>Bem-vindo ao Deliméter</h2>
            <div className="info-cards">
              <div className="info-card">
                <span className="icon">📊</span>
                <h3>Histórico Personalizado</h3>
                <p>Acompanhe seus cálculos e evolução ao longo do tempo</p>
              </div>
              <div className="info-card">
                <span className="icon">🎯</span>
                <h3>Metas Personalizadas</h3>
                <p>Defina e acompanhe suas metas de saúde e nutrição</p>
              </div>
              <div className="info-card">
                <span className="icon">💾</span>
                <h3>Dados Salvos</h3>
                <p>Seus dados ficam seguros e sempre disponíveis</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Login;
