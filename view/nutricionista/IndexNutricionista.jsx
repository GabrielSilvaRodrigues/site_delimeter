import React from "react";

function IndexNutricionista() {
  return (
    <div className="usuario-container" style={{ background: 'linear-gradient(120deg, #f4f4f4 60%, #e0ffe0 100%)', minHeight: '100vh' }}>
      <main className="usuario-main-content" style={{ maxWidth: '900px', margin: '0 auto', paddingBottom: '40px' }}>
        <div className="usuario-header" style={{ marginBottom: '40px', background: 'linear-gradient(90deg, #43a047 70%, #388e3c 100%)', boxShadow: '0 4px 16px rgba(67,160,71,0.13)', borderRadius: '14px' }}>
          <h1 style={{ fontSize: '2.5rem', marginBottom: '10px', letterSpacing: '1px', color: '#fff', textShadow: '1px 2px 8px #388e3c33' }}>
            🥗 Bem-vindo ao Painel do Nutricionista
          </h1>
          <p style={{ fontSize: '1.18rem', color: '#e0ffe0', marginBottom: '0' }}>
            Gerencie pacientes, planos alimentares e acompanhe resultados nutricionais.
          </p>
        </div>
        {/* ...demais seções... */}
        {/* Copie o conteúdo JSX das seções do arquivo original */}
      </main>
    </div>
  );
}

export default IndexNutricionista;
