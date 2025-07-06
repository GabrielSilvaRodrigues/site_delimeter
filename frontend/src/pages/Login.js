import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import './Auth.css';

const Login = () => {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    email_usuario: '',
    senha_usuario: ''
  });
  
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({
      ...formData,
      [name]: value
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    // Validação básica do formulário
    if (!formData.email_usuario || !formData.senha_usuario) {
      setError('Por favor, preencha todos os campos.');
      return;
    }
    
    setLoading(true);
    setError('');
    
    try {
      // Em uma implementação real, aqui seria feita uma requisição para a API
      // Por enquanto, vamos simular com localStorage
      // (Isso seria substituído pela chamada real à API)
      
      // Simular login bem-sucedido
      const mockUserTypes = ['usuario', 'paciente', 'nutricionista', 'medico'];
      const randomType = mockUserTypes[Math.floor(Math.random() * mockUserTypes.length)];
      
      const userData = {
        id: 1,
        nome: 'Usuário Teste',
        email: formData.email_usuario,
        tipo: randomType
      };
      
      // Armazenar dados do usuário no localStorage
      localStorage.setItem('userSession', JSON.stringify(userData));
      
      // Redirecionar com base no tipo de usuário
      navigate(`/${randomType}`);
      
    } catch (err) {
      setError('Falha no login. Verifique suas credenciais e tente novamente.');
      console.error('Erro de login:', err);
    } finally {
      setLoading(false);
    }
  };

  return (
    <main>
      <div className="container-calc">
        <form onSubmit={handleSubmit} className="auth-form">
          <div className="container">
            <h1>Entrar</h1>
            
            {error && <div className="error-message">{error}</div>}
            
            <div className="form-group">
              <label htmlFor="email_usuario">Email:</label>
              <input 
                type="email" 
                name="email_usuario" 
                id="email_usuario" 
                value={formData.email_usuario}
                onChange={handleChange}
                required 
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
              />
            </div>
            
            <button 
              type="submit"
              disabled={loading}
            >
              {loading ? 'Entrando...' : 'Entrar'}
            </button>
          </div>
        </form>
      </div>
    </main>
  );
};

export default Login;