import React, { useState, useEffect } from 'react';
import { useAuth } from '../../contexts/AuthContext';
import { useNavigate } from 'react-router-dom';
import './Conta.css';

const Conta = () => {
  const { user, logout } = useAuth();
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    nome_usuario: '',
    email_usuario: '',
    senha_usuario: ''
  });
  const [profileData, setProfileData] = useState({});
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState('');

  useEffect(() => {
    if (!user) {
      navigate('/usuario/login');
      return;
    }

    // Preenche os dados do usuário
    setFormData({
      nome_usuario: user.nome_usuario || '',
      email_usuario: user.email_usuario || '',
      senha_usuario: ''
    });

    // Carrega dados específicos do tipo de usuário
    loadProfileData();
  }, [user, navigate]);

  const loadProfileData = async () => {
    if (!user || user.tipo === 'usuario') return;

    try {
      const response = await fetch(`/${user.tipo}/conta`, {
        credentials: 'include'
      });
      
      if (response.ok) {
        const data = await response.json();
        setProfileData(data);
      }
    } catch (error) {
      console.error('Erro ao carregar dados do perfil:', error);
    }
  };

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
  };

  const handleProfileChange = (e) => {
    setProfileData({
      ...profileData,
      [e.target.name]: e.target.value
    });
  };

  const handleUserUpdate = async (e) => {
    e.preventDefault();
    setLoading(true);
    setMessage('');

    try {
      const response = await fetch('/conta/atualizar', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData),
        credentials: 'include'
      });

      if (response.ok) {
        setMessage('Dados atualizados com sucesso!');
      } else {
        setMessage('Erro ao atualizar dados.');
      }
    } catch (error) {
      setMessage('Erro de conexão.');
    }
    
    setLoading(false);
  };

  const handleProfileUpdate = async (e) => {
    e.preventDefault();
    setLoading(true);
    setMessage('');

    try {
      const response = await fetch(`/${user.tipo}/conta/atualizar`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(profileData),
        credentials: 'include'
      });

      if (response.ok) {
        setMessage('Dados do perfil atualizados com sucesso!');
      } else {
        setMessage('Erro ao atualizar dados do perfil.');
      }
    } catch (error) {
      setMessage('Erro de conexão.');
    }
    
    setLoading(false);
  };

  const handleDeleteAccount = async () => {
    if (!confirm('Tem certeza que deseja deletar sua conta? Esta ação não poderá ser desfeita!')) {
      return;
    }

    try {
      const response = await fetch('/conta/deletar', {
        method: 'POST',
        credentials: 'include'
      });

      if (response.ok) {
        logout();
        navigate('/delimeter');
      } else {
        setMessage('Erro ao deletar conta.');
      }
    } catch (error) {
      setMessage('Erro de conexão.');
    }
  };

  const handleLogout = () => {
    logout();
    navigate('/delimeter');
  };

  if (!user) {
    return <div>Carregando...</div>;
  }

  return (
    <main className="conta-main">
      <div className="container-calc">
        <div className="container">
          <h1>Minha Conta</h1>
          
          {message && (
            <div className={`message ${message.includes('sucesso') ? 'success' : 'error'}`}>
              {message}
            </div>
          )}

          {/* Dados do usuário */}
          <form onSubmit={handleUserUpdate} className="conta-form">
            <h2>Dados do Usuário</h2>
            
            <div className="form-group">
              <label htmlFor="nome_usuario">Nome:</label>
              <input
                type="text"
                id="nome_usuario"
                name="nome_usuario"
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
                id="email_usuario"
                name="email_usuario"
                value={formData.email_usuario}
                onChange={handleChange}
                required
                disabled={loading}
              />
            </div>
            
            <div className="form-group">
              <label htmlFor="senha_usuario">Nova Senha (deixe em branco para manter a atual):</label>
              <input
                type="password"
                id="senha_usuario"
                name="senha_usuario"
                value={formData.senha_usuario}
                onChange={handleChange}
                disabled={loading}
              />
            </div>
            
            <button type="submit" disabled={loading}>
              {loading ? 'Atualizando...' : 'Atualizar Dados'}
            </button>
          </form>

          {/* Dados específicos do tipo de usuário */}
          {user.tipo === 'paciente' && (
            <form onSubmit={handleProfileUpdate} className="conta-form profile-form">
              <h2>Dados do Paciente</h2>
              
              <div className="form-group">
                <label htmlFor="cpf">CPF:</label>
                <input
                  type="text"
                  id="cpf"
                  name="cpf"
                  value={profileData.cpf || ''}
                  onChange={handleProfileChange}
                  required
                  disabled={loading}
                />
              </div>
              
              <div className="form-group">
                <label htmlFor="nis">NIS:</label>
                <input
                  type="text"
                  id="nis"
                  name="nis"
                  value={profileData.nis || ''}
                  onChange={handleProfileChange}
                  disabled={loading}
                />
              </div>
              
              <button type="submit" disabled={loading}>
                {loading ? 'Atualizando...' : 'Atualizar Dados do Paciente'}
              </button>
            </form>
          )}

          {user.tipo === 'nutricionista' && (
            <form onSubmit={handleProfileUpdate} className="conta-form profile-form">
              <h2>Dados do Nutricionista</h2>
              
              <div className="form-group">
                <label htmlFor="crm_nutricionista">CRN:</label>
                <input
                  type="text"
                  id="crm_nutricionista"
                  name="crm_nutricionista"
                  value={profileData.crm_nutricionista || ''}
                  onChange={handleProfileChange}
                  required
                  disabled={loading}
                />
              </div>
              
              <div className="form-group">
                <label htmlFor="cpf">CPF:</label>
                <input
                  type="text"
                  id="cpf"
                  name="cpf"
                  value={profileData.cpf || ''}
                  onChange={handleProfileChange}
                  required
                  disabled={loading}
                />
              </div>
              
              <button type="submit" disabled={loading}>
                {loading ? 'Atualizando...' : 'Atualizar Dados do Nutricionista'}
              </button>
            </form>
          )}

          {user.tipo === 'medico' && (
            <form onSubmit={handleProfileUpdate} className="conta-form profile-form">
              <h2>Dados do Médico</h2>
              
              <div className="form-group">
                <label htmlFor="crm_medico">CRM:</label>
                <input
                  type="text"
                  id="crm_medico"
                  name="crm_medico"
                  value={profileData.crm_medico || ''}
                  onChange={handleProfileChange}
                  required
                  disabled={loading}
                />
              </div>
              
              <div className="form-group">
                <label htmlFor="cpf">CPF:</label>
                <input
                  type="text"
                  id="cpf"
                  name="cpf"
                  value={profileData.cpf || ''}
                  onChange={handleProfileChange}
                  required
                  disabled={loading}
                />
              </div>
              
              <button type="submit" disabled={loading}>
                {loading ? 'Atualizando...' : 'Atualizar Dados do Médico'}
              </button>
            </form>
          )}

          {/* Ações da conta */}
          <div className="conta-actions">
            <button onClick={handleDeleteAccount} className="delete-btn">
              Deletar Conta
            </button>
            
            <button onClick={handleLogout} className="logout-btn">
              Sair
            </button>
          </div>
        </div>
      </div>
    </main>
  );
};

export default Conta;