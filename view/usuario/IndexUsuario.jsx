import React from "react";

function IndexUsuario() {
  return (
    <div className="usuario-container" style={{background: "linear-gradient(120deg, #f4f4f4 60%, #e0f7fa 100%)", minHeight: "100vh"}}>
      <main className="usuario-main-content" style={{maxWidth: "900px", margin: "0 auto", paddingBottom: "40px"}}>
        <div className="usuario-header" style={{marginBottom: "40px", background: "linear-gradient(90deg, #4CAF50 70%, #388e3c 100%)", boxShadow: "0 4px 16px rgba(76,175,80,0.13)", borderRadius: "14px"}}>
            <h1 style={{fontSize:"2.5rem", marginBottom: "10px", letterSpacing: "1px", color: "#fff", textShadow: "1px 2px 8px #388e3c33"}}>
                👤 Bem-vindo ao Sistema de Gerenciamento de Usuários
            </h1>
            <p style={{fontSize:"1.18rem", color:"#e0ffe0", marginBottom: "0"}}>
                Gerencie seus dados, visualize informações e aproveite nossos serviços exclusivos.
            </p>
        </div>
        {/* Seção de cadastro para diferentes perfis */}
        <section className="usuario-section" id="cadastro-tipos" style={{marginBottom: "32px", background: "#fff", borderRadius: "10px", boxShadow: "0 2px 8px #4caf5022", textAlign:"center"}}>
            <h2 style={{fontSize:"1.4rem", color:"#388e3c", marginBottom: "18px"}}>
                <span style={{fontSize:"1.2em"}}>📝</span> Entrar como:
            </h2>
            <div style={{display: "flex", flexWrap: "wrap", gap: "24px", justifyContent: "center"}}>
                <a href="/paciente" style={{flex:"1 1 180px", minWidth:"180px", maxWidth:"220px", background:"#e0f7fa", borderRadius:"8px", boxShadow:"0 1px 6px #4caf5011", padding:"18px 10px", marginBottom:"10px", display:"flex", flexDirection:"column", alignItems:"center", textDecoration:"none", color:"#388e3c", fontWeight:"bold", transition:"box-shadow 0.2s"}}>
                    <span style={{fontSize:"2.2em", marginBottom:"8px"}}>🧑‍🦱</span>
                    Paciente
                </a>
                <a href="/nutricionista" style={{flex:"1 1 180px", minWidth:"180px", maxWidth:"220px", background:"#e8f5e9", borderRadius:"8px", boxShadow:"0 1px 6px #43a04711", padding:"18px 10px", marginBottom:"10px", display:"flex", flexDirection:"column", alignItems:"center", textDecoration:"none", color:"#43a047", fontWeight:"bold", transition:"box-shadow 0.2s"}}>
                    <span style={{fontSize:"2.2em", marginBottom:"8px"}}>🥗</span>
                    Nutricionista
                </a>
                <a href="/medico" style={{flex:"1 1 180px", minWidth:"180px", maxWidth:"220px", background:"#e3f2fd", borderRadius:"8px", boxShadow:"0 1px 6px #1976d211", padding:"18px 10px", marginBottom:"10px", display:"flex", flexDirection:"column", alignItems:"center", textDecoration:"none", color:"#1976d2", fontWeight:"bold", transition:"box-shadow 0.2s"}}>
                    <span style={{fontSize:"2.2em", marginBottom:"8px"}}>🩺</span>
                    Médico
                </a>
            </div>
        </section>
        <section className="usuario-section" id="home" style={{marginBottom: "32px", background: "#fff", borderRadius: "10px", boxShadow: "0 2px 8px #4caf5022"}}>
            <h2 style={{fontSize:"1.6rem", color:"#388e3c", marginBottom: "10px", display: "flex", alignItems: "center", gap: "8px"}}>
                <span style={{fontSize:"1.3em"}}>🏠</span> Início
            </h2>
            <p style={{fontSize:"1.08rem", color:"#444"}}>
                Bem-vindo ao seu <strong>painel de usuário</strong>! Aqui você pode acessar e gerenciar suas informações de forma simples, segura e prática.<br />
                <span style={{color:"#4CAF50"}}>Utilize o menu lateral para navegar entre as funcionalidades.</span>
            </p>
        </section>
        <section className="usuario-section" id="about" style={{marginBottom: "32px", background: "#fff", borderRadius: "10px", boxShadow: "0 2px 8px #4caf5022"}}>
            <h2 style={{fontSize:"1.6rem", color:"#388e3c", marginBottom: "10px", display: "flex", alignItems: "center", gap: "8px"}}>
                <span style={{fontSize:"1.3em"}}>ℹ️</span> Sobre
            </h2>
            <p style={{fontSize:"1.08rem", color:"#444"}}>
                Nosso sistema foi desenvolvido para facilitar o <strong>gerenciamento de usuários</strong>, proporcionando praticidade, segurança e autonomia.
            </p>
            <ul style={{margin: "15px 0 0 20px", color:"#388e3c", fontSize:"1.05rem", lineHeight:"1.7"}}>
                <li>Atualize seus dados pessoais facilmente</li>
                <li>Visualize seu histórico de atividades</li>
                <li>Receba suporte personalizado</li>
            </ul>
        </section>
        <section className="usuario-section" id="services" style={{marginBottom: "32px", background: "#fff", borderRadius: "10px", boxShadow: "0 2px 8px #4caf5022"}}>
            <h2 style={{fontSize:"1.6rem", color:"#388e3c", marginBottom: "18px", display: "flex", alignItems: "center", gap: "8px"}}>
                <span style={{fontSize:"1.3em"}}>🛠️</span> Serviços
            </h2>
            <div style={{display: "flex", flexWrap: "wrap", gap: "24px", justifyContent: "space-between"}}>
                <div style={{flex:"1 1 220px", minWidth:"220px", background:"#f8fff8", borderRadius:"8px", boxShadow:"0 1px 6px #4caf5011", padding:"18px 14px", marginBottom:"10px", display:"flex", flexDirection:"column", alignItems:"center"}}>
                    <div style={{fontSize:"2.1em", marginBottom:"8px"}}>👤</div>
                    <h3 style={{marginBottom: "6px", color:"#4CAF50", fontSize:"1.18rem"}}>Perfil</h3>
                    <p style={{margin:"0", color:"#555", textAlign:"center"}}>Atualize suas informações pessoais e preferências.</p>
                </div>
                <div style={{flex:"1 1 220px", minWidth:"220px", background:"#f8fff8", borderRadius:"8px", boxShadow:"0 1px 6px #4caf5011", padding:"18px 14px", marginBottom:"10px", display:"flex", flexDirection:"column", alignItems:"center"}}>
                    <div style={{fontSize:"2.1em", marginBottom:"8px"}}>📜</div>
                    <h3 style={{marginBottom: "6px", color:"#4CAF50", fontSize:"1.18rem"}}>Histórico</h3>
                    <p style={{margin:"0", color:"#555", textAlign:"center"}}>Consulte seu histórico de acessos e atividades.</p>
                </div>
                <div style={{flex:"1 1 220px", minWidth:"220px", background:"#f8fff8", borderRadius:"8px", boxShadow:"0 1px 6px #4caf5011", padding:"18px 14px", marginBottom:"10px", display:"flex", flexDirection:"column", alignItems:"center"}}>
                    <div style={{fontSize:"2.1em", marginBottom:"8px"}}>💬</div>
                    <h3 style={{marginBottom: "6px", color:"#4CAF50", fontSize:"1.18rem"}}>Suporte</h3>
                    <p style={{margin:"0", color:"#555", textAlign:"center"}}>Fale com nossa equipe para tirar dúvidas ou resolver problemas.</p>
                </div>
            </div>
        </section>
        <section className="usuario-section" id="contact" style={{background: "#fff", borderRadius: "10px", boxShadow: "0 2px 8px #4caf5022"}}>
            <h2 style={{fontSize:"1.6rem", color:"#388e3c", marginBottom: "10px", display: "flex", alignItems: "center", gap: "8px"}}>
                <span style={{fontSize:"1.3em"}}>📞</span> Contato
            </h2>
            <p style={{fontSize:"1.08rem", color:"#444"}}>
                Precisa de ajuda? Entre em contato com nossa equipe de suporte:<br />
                <strong>Email:</strong> <a href="mailto:suporte@delimeter.com" style={{color:"#4CAF50", textDecoration:"underline"}}>suporte@delimeter.com</a><br />
                Ou utilize o formulário disponível no site.
            </p>
        </section>
      </main>
    </div>
  );
}

export default IndexUsuario;