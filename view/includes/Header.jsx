import React from "react";

function Header({ usuario }) {
  // O usuário pode ser passado via props/context
  let tipo = usuario?.tipo || null;
  let css = tipo ? `/public/assets/styles/${tipo}.css` : "/public/assets/styles/delimeter.css";
  return (
    <header>
      <link rel="stylesheet" href={css} />
      <link rel="stylesheet" href="/public/assets/styles/acessibilidade.css" />
      <div className="logo">
        <a href="/delimeter">
          <img src="/public/assets/images/logo.png" alt="Logo Delímiter" />
        </a>
      </div>
      <div className="menu-hamburguer">
        <input type="checkbox" id="menu-toggle" />
        <label htmlFor="menu-toggle" className="menu-icon">
          <div className="linha"></div>
          <div className="linha"></div>
          <div className="linha"></div>
        </label>
        <div className="overlay">
          <nav>
            <ul aria-label="Acessibilidade" className="acessibilidade">
              <li><a href="/delimeter/sobre" className="link">Sobre Nós</a></li>
              <li><a href="/delimeter/calculo" className="link">Cálculo nutricional</a></li>
              {usuario ? (
                <>
                  {usuario.tipo === "paciente" && <li><a href="/paciente" className="link">Painel</a></li>}
                  {usuario.tipo === "nutricionista" && <li><a href="/nutricionista" className="link">Painel</a></li>}
                  {usuario.tipo === "medico" && <li><a href="/medico" className="link">Painel</a></li>}
                  <li><a href="/conta" className="link">Conta</a></li>
                  <li><a href="/usuario" className="link">Home</a></li>
                </>
              ) : (
                <>
                  <li><a href="/usuario/cadastro" className="link">Cadastrar-se</a></li>
                  <li><a href="/usuario/login" className="link">Login</a></li>
                </>
              )}
              <li><p>Modificar tamanho da fonte</p></li>
              <li><button onClick={() => window.aumentarFonte()} id="aumentar-fonte-btn" aria-label="Aumentar tamanho da fonte" accessKey="2" tabIndex={2}>A+</button></li>
              <li><button onClick={() => window.diminuirFonte()} id="diminuir-fonte-btn" aria-label="Diminuir tamanho da fonte" accessKey="3" tabIndex={3}>A-</button></li>
              <li><p>Modificar estilo da exibição</p></li>
              <li><button onClick={() => window.toggleContraste()} id="contraste-btn" aria-pressed="false" aria-label="Ativar ou desativar alto contraste">Alto Contraste</button></li>
              <li><button onClick={() => window.toggleDaltonismo('protanopia')} aria-label="Simular protanopia">Protanopia</button></li>
              <li><button onClick={() => window.toggleDaltonismo('deuteranopia')} aria-label="Simular deuteranopia">Deuteranopia</button></li>
              <li><button onClick={() => window.toggleDaltonismo('tritanopia')} aria-label="Simular tritanopia">Tritanopia</button></li>
              <button onClick={() => window.resetarAcessibilidade()} aria-label="Restaurar configurações de acessibilidade">Voltar ao normal</button>
            </ul>
          </nav>
        </div>
      </div>
    </header>
  );
}

export default Header;
