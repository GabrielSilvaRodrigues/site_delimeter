import React, { useState } from 'react';
import { useAuth } from '../../contexts/AuthContext';
import { useNavigate, Link } from 'react-router-dom';
import './Login.css';

const Login = () => {
  const [email, setEmail] = useState('');
  const [senha, setSenha] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  
  const { login } = useAuth();
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');

    const result = await login(email, senha);
    
    if (result.success) {
      navigate(`/${result.user.tipo}`);
    } else {
      setError(result.error);
    }
    
    setLoading(false);
  };

  return (
    <main className="login-main">
      <div className="container-calc">
        <form onSubmit={handleSubmit} className="login-form">
          <div className="container">
            <h1>Entrar</h1>
            
            {error && (
              <div className="error-message">
                {error}
              </div>
            )}
            
            <div className="form-group">
              <label htmlFor="email_usuario">Email:</label>
              <input
                type="email"
                id="email_usuario"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
                disabled={loading}
              />
            </div>
            
            <div className="form-group">
              <label htmlFor="senha_usuario">Senha:</label>
              <input
                type="password"
                id="senha_usuario"
                value={senha}
                onChange={(e) => setSenha(e.target.value)}
                required
                disabled={loading}
              />
            </div>
            
            <button type="submit" disabled={loading}>
              {loading ? 'Entrando...' : 'Entrar'}
            </button>
            
            <div className="login-links">
              <p>
                Ainda não tem conta? 
                <Link to="/usuario/cadastro"> Cadastre-se aqui</Link>
              </p>
            </div>
          </div>
        </form>
      </div>
    </main>
  );
};

export default Login;