import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import './PacienteForm.css';

const PacienteForm = () => {
  const [formData, setFormData] = useState({
    cpf: '',
    nis: ''
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  
  const navigate = useNavigate();
  const { user } = useAuth();

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
      const response = await fetch('/api/paciente', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData),
        credentials: 'include'
      });

      if (response.ok) {
        navigate('/paciente');
      } else {
        const errorData = await response.json();
        setError(errorData.error || 'Erro ao cadastrar paciente');
      }
    } catch (error) {
      setError('Erro de conexão');
    }
    
    setLoading(false);
  };

  if (!user) {
    return (
      <main className="paciente-form-main">
        <div className="container-calc">
          <div className="container">
            <h2>Acesso Negado</h2>
            <p>Você precisa estar logado para acessar esta página.</p>
          </div>
        </div>
      </main>
    );
  }

  return (
    <main className="paciente-form-main">
      <div className="container-calc">
        <form onSubmit={handleSubmit} className="paciente-form">
          <div className="container">
            <h2>Cadastro de Paciente</h2>
            
            {error && (
              <div className="error-message">
                {error}
              </div>
            )}
            
            <div className="form-group">
              <label htmlFor="cpf">CPF:</label>
              <input
                type="text"
                id="cpf"
                name="cpf"
                value={formData.cpf}
                onChange={handleChange}
                required
                disabled={loading}
                placeholder="000.000.000-00"
              />
            </div>
            
            <div className="form-group">
              <label htmlFor="nis">NIS (opcional):</label>
              <input
                type="text"
                id="nis"
                name="nis"
                value={formData.nis}
                onChange={handleChange}
                disabled={loading}
                placeholder="Número de Identificação Social"
              />
            </div>
            
            <button type="submit" disabled={loading}>
              {loading ? 'Cadastrando...' : 'Cadastrar'}
            </button>
          </div>
        </form>
      </div>
    </main>
  );
};

export default PacienteForm;