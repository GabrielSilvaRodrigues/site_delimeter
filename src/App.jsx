import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Header from './components/Header/Header';
import Footer from './components/Footer/Footer';
import Home from './pages/Home/Home';
import Sobre from './pages/Sobre/Sobre';
import Calculo from './pages/Calculo/Calculo';
import Login from './pages/Login/Login';
import Cadastro from './pages/Cadastro/Cadastro';
import Usuario from './pages/Usuario/Usuario';
import Paciente from './pages/Paciente/Paciente';
import Nutricionista from './pages/Nutricionista/Nutricionista';
import Medico from './pages/Medico/Medico';
import Conta from './pages/Conta/Conta';
import PacienteForm from './pages/Paciente/PacienteForm';
import NutricionistaForm from './pages/Nutricionista/NutricionistaForm';
import MedicoForm from './pages/Medico/MedicoForm';
import { AuthProvider } from './contexts/AuthContext';
import './App.css';

function App() {
  return (
    <AuthProvider>
      <Router>
        <div className="App">
          <Header />
          <main className="main-content">
            <Routes>
              <Route path="/" element={<Home />} />
              <Route path="/delimeter" element={<Home />} />
              <Route path="/delimeter/sobre" element={<Sobre />} />
              <Route path="/delimeter/calculo" element={<Calculo />} />
              <Route path="/usuario/login" element={<Login />} />
              <Route path="/usuario/cadastro" element={<Cadastro />} />
              <Route path="/usuario" element={<Usuario />} />
              <Route path="/paciente" element={<Paciente />} />
              <Route path="/paciente/cadastro" element={<PacienteForm />} />
              <Route path="/nutricionista" element={<Nutricionista />} />
              <Route path="/nutricionista/cadastro" element={<NutricionistaForm />} />
              <Route path="/medico" element={<Medico />} />
              <Route path="/medico/cadastro" element={<MedicoForm />} />
              <Route path="/conta" element={<Conta />} />
            </Routes>
          </main>
          <Footer />
        </div>
      </Router>
    </AuthProvider>
  );
}

export default App;