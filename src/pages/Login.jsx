import React, { useState } from 'react';
import './Login.css';

const Login = ({ setUser }) => {
  const [formData, setFormData] = useState({
    email_usuario: '',
    senha_usuario: ''
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');

    try {
      const response = await fetch('/login/usuario', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
      });

      const data = await response.json();

      if (data.success) {
        localStorage.setItem('user', JSON.stringify(data.usuario));
        setUser(data.usuario);
      } else {
        setError(data.error || 'Erro ao fazer login');
      }
    } catch (error) {
      setError('Erro de conexão');
    }

    setLoading(false);
  };

  return (
    <div className="login-page">
      <div className="login-container">
        <form onSubmit={handleSubmit} className="login-form">
          <h1>Entrar</h1>
          
          {error && <div className="error-message">{error}</div>}
          
          <div className="form-group">
            <label htmlFor="email_usuario">Email:</label>
            <input
              type="email"
              id="email_usuario"
              name="email_usuario"
              value={formData.email_usuario}
              onChange={handleChange}
              required
            />
          </div>
          
          <div className="form-group">
            <label htmlFor="senha_usuario">Senha:</label>
            <input
              type="password"
              id="senha_usuario"
              name="senha_usuario"
              value={formData.senha_usuario}
              onChange={handleChange}
              required
            />
          </div>
          
          <button type="submit" disabled={loading}>
            {loading ? 'Entrando...' : 'Entrar'}
          </button>
          
          <p>
            Não tem conta? <a href="/register">Cadastre-se aqui</a>
          </p>
        </form>
      </div>
    </div>
  );
};

export default Login;