import React, { useState } from 'react';
import { useAuth } from '../../contexts/AuthContext';
import { useNavigate } from 'react-router-dom';
import './Login.css';

const Login = () => {
  const [formData, setFormData] = useState({
    email_usuario: '',
    senha_usuario: ''
  });
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  
  const { login } = useAuth();
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

    try {
      const result = await login(formData);
      
      if (result.success) {
        // Redirecionar baseado no tipo de usuário
        const userType = result.user.tipo || 'usuario';
        navigate(`/${userType}`);
      } else {
        setError(result.error || 'Erro ao fazer login');
      }
    } catch (error) {
      setError('Erro inesperado. Tente novamente.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <main className="login-main">
      <div className="container-calc">
        <form onSubmit={handleSubmit} className="login-form">
          <div className="container">
            <h1>Entrar</h1>
            
            {error && (
              <div className="error-message">{error}</div>
            )}
            
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
            
            <button type="submit" disabled={loading}>
              {loading ? 'Entrando...' : 'Entrar'}
            </button>
            
            <div className="login-links">
              <p>
                Não tem uma conta? 
                <a href="/register" className="link"> Cadastre-se aqui</a>
              </p>
            </div>
          </div>
        </form>
      </div>
    </main>
  );
};

export default Login;