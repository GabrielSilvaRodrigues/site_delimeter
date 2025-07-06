import React, { useState } from 'react';
import './NutritionalCalculator.css';

const NutritionalCalculator = () => {
  const [formData, setFormData] = useState({
    nome: '',
    idade: '',
    sexo: '',
    peso: '',
    altura: '',
    atividade: ''
  });
  const [resultado, setResultado] = useState(null);
  const [loading, setLoading] = useState(false);

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
  };

  const validarFormulario = () => {
    const { nome, idade, sexo, peso, altura, atividade } = formData;
    
    if (!nome || !idade || !sexo || !peso || !altura || !atividade) {
      alert('Por favor, preencha todos os campos.');
      return false;
    }

    if (idade < 1 || idade > 120) {
      alert('Idade deve estar entre 1 e 120 anos.');
      return false;
    }

    if (peso < 1 || peso > 500) {
      alert('Peso deve estar entre 1 e 500 kg.');
      return false;
    }

    if (altura < 50 || altura > 250) {
      alert('Altura deve estar entre 50 e 250 cm.');
      return false;
    }

    return true;
  };

  const calcularDados = () => {
    if (!validarFormulario()) return;

    setLoading(true);
    
    const { nome, idade, sexo, peso, altura, atividade } = formData;
    const idadeNum = parseInt(idade);
    const pesoNum = parseFloat(peso);
    const alturaN = parseFloat(altura);
    const alturaM = alturaN / 100;

    // Cálculo do IMC
    const imc = pesoNum / (alturaM * alturaM);
    const pesoIdeal = ((alturaM ** 2) * 21.7).toFixed(1);
    
    let classificado = '';
    let corIMC = '';
    
    if (imc < 18.5) { 
      classificado = "Baixo peso"; 
      corIMC = "blue"; 
    } else if (imc <= 24.99) { 
      classificado = "Eutrofia"; 
      corIMC = "green"; 
    } else if (imc <= 29.99) { 
      classificado = "Sobrepeso"; 
      corIMC = "yellow"; 
    } else { 
      classificado = "Obesidade"; 
      corIMC = "red"; 
    }

    // Fator de atividade
    const fatoresAtividade = { 
      'sedentário': 1.55, 
      'moderadamente ativo': 1.85, 
      'ativo': 2.20 
    };
    const fatorAtividade = fatoresAtividade[atividade] || 1.55;

    // Cálculo do GEB (Gasto Energético Basal)
    let geb, gebIdeal;
    
    if (sexo === 'masculino') {
      if (idadeNum <= 3) { 
        geb = (59.512 * pesoNum) - 30.4; 
        gebIdeal = (59.512 * pesoIdeal) - 30.4; 
      } else if (idadeNum <= 10) { 
        geb = (22.706 * pesoNum) + 504.3; 
        gebIdeal = (22.706 * pesoIdeal) + 504.3; 
      } else if (idadeNum <= 18) { 
        geb = (17.686 * pesoNum) + 658.2; 
        gebIdeal = (17.686 * pesoIdeal) + 658.2; 
      } else if (idadeNum <= 30) { 
        geb = (15.057 * pesoNum) + 692.2; 
        gebIdeal = (15.057 * pesoIdeal) + 692.2; 
      } else if (idadeNum <= 60) { 
        geb = (11.472 * pesoNum) + 873.1; 
        gebIdeal = (11.472 * pesoIdeal) + 873.1; 
      } else { 
        geb = (11.711 * pesoNum) + 587.7; 
        gebIdeal = (11.711 * pesoIdeal) + 587.7; 
      }
    } else {
      if (idadeNum <= 3) { 
        geb = (58.31 * pesoNum) - 31.1; 
        gebIdeal = (58.31 * pesoIdeal) - 31.1; 
      } else if (idadeNum <= 10) { 
        geb = (20.315 * pesoNum) + 485.9; 
        gebIdeal = (20.315 * pesoIdeal) + 485.9; 
      } else if (idadeNum <= 18) { 
        geb = (13.384 * pesoNum) + 692.6; 
        gebIdeal = (13.384 * pesoIdeal) + 692.6; 
      } else if (idadeNum <= 30) { 
        geb = (14.818 * pesoNum) + 486.6; 
        gebIdeal = (14.818 * pesoIdeal) + 486.6; 
      } else if (idadeNum <= 60) { 
        geb = (8.126 * pesoNum) + 845.6; 
        gebIdeal = (8.126 * pesoIdeal) + 845.6; 
      } else { 
        geb = (9.082 * pesoNum) + 658.5; 
        gebIdeal = (9.082 * pesoIdeal) + 658.5; 
      }
    }

    const get = geb * fatorAtividade;
    const getIdeal = gebIdeal * fatorAtividade;

    // Cálculo dos macronutrientes
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

    // Distribuição das refeições
    const refeicoes = {
      '🍞 Café da manhã': getIdeal * 0.25,
      '🍎 Lanche da manhã': getIdeal * 0.05,
      '🍛 Almoço': getIdeal * 0.35,
      '☕ Lanche da tarde': getIdeal * 0.10,
      '🍽️ Jantar': getIdeal * 0.15,
      '🥛 Lanche da noite': getIdeal * 0.05,
    };

    const macroNutrientes = {
      '🍚 Carboidratos': {
        min: carboidratosMin.toFixed(1),
        max: carboidratosMax.toFixed(1),
        gramasMin: gramagemCarboidratoMin.toFixed(1),
        gramasMax: gramagemCarboidratoMax.toFixed(1),
        cor: 'green',
      },
      '🍗 Proteínas': {
        min: proteinaMin.toFixed(1),
        max: proteinaMax.toFixed(1),
        gramasMin: gramagemProteinaMin.toFixed(1),
        gramasMax: gramagemProteinaMax.toFixed(1),
        cor: 'red',
      },
      '🥑 Lipídios': {
        min: lipidiosMin.toFixed(1),
        max: lipidiosMax.toFixed(1),
        gramasMin: gramagemLipidioMin.toFixed(1),
        gramasMax: gramagemLipidioMax.toFixed(1),
        cor: 'orange',
      },
    };

    setResultado({
      nome,
      idade: idadeNum,
      sexo,
      peso: pesoNum,
      altura: alturaM,
      atividade,
      imc: imc.toFixed(1),
      corIMC,
      classificado,
      pesoIdeal,
      geb: geb.toFixed(1),
      get: get.toFixed(1),
      getIdeal: getIdeal.toFixed(1),
      refeicoes,
      macroNutrientes
    });

    setLoading(false);
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    calcularDados();
  };

  return (
    <div className="nutritional-calculator">
      <div className="container">
        <h1>Cálculo de Gasto Energético</h1>
        
        <form onSubmit={handleSubmit} className="calculator-form">
          <div className="form-group">
            <label htmlFor="nome">Nome</label>
            <input
              type="text"
              name="nome"
              id="nome"
              value={formData.nome}
              onChange={handleChange}
              required
            />
          </div>
          
          <div className="form-group">
            <label htmlFor="idade">Idade</label>
            <input
              type="number"
              name="idade"
              id="idade"
              value={formData.idade}
              onChange={handleChange}
              required
            />
          </div>
          
          <div className="form-group">
            <label htmlFor="sexo">Sexo</label>
            <select
              name="sexo"
              id="sexo"
              value={formData.sexo}
              onChange={handleChange}
              required
            >
              <option value="">Selecione</option>
              <option value="masculino">MASCULINO</option>
              <option value="feminino">FEMININO</option>
            </select>
          </div>
          
          <div className="form-group">
            <label htmlFor="peso">Peso (kg)</label>
            <input
              type="number"
              name="peso"
              id="peso"
              step="0.01"
              value={formData.peso}
              onChange={handleChange}
              required
            />
          </div>
          
          <div className="form-group">
            <label htmlFor="altura">Altura (em centímetros)</label>
            <input
              type="number"
              name="altura"
              id="altura"
              step="0.01"
              value={formData.altura}
              onChange={handleChange}
              required
            />
          </div>
          
          <div className="form-group">
            <label htmlFor="atividade">Nível de atividade física</label>
            <select
              name="atividade"
              id="atividade"
              value={formData.atividade}
              onChange={handleChange}
              required
            >
              <option value="">Selecione</option>
              <option value="sedentário">Sedentário</option>
              <option value="moderadamente ativo">Moderadamente ativo</option>
              <option value="ativo">Ativo</option>
            </select>
          </div>
          
          <button type="submit" disabled={loading}>
            {loading ? 'Calculando...' : 'Calcular'}
          </button>
        </form>
      </div>
      
      {resultado && (
        <div className="container resultado" id="resultado">
          <h2>Resultados</h2>
          <div className="resultado-content">
            <p><strong>Nome:</strong> {resultado.nome}</p>
            <p><strong>Idade:</strong> {resultado.idade}</p>
            <p><strong>Sexo:</strong> {resultado.sexo}</p>
            <p><strong>Peso:</strong> {resultado.peso} kg</p>
            <p><strong>Altura:</strong> {resultado.altura.toFixed(2)} m</p>
            <p><strong>Atividade Física:</strong> {resultado.atividade}</p>
            <p>
              <strong>IMC:</strong> 
              <span style={{backgroundColor: resultado.corIMC, padding: '2px 6px', borderRadius: '3px'}}>
                {resultado.imc}
              </span>
            </p>
            <p><strong>Classificação corporal:</strong> {resultado.classificado}</p>
            <p><strong>Peso Ideal:</strong> {resultado.pesoIdeal} kg</p>
            <p><strong>Gasto Energético Basal:</strong> {resultado.geb} Kcal</p>
            <p><strong>Gasto Energético Total:</strong> {resultado.get} Kcal</p>
            <p><strong>Gasto Energético Total Ideal:</strong> {resultado.getIdeal} Kcal</p>
            
            <hr />
            
            <h3>Distribuição energética</h3>
            {Object.entries(resultado.refeicoes).map(([refeicao, valor]) => (
              <p key={refeicao}>
                <strong>{refeicao}:</strong> {valor.toFixed(1)} Kcal
              </p>
            ))}
            
            <hr />
            
            <h3>Macro nutrientes</h3>
            {Object.entries(resultado.macroNutrientes).map(([nutriente, dados]) => (
              <p key={nutriente}>
                <strong>{nutriente}:</strong> Mínimo 
                <span style={{backgroundColor: dados.cor, padding: '2px 6px', borderRadius: '3px', margin: '0 4px'}}>
                  {dados.min}
                </span> 
                Kcal ({dados.gramasMin} gramas) e máximo 
                <span style={{backgroundColor: dados.cor, padding: '2px 6px', borderRadius: '3px', margin: '0 4px'}}>
                  {dados.max}
                </span> 
                Kcal ({dados.gramasMax} gramas)
              </p>
            ))}
          </div>
        </div>
      )}
    </div>
  );
};

export default NutritionalCalculator;