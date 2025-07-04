import React, { useState } from "react";

function CalculoForm() {
  const [form, setForm] = useState({
    nome: "",
    idade: "",
    sexo: "",
    peso: "",
    altura: "",
    atividade: "",
  });
  const [resultado, setResultado] = useState(null);

  function validarFormulario() {
    for (const key in form) {
      if (!form[key]) return false;
    }
    return true;
  }

  function enviarDados() {
    if (!validarFormulario()) {
      alert("Preencha todos os campos obrigatórios.");
      return;
    }
    const nome = form.nome;
    const idade = parseInt(form.idade);
    const sexo = form.sexo;
    const peso = parseFloat(form.peso);
    const alturaN = parseFloat(form.altura);
    const altura = alturaN / 100;
    const atividade = form.atividade;

    const imc = peso / (altura * altura);
    const pesoIdeal = ((altura ** 2) * 21.7).toFixed(1);
    let classificado = "";
    let corIMC = "";
    let fatorAtividade = { "sedentário": 1.55, "moderadamente ativo": 1.85, "ativo": 2.20 }[atividade] || 1.55;

    if (imc < 18.5) { classificado = "Baixo peso"; corIMC = "blue"; }
    else if (imc <= 24.99) { classificado = "Eutrofia"; corIMC = "green"; }
    else if (imc <= 29.99) { classificado = "Sobrepeso"; corIMC = "orange"; }
    else { classificado = "Obesidade"; corIMC = "red"; }

    let geb, gebIdeal;
    if (sexo === "masculino") {
      if (idade <= 3) { geb = (59.512 * peso) - 30.4; gebIdeal = (59.512 * pesoIdeal) - 30.4; }
      else if (idade <= 10) { geb = (22.706 * peso) + 504.3; gebIdeal = (22.706 * pesoIdeal) + 504.3; }
      else if (idade <= 18) { geb = (17.686 * peso) + 658.2; gebIdeal = (17.686 * pesoIdeal) + 658.2; }
      else if (idade <= 30) { geb = (15.057 * peso) + 692.2; gebIdeal = (15.057 * pesoIdeal) + 692.2; }
      else if (idade <= 60) { geb = (11.472 * peso) + 873.1; gebIdeal = (11.472 * pesoIdeal) + 873.1; }
      else { geb = (11.711 * peso) + 587.7; gebIdeal = (11.711 * pesoIdeal) + 587.7; }
    } else {
      if (idade <= 3) { geb = (58.31 * peso) - 31.1; gebIdeal = (58.31 * pesoIdeal) - 31.1; }
      else if (idade <= 10) { geb = (20.315 * peso) + 485.9; gebIdeal = (20.315 * pesoIdeal) + 485.9; }
      else if (idade <= 18) { geb = (13.384 * peso) + 692.6; gebIdeal = (13.384 * pesoIdeal) + 692.6; }
      else if (idade <= 30) { geb = (14.818 * peso) + 486.6; gebIdeal = (14.818 * pesoIdeal) + 486.6; }
      else if (idade <= 60) { geb = (8.126 * peso) + 845.6; gebIdeal = (8.126 * pesoIdeal) + 845.6; }
      else { geb = (9.082 * peso) + 658.5; gebIdeal = (9.082 * pesoIdeal) + 658.5; }
    }

    const get = geb * fatorAtividade;
    const getIdeal = gebIdeal * fatorAtividade;

    const proteinaMin = getIdeal * 0.10;
    const proteinaMax = getIdeal * 0.15;
    const gramagemProteinaMin = proteinaMin / 4;
    const gramagemProteinaMax = proteinaMax / 4;

    const carboidratosMin = getIdeal * 0.15;
    const carboidratosMax = getIdeal * 0.30;
    const gramagemCarboidratoMin = carboidratosMin / 4;
    const gramagemCarboidratoMax = carboidratosMax / 4;

    const lipidiosMin = getIdeal * 0.55;
    const lipidiosMax = getIdeal * 0.75;
    const gramagemLipidioMin = lipidiosMin / 9;
    const gramagemLipidioMax = lipidiosMax / 9;

    const refeicoes = {
      "🍞 Café da manhã": getIdeal * 0.25,
      "🍎 Lanche da manhã": getIdeal * 0.05,
      "🍛 Almoço": getIdeal * 0.35,
      "☕ Lanche da tarde": getIdeal * 0.10,
      "🍽️ Jantar": getIdeal * 0.15,
      "🥛 Lanche da noite": getIdeal * 0.05,
    };

    const macroNutrientes = {
      "🍚 Carboidratos": {
        min: carboidratosMin.toFixed(1),
        max: carboidratosMax.toFixed(1),
        gramasMin: gramagemCarboidratoMin.toFixed(1),
        gramasMax: gramagemCarboidratoMax.toFixed(1),
        cor: "green",
      },
      "🍗 Proteínas": {
        min: proteinaMin.toFixed(1),
        max: proteinaMax.toFixed(1),
        gramasMin: gramagemProteinaMin.toFixed(1),
        gramasMax: gramagemProteinaMax.toFixed(1),
        cor: "red",
      },
      "🥑 Lipídios": {
        min: lipidiosMin.toFixed(1),
        max: lipidiosMax.toFixed(1),
        gramasMin: gramagemLipidioMin.toFixed(1),
        gramasMax: gramagemLipidioMax.toFixed(1),
        cor: "orange",
      },
    };

    setResultado({
      nome, idade, sexo, peso, altura, atividade, imc, corIMC, classificado, geb, get, getIdeal, refeicoes, macroNutrientes
    });
  }

  return (
    <main>
      <section className="container-main">
        <div className="container-main-image">
          <img src="/public/assets/images/almoço.jpg" alt="Alimentação saudável" />
          <h1>PRIORIZAMOS A SUA ALIMENTAÇÃO</h1>
        </div>
      </section>
      <div className="container-calc" id="container-formulario-nutricional">
        <div className="container">
          <h1>Cálculo de Gasto Energético</h1>
          <form id="formulario" onSubmit={e => { e.preventDefault(); enviarDados(); }}>
            <div className="form-group">
              <label htmlFor="nome">Nome</label>
              <input type="text" name="nome" required id="nome"
                value={form.nome} onChange={e => setForm({ ...form, nome: e.target.value })} />
            </div>
            <div className="form-group">
              <label htmlFor="idade">Idade</label>
              <input type="number" name="idade" required id="idade"
                value={form.idade} onChange={e => setForm({ ...form, idade: e.target.value })} />
            </div>
            <div className="form-group">
              <label htmlFor="sexo">Sexo</label>
              <select name="sexo" required id="sexo"
                value={form.sexo} onChange={e => setForm({ ...form, sexo: e.target.value })}>
                <option value="">Selecione</option>
                <option value="masculino">MASCULINO</option>
                <option value="feminino">FEMININO</option>
              </select>
            </div>
            <div className="form-group">
              <label htmlFor="peso">Peso</label>
              <input type="number" name="peso" step="0.01" required id="peso"
                value={form.peso} onChange={e => setForm({ ...form, peso: e.target.value })} />
            </div>
            <div className="form-group">
              <label htmlFor="altura">Altura (em centímetros)</label>
              <input type="number" name="altura" step="0.01" required id="altura"
                value={form.altura} onChange={e => setForm({ ...form, altura: e.target.value })} />
            </div>
            <div className="form-group">
              <label htmlFor="atividade">Nível de atividade física</label>
              <select name="atividade" required id="atividade"
                value={form.atividade} onChange={e => setForm({ ...form, atividade: e.target.value })}>
                <option value="">Selecione</option>
                <option value="sedentário">Sedentário</option>
                <option value="moderadamente ativo">Moderadamente ativo</option>
                <option value="ativo">Ativo</option>
              </select>
            </div>
            <button type="submit">Enviar</button>
          </form>
        </div>
        <div className="container" id="resultado" style={{ display: resultado ? "block" : "none" }}>
          {resultado && (
            <>
              <p><strong>Nome:</strong> {resultado.nome}</p>
              <p><strong>Idade:</strong> {resultado.idade}</p>
              <p><strong>Sexo:</strong> {resultado.sexo}</p>
              <p><strong>Peso:</strong> {resultado.peso} kg</p>
              <p><strong>Altura:</strong> {resultado.altura / 100} m</p>
              <p><strong>Atividade Física:</strong> {resultado.atividade}</p>
              <p><strong>IMC:</strong> <span style={{ backgroundColor: resultado.corIMC, color: "#fff", padding: "2px 8px", borderRadius: 4 }}>{resultado.imc.toFixed(1)}</span></p>
              <p><strong>Classificação corporal:</strong> {resultado.classificado}</p>
              <p><strong>Gasto Energético Basal:</strong> {resultado.geb.toFixed(1)} Kcal</p>
              <p><strong>Gasto Energético Total:</strong> {resultado.get.toFixed(1)} Kcal</p>
              <p><strong>Gasto Energético Total Ideal:</strong> {resultado.getIdeal.toFixed(1)} Kcal</p>
              <hr />
              <h3>Distribuição energética</h3>
              {Object.entries(resultado.refeicoes).map(([refeicao, valor]) => (
                <p key={refeicao}><strong>{refeicao}:</strong> {valor.toFixed(1)} Kcal</p>
              ))}
              <hr />
              <h3>Macro nutrientes</h3>
              {Object.entries(resultado.macroNutrientes).map(([nutriente, dados]) => (
                <p key={nutriente}>
                  <strong>{nutriente}:</strong> Mínimo <span style={{ backgroundColor: dados.cor, color: "#fff", padding: "2px 8px", borderRadius: 4 }}>{dados.min}</span> Kcal ({dados.gramasMin} gramas) e máximo <span style={{ backgroundColor: dados.cor, color: "#fff", padding: "2px 8px", borderRadius: 4 }}>{dados.max}</span> Kcal ({dados.gramasMax} gramas)
                </p>
              ))}
            </>
          )}
        </div>
      </div>
    </main>
  );
}

export default CalculoForm;
