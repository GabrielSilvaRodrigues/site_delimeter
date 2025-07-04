import React from "react";

function IndexDelimeter() {
  return (
    <main>
      <section className="container-main">
        <div className="container-main-image">
          <img
            src="/public/assets/images/pexels-fauxels-3184195.jpg"
            alt="Alimentação saudável"
          />
          <h1>PRIORIZAMOS A SUA ALIMENTAÇÃO</h1>
        </div>
        <div className="caixas">
          <div className="caixaAlfa caixaRelativa">
            <h2>Sobre o Delímiter</h2>
            <p>Uma plataforma nova voltada à alimentação</p>
            <a href="about.php" className="link">
              Saiba mais
            </a>
          </div>
          <div className="caixaAlfa caixaRelativa">
            <h2>Dados métricos</h2>
            <p>Calcule o seu gasto energético basal</p>
            <a href="calculo_landpage.php" className="link">
              Saiba mais
            </a>
          </div>
        </div>
        <div className="parceiros">
          <h2>PARCERIAS</h2>
          <p>Conheça nossos parceiros</p>
          <div className="logos">
            <a href="#">
              <img
                src="/public/assets/images/sus.jpeg"
                alt="SUS"
                className="caixaRelativa"
              />
            </a>
            <a href="#">
              <img
                src="/public/assets/images/crn3.jpeg"
                alt="CRN3"
                className="caixaRelativa"
              />
            </a>
            <a href="#">
              <img
                src="/public/assets/images/cremesp.jpeg"
                alt="CREMESP"
                className="caixaRelativa"
              />
            </a>
          </div>
        </div>
      </section>
      <section className="funcionalidades">
        <h2>FUNCIONALIDADES</h2>
        <div className="caixas">
          <div className="caixa">
            <a href="#">
              <img
                src="/public/assets/images/nutricionista.jpg"
                alt="Nutricionista"
                className="caixaRelativa"
              />
            </a>
            <h3>Marque uma consulta com uma nutricionista</h3>
            <p>Para acompanhar sua alimentação.</p>
          </div>
          <div className="caixa">
            <a href="#">
              <img
                src="/public/assets/images/dieta.jpg"
                alt="Dieta"
                className="caixaRelativa"
              />
            </a>
            <h3>Dietas focadas no seu perfil</h3>
            <p>Através do mapeamento de dados.</p>
          </div>
          <div className="caixa">
            <a href="#">
              <img
                src="/public/assets/images/crianca.jpg"
                alt="Criança"
                className="caixaRelativa"
              />
            </a>
            <h3>Plano alimentar junto ao cadúnico</h3>
            <p>Associar famílias de baixa renda a fornecedores de alimentos.</p>
          </div>
        </div>
      </section>
    </main>
  );
}

export default IndexDelimeter;