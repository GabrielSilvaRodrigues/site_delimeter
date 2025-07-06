import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import './Register.css';

const Register = () => {
  const [formData, setFormData] = useState({
    nome_usuario: '',
    email_usuario: '',
    senha_usuario: '',
    confirmar_senha: ''
  });
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  
  const navigate = useNavigate();

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

    // Validar senhas
    if (formData.senha_usuario !== formData.confirmar_senha) {
      setError('As senhas não coincidem');
      setLoading(false);
      return;
    }

    try {
      const response = await fetch('/api/usuario', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          nome_usuario: formData.nome_usuario,
          email_usuario: formData.email_usuario,
          senha_usuario: formData.senha_usuario
        })
      });

      const data = await response.json();

      if (data.success) {
        // Redirecionar para login
        navigate('/login');
      } else {
        setError(data.error || 'Erro ao criar conta');
      }
    } catch (error) {
      setError('Erro de conexão');
    } finally {
      setLoading(false);
    }
  };

  return (
    <main className="register-main">
      <div className="container-calc">
        <form onSubmit={handleSubmit} className="register-form">
          <div className="container">
            <h1>Cadastrar-se</h1>
            
            {error && (
              <div className="error-message">{error}</div>
            )}
            
            <div className="form-group">
              <label htmlFor="nome_usuario">Nome:</label>
              <input
                type="text"
                name="nome_usuario"
                id="nome_usuario"
                value={formData.nome_usuario}
                onChange={handleChange}
                required
                disabled={loading}
              />
            </div>
            
            <div className="form-group">
              <label htmlFor="email_usuario">Email:</label>
              <input
                type="email"
                name="email_usuario"
                id="email_usuario"
                value={formData.email_usuario}
                onChange={handleChange}
                required
                disabled={loading}
              />
            </div>
            
            <div className="form-group">
              <label htmlFor="senha_usuario">Senha:</label>
              <input
                type="password"
                name="senha_usuario"
                id="senha_usuario"
                value={formData.senha_usuario}
                onChange={handleChange}
                required
                disabled={loading}
              />
            </div>
            
            <div className="form-group">
              <label htmlFor="confirmar_senha">Confirmar Senha:</label>
              <input
                type="password"
                name="confirmar_senha"
                id="confirmar_senha"
                value={formData.confirmar_senha}
                onChange={handleChange}
                required
                disabled={loading}
              />
            </div>
            
            <button type="submit" disabled={loading}>
              {loading ? 'Cadastrando...' : 'Cadastrar'}
            </button>
            
            <div className="register-links">
              <p>
                Já tem uma conta? 
                <a href="/login" className="link"> Faça login aqui</a>
              </p>
            </div>
          </div>
        </form>
      </div>
    </main>
  );
};

export default Register;