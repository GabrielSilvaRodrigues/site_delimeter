import React, { createContext, useContext, useState, useEffect } from 'react';

const AuthContext = createContext();

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth deve ser usado dentro de um AuthProvider');
  }
  return context;
};

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Verifica se há usuário logado
    checkAuthStatus();
  }, []);

  const checkAuthStatus = async () => {
    try {
      const response = await fetch('/api/auth/status', {
        credentials: 'include'
      });
      if (response.ok) {
        const userData = await response.json();
        setUser(userData);
      }
    } catch (error) {
      console.error('Erro ao verificar status de autenticação:', error);
    }
    setLoading(false);
  };

  const login = async (email, senha) => {
    try {
      const response = await fetch('/login/usuario', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          email_usuario: email,
          senha_usuario: senha
        }),
        credentials: 'include'
      });

      if (response.ok) {
        const userData = await response.json();
        setUser(userData.usuario);
        return { success: true, user: userData.usuario };
      } else {
        const error = await response.json();
        return { success: false, error: error.error || 'Erro no login' };
      }
    } catch (error) {
      return { success: false, error: 'Erro de conexão' };
    }
  };

  const logout = async () => {
    try {
      await fetch('/conta/sair', {
        credentials: 'include'
      });
    } catch (error) {
      console.error('Erro ao fazer logout:', error);
    }
    setUser(null);
  };

  const register = async (nome, email, senha) => {
    try {
      const response = await fetch('/api/usuario', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          nome_usuario: nome,
          email_usuario: email,
          senha_usuario: senha
        })
      });

      if (response.ok) {
        return { success: true };
      } else {
        const error = await response.json();
        return { success: false, error: error.error || 'Erro no cadastro' };
      }
    } catch (error) {
      return { success: false, error: 'Erro de conexão' };
    }
  };

  const value = {
    user,
    login,
    logout,
    register,
    loading
  };

  return (
    <AuthContext.Provider value={value}>
      {children}
    </AuthContext.Provider>
  );
};