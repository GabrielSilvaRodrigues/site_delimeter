import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import './Account.css';

const Account = () => {
  const navigate = useNavigate();
  const [userSession, setUserSession] = useState(null);
  const [userData, setUserData] = useState({
    nome_usuario: '',
    email_usuario: '',
    senha_usuario: '',
    tipo: ''
  });
  
  const [profileData, setProfileData] = useState({});
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState({ text: '', type: '' });

  // Carregar dados do usuário ao montar o componente
  useEffect(() => {
    // Em uma implementação real, isso seria uma chamada à API
    const sessionData = localStorage.getItem('userSession');
    
    if (!sessionData) {
      navigate('/usuario/login');
      return;
    }
    
    const user = JSON.parse(sessionData);
    setUserSession(user);
    setUserData({
      nome_usuario: user.nome || '',
      email_usuario: user.email || '',
      senha_usuario: '',
      tipo: user.tipo || 'usuario'
    });
    
    // Carrega dados específicos do tipo de usuário
    if (user.tipo === 'paciente') {
      setProfileData({
        cpf: user.cpf || '123.456.789-00',
        nis: user.nis || '12345678901'
      });
    } else if (user.tipo === 'nutricionista') {
      setProfileData({
        crm_nutricionista: user.crm || 'CRN-12345',
        cpf: user.cpf || '123.456.789-00'
      });
    } else if (user.tipo === 'medico') {
      setProfileData({
        crm_medico: user.crm || 'CRM-12345',
        cpf: user.cpf || '123.456.789-00'
      });
    }
  }, [navigate]);

  const handleChange = (e, formType = 'main') => {
    const { name, value } = e.target;
    
    if (formType === 'main') {
      setUserData({
        ...userData,
        [name]: value
      });
    } else {
      setProfileData({
        ...profileData,
        [name]: value
      });
    }
  };

  const handleMainFormSubmit = (e) => {
    e.preventDefault();
    setLoading(true);
    
    // Simular atualização de dados
    setTimeout(() => {
      // Atualizar dados do usuário no localStorage
      const updatedUser = {
        ...userSession,
        nome: userData.nome_usuario,
        email: userData.email_usuario
      };
      
      localStorage.setItem('userSession', JSON.stringify(updatedUser));
      setUserSession(updatedUser);
      
      setMessage({ text: 'Dados atualizados com sucesso!', type: 'success' });
      setLoading(false);
    }, 1000);
  };

  const handleProfileFormSubmit = (e) => {
    e.preventDefault();
    setLoading(true);
    
    // Simular atualização de dados específicos
    setTimeout(() => {
      // Atualizar dados específicos do usuário no localStorage
      const updatedUser = {
        ...userSession,
        ...profileData
      };
      
      localStorage.setItem('userSession', JSON.stringify(updatedUser));
      setUserSession(updatedUser);
      
      setMessage({ text: 'Dados específicos atualizados com sucesso!', type: 'success' });
      setLoading(false);
    }, 1000);
  };

  const handleDeleteAccount = () => {
    const confirmDelete = window.confirm('Tem certeza que deseja deletar sua conta? Esta ação não poderá ser desfeita!');
    
    if (confirmDelete) {
      setLoading(true);
      
      // Simular exclusão de conta
      setTimeout(() => {
        localStorage.removeItem('userSession');
        navigate('/');
        setLoading(false);
      }, 1000);
    }
  };

  const handleLogout = () => {
    localStorage.removeItem('userSession');
    navigate('/');
  };

  if (!userSession) {
    return <div className="loading">Carregando...</div>;
  }

  return (
    <main>
      <div className="container-calc">
        <div className="container account-container">
          <h1>Minha Conta</h1>
          
          {message.text && (
            <div className={`message ${message.type}`}>
              {message.text}
            </div>
          )}
          
          <form onSubmit={handleMainFormSubmit} id="formulario-conta">
            <div className="form-group">
              <label htmlFor="nome_usuario">Nome:</label>
              <input 
                type="text" 
                name="nome_usuario" 
                id="nome_usuario" 
                value={userData.nome_usuario}
                onChange={(e) => handleChange(e)}
                required 
              />
            </div>
            
            <div className="form-group">
              <label htmlFor="email_usuario">Email:</label>
              <input 
                type="email" 
                name="email_usuario" 
                id="email_usuario"
                value={userData.email_usuario}
                onChange={(e) => handleChange(e)}
                required 
              />
            </div>
            
            <div className="form-group">
              <label htmlFor="senha_usuario">Nova Senha:</label>
              <input 
                type="password" 
                name="senha_usuario" 
                id="senha_usuario"
                value={userData.senha_usuario}
                onChange={(e) => handleChange(e)}
                placeholder="Deixe em branco para não alterar" 
              />
            </div>
            
            <button 
              type="submit"
              disabled={loading}
            >
              {loading ? 'Atualizando...' : 'Atualizar Dados'}
            </button>
          </form>
          
          {/* Formulário específico com base no tipo de usuário */}
          {userData.tipo === 'paciente' && (
            <form onSubmit={handleProfileFormSubmit} id="formulario-paciente" className="profile-form">
              <h2>Dados do Paciente</h2>
              <div className="form-group">
                <label htmlFor="cpf">CPF:</label>
                <input 
                  type="text" 
                  name="cpf" 
                  id="cpf"
                  value={profileData.cpf || ''}
                  onChange={(e) => handleChange(e, 'profile')}
                  required 
                />
              </div>
              
              <div className="form-group">
                <label htmlFor="nis">NIS:</label>
                <input 
                  type="text" 
                  name="nis" 
                  id="nis"
                  value={profileData.nis || ''}
                  onChange={(e) => handleChange(e, 'profile')} 
                />
              </div>
              
              <button 
                type="submit"
                disabled={loading}
              >
                {loading ? 'Atualizando...' : 'Atualizar Dados do Paciente'}
              </button>
            </form>
          )}
          
          {userData.tipo === 'nutricionista' && (
            <form onSubmit={handleProfileFormSubmit} id="formulario-nutricionista" className="profile-form">
              <h2>Dados do Nutricionista</h2>
              <div className="form-group">
                <label htmlFor="crm_nutricionista">CRN:</label>
                <input 
                  type="text" 
                  name="crm_nutricionista" 
                  id="crm_nutricionista"
                  value={profileData.crm_nutricionista || ''}
                  onChange={(e) => handleChange(e, 'profile')}
                  required 
                />
              </div>
              
              <div className="form-group">
                <label htmlFor="cpf_nutricionista">CPF:</label>
                <input 
                  type="text" 
                  name="cpf" 
                  id="cpf_nutricionista"
                  value={profileData.cpf || ''}
                  onChange={(e) => handleChange(e, 'profile')}
                  required 
                />
              </div>
              
              <button 
                type="submit"
                disabled={loading}
              >
                {loading ? 'Atualizando...' : 'Atualizar Dados do Nutricionista'}
              </button>
            </form>
          )}
          
          {userData.tipo === 'medico' && (
            <form onSubmit={handleProfileFormSubmit} id="formulario-medico" className="profile-form">
              <h2>Dados do Médico</h2>
              <div className="form-group">
                <label htmlFor="crm_medico">CRM:</label>
                <input 
                  type="text" 
                  name="crm_medico" 
                  id="crm_medico"
                  value={profileData.crm_medico || ''}
                  onChange={(e) => handleChange(e, 'profile')}
                  required 
                />
              </div>
              
              <div className="form-group">
                <label htmlFor="cpf_medico">CPF:</label>
                <input 
                  type="text" 
                  name="cpf" 
                  id="cpf_medico"
                  value={profileData.cpf || ''}
                  onChange={(e) => handleChange(e, 'profile')}
                  required 
                />
              </div>
              
              <button 
                type="submit"
                disabled={loading}
              >
                {loading ? 'Atualizando...' : 'Atualizar Dados do Médico'}
              </button>
            </form>
          )}
          
          <div className="account-actions">
            <button 
              onClick={handleDeleteAccount}
              className="delete-btn"
              disabled={loading}
            >
              Deletar Conta
            </button>
            
            <button 
              onClick={handleLogout}
              className="logout-btn"
              disabled={loading}
            >
              Sair
            </button>
          </div>
        </div>
      </div>
    </main>
  );
};

export default Account;