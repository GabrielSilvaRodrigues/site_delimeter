import React from "react";

function IndexPaciente() {
  return (
    <div className="usuario-container" style={{ background: 'linear-gradient(120deg, #f4f4f4 60%, #e0f7fa 100%)', minHeight: '100vh' }}>
      <main className="usuario-main-content" style={{ maxWidth: '900px', margin: '0 auto', paddingBottom: '40px' }}>
        <div className="usuario-header" style={{ marginBottom: '40px', background: 'linear-gradient(90deg, #ff9800 70%, #f57c00 100%)', boxShadow: '0 4px 16px rgba(255,152,0,0.13)', borderRadius: '14px' }}>
          <h1 style={{ fontSize: '2.5rem', marginBottom: '10px', letterSpacing: '1px', color: '#fff', textShadow: '1px 2px 8px #f57c0033' }}>
            🧑‍🦱 Bem-vindo ao Painel do Paciente
          </h1>
          <p style={{ fontSize: '1.18rem', color: '#fff3e0', marginBottom: '0' }}>
            Acompanhe sua saúde, visualize orientações e mantenha seus dados atualizados.
          </p>
        </div>
        {/* ...demais seções... */}
        {/* Copie o conteúdo JSX das seções do arquivo original */}
      </main>
    </div>
  );
}

export default IndexPaciente;
