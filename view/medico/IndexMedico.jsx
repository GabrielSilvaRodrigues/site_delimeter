import React from "react";

function IndexMedico() {
  return (
    <div className="usuario-container" style={{ background: 'linear-gradient(120deg, #f4f4f4 60%, #e0f7fa 100%)', minHeight: '100vh' }}>
      <main className="usuario-main-content" style={{ maxWidth: '900px', margin: '0 auto', paddingBottom: '40px' }}>
        <div className="usuario-header" style={{ marginBottom: '40px', background: 'linear-gradient(90deg, #1976d2 70%, #1565c0 100%)', boxShadow: '0 4px 16px rgba(25,118,210,0.13)', borderRadius: '14px' }}>
          <h1 style={{ fontSize: '2.5rem', marginBottom: '10px', letterSpacing: '1px', color: '#fff', textShadow: '1px 2px 8px #1565c033' }}>
            🩺 Bem-vindo ao Painel do Médico
          </h1>
          <p style={{ fontSize: '1.18rem', color: '#e0f7fa', marginBottom: '0' }}>
            Gerencie pacientes, visualize históricos e acesse ferramentas clínicas.
          </p>
        </div>
        {/* ...demais seções... */}
        {/* Copie o conteúdo JSX das seções do arquivo original */}
      </main>
    </div>
  );
}

export default IndexMedico;