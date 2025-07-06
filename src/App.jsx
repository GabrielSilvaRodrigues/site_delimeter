import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import './App.css';
import { AuthProvider } from './contexts/AuthContext';
import Header from './components/Header';
import Footer from './components/Footer';
import Home from './pages/Home';
import Login from './pages/Login';
import Register from './pages/Register';
import UserPanel from './pages/UserPanel';
import PacientePanel from './pages/PacientePanel';
import NutricionistaPanel from './pages/NutricionistaPanel';
import MedicoPanel from './pages/MedicoPanel';
import DelimiterTool from './pages/DelimiterTool';
import About from './pages/About';
import Calculo from './pages/Calculo';

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
              <Route path="/login" element={<Login />} />
              <Route path="/register" element={<Register />} />
              <Route path="/about" element={<About />} />
              <Route path="/calculo" element={<Calculo />} />
              <Route path="/delimiter-tool" element={<DelimiterTool />} />
              
              {/* Rotas protegidas */}
              <Route path="/usuario" element={<UserPanel />} />
              <Route path="/paciente" element={<PacientePanel />} />
              <Route path="/nutricionista" element={<NutricionistaPanel />} />
              <Route path="/medico" element={<MedicoPanel />} />
            </Routes>
          </main>
          <Footer />
        </div>
      </Router>
    </AuthProvider>
  );
}

export default App;