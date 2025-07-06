import axios from 'axios';

// Configure axios defaults
axios.defaults.withCredentials = true;
axios.defaults.headers.common['Content-Type'] = 'application/json';

const API_BASE_URL = process.env.NODE_ENV === 'production' ? '' : 'http://localhost:8080';

// API endpoints
export const api = {
  // Authentication
  login: (credentials) => axios.post(`${API_BASE_URL}/login/usuario`, credentials),
  logout: () => axios.get(`${API_BASE_URL}/conta/sair`),
  checkAuth: () => axios.get(`${API_BASE_URL}/api/auth/check`),
  
  // User management
  createUser: (userData) => axios.post(`${API_BASE_URL}/api/usuario`, userData),
  updateUser: (userData) => axios.post(`${API_BASE_URL}/conta/atualizar`, userData),
  deleteUser: () => axios.post(`${API_BASE_URL}/conta/deletar`),
  getUserData: () => axios.get(`${API_BASE_URL}/conta`),
  
  // Patient management
  createPatient: (patientData) => axios.post(`${API_BASE_URL}/api/paciente`, patientData),
  updatePatient: (patientData) => axios.post(`${API_BASE_URL}/paciente/conta/atualizar`, patientData),
  deletePatient: () => axios.post(`${API_BASE_URL}/paciente/conta/deletar`),
  getPatientData: () => axios.get(`${API_BASE_URL}/paciente`),
  
  // Nutritionist management
  createNutritionist: (nutritionistData) => axios.post(`${API_BASE_URL}/api/nutricionista`, nutritionistData),
  updateNutritionist: (nutritionistData) => axios.post(`${API_BASE_URL}/nutricionista/conta/atualizar`, nutritionistData),
  deleteNutritionist: () => axios.post(`${API_BASE_URL}/nutricionista/conta/deletar`),
  getNutritionistData: () => axios.get(`${API_BASE_URL}/nutricionista`),
  
  // Doctor management
  createDoctor: (doctorData) => axios.post(`${API_BASE_URL}/api/medico`, doctorData),
  updateDoctor: (doctorData) => axios.post(`${API_BASE_URL}/medico/conta/atualizar`, doctorData),
  deleteDoctor: () => axios.post(`${API_BASE_URL}/medico/conta/deletar`),
  getDoctorData: () => axios.get(`${API_BASE_URL}/medico`),
  
  // Delimiter API (if needed)
  processDelimiter: (data) => axios.post(`${API_BASE_URL}/api/delimiter`, data)
};

// Error interceptor
axios.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Unauthorized - redirect to login
      window.location.href = '/usuario/login';
    }
    return Promise.reject(error);
  }
);

export default api;