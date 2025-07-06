import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import './Auth.css';

const Register = () => {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    nome_usuario: '',
    email_usuario: '',
    senha_usuario: '',
    confirmar_senha: ''
  });
  
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [userType, setUserType] = useState('');

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({
      ...formData,
      [name]: value
    });
  };

  const validateForm = () => {
    if (!formData.nome_usuario || !formData.email_usuario || !formData.senha_usuario || !formData.confirmar_senha) {
      setError('Todos os campos são obrigatórios.');
      return false;
    }
    
    if (formData.senha_usuario !== formData.confirmar_senha) {
      setError('As senhas não coincidem.');
      return false;
    }
    
    if (formData.senha_usuario.length < 6) {
      setError('A senha deve ter pelo menos 6 caracteres.');
      return false;
    }
    
    if (!formData.email_usuario.includes('@')) {
      setError('Digite um email válido.');
      return false;
    }
    
    return true;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    if (!validateForm()) return;
    
    setLoading(true);
    setError('');
    
    try {
      // Em uma implementação real, aqui seria feita uma requisição para a API
      // Por enquanto, simulamos com localStorage
      
      // Simular registro bem-sucedido
      const userData = {
        id: Date.now(), // ID temporário
        nome: formData.nome_usuario,
        email: formData.email_usuario,
        tipo: 'usuario' // Tipo padrão
      };
      
      // Armazenar dados temporariamente
      localStorage.setItem('tempUserData', JSON.stringify(userData));
      
      // Mostrar opções de tipo de usuário
      setUserType('selection');
      
    } catch (err) {
      setError('Erro ao cadastrar. Tente novamente mais tarde.');
      console.error('Erro de cadastro:', err);
    } finally {
      setLoading(false);
    }
  };

  // Se o usuário já escolheu criar conta e agora está escolhendo o tipo
  if (userType === 'selection') {
    return (
      <main>
        <div className="container-calc">
          <div className="container">
            <h1>Escolha seu tipo de conta</h1>
            <p className="info-text">Selecione o tipo de conta que deseja criar:</p>
            
            <div className="user-type-selection">
              <Link to="/paciente/cadastro" className="user-type-option">
                <div className="icon">🧑‍🦱</div>
                <h3>Paciente</h3>
                <p>Para receber acompanhamento nutricional e de saúde</p>
              </Link>
              
              <Link to="/nutricionista/cadastro" className="user-type-option">
                <div className="icon">🥗</div>
                <h3>Nutricionista</h3>
                <p>Para oferecer serviços de nutrição</p>
              </Link>
              
              <Link to="/medico/cadastro" className="user-type-option">
                <div className="icon">🩺</div>
                <h3>Médico</h3>
                <p>Para oferecer serviços médicos</p>
              </Link>
            </div>
          </div>
        </div>
      </main>
    );
  }

  return (
    <main>
      <div className="container-calc">
        <form onSubmit={handleSubmit} className="auth-form">
          <div className="container">
            <h1>Cadastro de Usuário</h1>
            
            {error && <div className="error-message">{error}</div>}
            
            <div className="form-group">
              <label htmlFor="nome_usuario">Nome:</label>
              <input 
                type="text" 
                name="nome_usuario" 
                id="nome_usuario"
                value={formData.nome_usuario}
                onChange={handleChange}
                required 
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
            
            <div className="form-group">
              <label htmlFor="confirmar_senha">Confirmar Senha:</label>
              <input 
                type="password" 
                name="confirmar_senha" 
                id="confirmar_senha"
                value={formData.confirmar_senha}
                onChange={handleChange}
                required 
              />
            </div>
            
            <button 
              type="submit"
              disabled={loading}
            >
              {loading ? 'Cadastrando...' : 'Cadastrar'}
            </button>
            
            <p className="auth-redirect">
              Já tem uma conta? <Link to="/usuario/login">Entrar</Link>
            </p>
          </div>
        </form>
      </div>
    </main>
  );
};

export default Register;