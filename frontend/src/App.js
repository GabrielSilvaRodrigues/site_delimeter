import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import './App.css';

// Componentes de Layout
import Header from './components/layout/Header';
import Footer from './components/layout/Footer';

// Páginas principais
import Home from './pages/Home';
import About from './pages/About';
import CalcPage from './pages/CalcPage';
import Login from './pages/Login';
import Register from './pages/Register';
import Account from './pages/Account';

// Páginas de usuários
import UserHome from './pages/user/UserHome';
import PatientHome from './pages/user/PatientHome';
import NutritionistHome from './pages/user/NutritionistHome';
import DoctorHome from './pages/user/DoctorHome';

// Formulários de usuários específicos
import PatientForm from './pages/user/PatientForm';
import NutritionistForm from './pages/user/NutritionistForm';
import DoctorForm from './pages/user/DoctorForm';

function App() {
  return (
    <Router>
      <div className="App">
        <Header />
        <Routes>
          {/* Rotas principais */}
          <Route path="/" element={<Home />} />
          <Route path="/delimeter" element={<Home />} />
          <Route path="/delimeter/sobre" element={<About />} />
          <Route path="/delimeter/calculo" element={<CalcPage />} />
          
          {/* Autenticação */}
          <Route path="/usuario/login" element={<Login />} />
          <Route path="/usuario/cadastro" element={<Register />} />
          <Route path="/conta" element={<Account />} />
          
          {/* Painéis de usuários */}
          <Route path="/usuario" element={<UserHome />} />
          <Route path="/paciente" element={<PatientHome />} />
          <Route path="/nutricionista" element={<NutritionistHome />} />
          <Route path="/medico" element={<DoctorHome />} />
          
          {/* Formulários específicos */}
          <Route path="/paciente/cadastro" element={<PatientForm />} />
          <Route path="/nutricionista/cadastro" element={<NutritionistForm />} />
          <Route path="/medico/cadastro" element={<DoctorForm />} />
        </Routes>
        <Footer />
      </div>
    </Router>
  );
}

export default App;