import axios from 'axios';

const API_BASE_URL = 'http://localhost:8000/api';

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

export const delimeterService = {
  getHome: async () => {
    const response = await api.get('/home');
    return response.data;
  },

  getSobre: async () => {
    const response = await api.get('/sobre');
    return response.data;
  },

  calcularNutricional: async (dados: {
    peso: number;
    altura: number;
    idade: number;
    sexo: string;
    atividade: number;
  }) => {
    const response = await api.post('/calculo', dados);
    return response.data;
  },
};

export default api;
