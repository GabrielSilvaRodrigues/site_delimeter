import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import './App.css';
import { AuthProvider } from './contexts/AuthContext';
import Header from './components/Header/Header';
import Footer from './components/Footer/Footer';
import Home from './pages/Home/Home';
import Login from './pages/Login/Login';
import Register from './pages/Register/Register';
import UserPanel from './pages/UserPanel';
import PacientePanel from './pages/PacientePanel';
import NutricionistaPanel from './pages/NutricionistaPanel';
import MedicoPanel from './pages/MedicoPanel';
import DelimiterTool from './pages/DelimiterTool';
import About from './pages/Sobre/Sobre';
import Calculo from './pages/Calculo/Calculo';
import ErrorBoundary from './components/ErrorBoundary';

function App() {
  return (
    <AuthProvider>
      <Router>
        <div className="App">
          <ErrorBoundary>
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
          </ErrorBoundary>
        </div>
      </Router>
    </AuthProvider>
  );
}

export default App;