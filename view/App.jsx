import React from "react";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import IndexDelimeter from "../src/delimeter/IndexDelimeter";
import SobreDelimeter from "../src/delimeter/SobreDelimeter";
import CalculoForm from "./delimeter/CalculoForm";
import LoginDelimeter from "./delimeter/LoginDelimeter";
import IndexUsuario from "../src/usuario/IndexUsuario";
import FormUsuario from "../src/usuario/FormUsuario";
import LoginUsuario from "../src/usuario/LoginUsuario";
import ContaUsuario from "../src/usuario/ContaUsuario";
import IndexPaciente from "./paciente/IndexPaciente";
import FormPaciente from "./paciente/FormPaciente";
import IndexNutricionista from "./nutricionista/IndexNutricionista";
import FormNutricionista from "./nutricionista/FormNutricionista";
import IndexMedico from "./medico/IndexMedico";
import FormMedico from "./medico/FormMedico";
import Header from "./includes/Header";
import Footer from "./includes/Footer";

function App() {
  // O usuário pode ser obtido via contexto/autenticação
  const usuario = null; // Troque por lógica real

  return (
    <Router>
      <Header usuario={usuario} />
      <Routes>
        <Route path="/" element={<IndexDelimeter />} />
        <Route path="/delimeter" element={<IndexDelimeter />} />
        <Route path="/delimeter/sobre" element={<SobreDelimeter />} />
        <Route path="/delimeter/calculo" element={<CalculoForm />} />
        <Route path="/usuario" element={<IndexUsuario />} />
        <Route path="/usuario/cadastro" element={<FormUsuario />} />
        <Route path="/usuario/login" element={<LoginUsuario />} />
        <Route path="/conta" element={<ContaUsuario usuario={usuario} />} />
        <Route path="/paciente" element={<IndexPaciente />} />
        <Route path="/paciente/cadastro" element={<FormPaciente />} />
        <Route path="/nutricionista" element={<IndexNutricionista />} />
        <Route path="/nutricionista/cadastro" element={<FormNutricionista />} />
        <Route path="/medico" element={<IndexMedico />} />
        <Route path="/medico/cadastro" element={<FormMedico />} />
        {/* Adicione outras rotas conforme necessário */}
      </Routes>
      <Footer />
    </Router>
  );
}

export default App;
