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
  const [errors, setErrors] = useState({});

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
    // Remove error when user starts typing
    if (errors[name]) {
      setErrors(prev => ({
        ...prev,
        [name]: ''
      }));
    }
  };

  const validarFormulario = () => {
    const newErrors = {};
    
    if (!formData.nome.trim()) newErrors.nome = 'Nome é obrigatório';
    if (!formData.idade || formData.idade < 1 || formData.idade > 120) {
      newErrors.idade = 'Idade deve ser entre 1 e 120 anos';
    }
    if (!formData.sexo) newErrors.sexo = 'Sexo é obrigatório';
    if (!formData.peso || formData.peso < 1 || formData.peso > 500) {
      newErrors.peso = 'Peso deve ser entre 1 e 500 kg';
    }
    if (!formData.altura || formData.altura < 50 || formData.altura > 250) {
      newErrors.altura = 'Altura deve ser entre 50 e 250 cm';
    }
    if (!formData.atividade) newErrors.atividade = 'Nível de atividade é obrigatório';

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const calcularDados = () => {
    const idade = parseInt(formData.idade);
    const peso = parseFloat(formData.peso);
    const altura = parseFloat(formData.altura) / 100; // Convert to meters
    const { sexo, atividade } = formData;

    // Calculate BMI
    const imc = peso / (altura * altura);
    const pesoIdeal = ((altura ** 2) * 21.7).toFixed(1);

    // BMI Classification
    let classificado = '';
    let corIMC = '';

    if (imc < 18.5) {
      classificado = 'Abaixo do peso';
      corIMC = 'blue';
    } else if (imc < 25) {
      classificado = 'Peso normal';
      corIMC = 'green';
    } else if (imc < 30) {
      classificado = 'Sobrepeso';
      corIMC = 'orange';
    } else {
      classificado = 'Obesidade';
      corIMC = 'red';
    }

    // Activity factor
    const fatorAtividade = {
      'sedentário': 1.55,
      'moderadamente ativo': 1.85,
      'ativo': 2.20
    }[atividade] || 1.55;

    // Calculate GEB (Basal Energy Expenditure)
    let geb, gebIdeal;
    if (sexo === 'masculino') {
      geb = (10 * peso) + (6.25 * (altura * 100)) - (5 * idade) + 5;
      gebIdeal = (10 * parseFloat(pesoIdeal)) + (6.25 * (altura * 100)) - (5 * idade) + 5;
    } else {
      geb = (10 * peso) + (6.25 * (altura * 100)) - (5 * idade) - 161;
      gebIdeal = (10 * parseFloat(pesoIdeal)) + (6.25 * (altura * 100)) - (5 * idade) - 161;
    }

    // Calculate GET (Total Energy Expenditure)
    const get = geb * fatorAtividade;
    const getIdeal = gebIdeal * fatorAtividade;

    // Calculate macronutrients (based on ideal weight)
    const proteinaMin = getIdeal * 0.10;
    const proteinaMax = getIdeal * 0.15;
    const gramagemProteinaMin = (proteinaMin / 4).toFixed(1);
    const gramagemProteinaMax = (proteinaMax / 4).toFixed(1);

    const carboidratosMin = getIdeal * 0.15;
    const carboidratosMax = getIdeal * 0.30;
    const gramagemCarboidratoMin = (carboidratosMin / 4).toFixed(1);
    const gramagemCarboidratoMax = (carboidratosMax / 4).toFixed(1);

    const lipidiosMin = getIdeal * 0.55;
    const lipidiosMax = getIdeal * 0.75;
    const gramagemLipidioMin = (lipidiosMin / 9).toFixed(1);
    const gramagemLipidioMax = (lipidiosMax / 9).toFixed(1);

    // Meal distribution
    const refeicoes = {
      '🍞 Café da manhã': (getIdeal * 0.25).toFixed(0),
      '🍎 Lanche da manhã': (getIdeal * 0.05).toFixed(0),
      '🍛 Almoço': (getIdeal * 0.35).toFixed(0),
      '☕ Lanche da tarde': (getIdeal * 0.10).toFixed(0),
      '🍽️ Jantar': (getIdeal * 0.15).toFixed(0),
      '🥛 Lanche da noite': (getIdeal * 0.05).toFixed(0)
    };

    const macroNutrientes = {
      '🍚 Carboidratos': {
        min: carboidratosMin.toFixed(1),
        max: carboidratosMax.toFixed(1),
        gramasMin: gramagemCarboidratoMin,
        gramasMax: gramagemCarboidratoMax,
        cor: 'green'
      },
      '🍗 Proteínas': {
        min: proteinaMin.toFixed(1),
        max: proteinaMax.toFixed(1),
        gramasMin: gramagemProteinaMin,
        gramasMax: gramagemProteinaMax,
        cor: 'red'
      },
      '🥑 Lipídios': {
        min: lipidiosMin.toFixed(1),
        max: lipidiosMax.toFixed(1),
        gramasMin: gramagemLipidioMin,
        gramasMax: gramagemLipidioMax,
        cor: 'orange'
      }
    };

    return {
      imc: imc.toFixed(2),
      classificado,
      corIMC,
      pesoIdeal,
      geb: geb.toFixed(0),
      gebIdeal: gebIdeal.toFixed(0),
      get: get.toFixed(0),
      getIdeal: getIdeal.toFixed(0),
      refeicoes,
      macroNutrientes
    };
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    setLoading(true);

    if (!validarFormulario()) {
      setLoading(false);
      return;
    }

    try {
      const resultadoCalculado = calcularDados();
      setResultado(resultadoCalculado);
    } catch (error) {
      console.error('Erro no cálculo:', error);
      setErrors({ submit: 'Erro ao calcular. Verifique os dados inseridos.' });
    }

    setLoading(false);
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
              id="nome"
              name="nome"
              value={formData.nome}
              onChange={handleChange}
              className={errors.nome ? 'error' : ''}
              required
            />
            {errors.nome && <span className="error">{errors.nome}</span>}
          </div>

          <div className="form-group">
            <label htmlFor="idade">Idade</label>
            <input
              type="number"
              id="idade"
              name="idade"
              value={formData.idade}
              onChange={handleChange}
              className={errors.idade ? 'error' : ''}
              min="1"
              max="120"
              required
            />
            {errors.idade && <span className="error">{errors.idade}</span>}
          </div>

          <div className="form-group">
            <label htmlFor="sexo">Sexo</label>
            <select
              id="sexo"
              name="sexo"
              value={formData.sexo}
              onChange={handleChange}
              className={errors.sexo ? 'error' : ''}
              required
            >
              <option value="">Selecione</option>
              <option value="masculino">Masculino</option>
              <option value="feminino">Feminino</option>
            </select>
            {errors.sexo && <span className="error">{errors.sexo}</span>}
          </div>

          <div className="form-group">
            <label htmlFor="peso">Peso (kg)</label>
            <input
              type="number"
              id="peso"
              name="peso"
              value={formData.peso}
              onChange={handleChange}
              className={errors.peso ? 'error' : ''}
              step="0.1"
              min="1"
              max="500"
              required
            />
            {errors.peso && <span className="error">{errors.peso}</span>}
          </div>

          <div className="form-group">
            <label htmlFor="altura">Altura (cm)</label>
            <input
              type="number"
              id="altura"
              name="altura"
              value={formData.altura}
              onChange={handleChange}
              className={errors.altura ? 'error' : ''}
              min="50"
              max="250"
              required
            />
            {errors.altura && <span className="error">{errors.altura}</span>}
          </div>

          <div className="form-group">
            <label htmlFor="atividade">Nível de atividade física</label>
            <select
              id="atividade"
              name="atividade"
              value={formData.atividade}
              onChange={handleChange}
              className={errors.atividade ? 'error' : ''}
              required
            >
              <option value="">Selecione</option>
              <option value="sedentário">Sedentário</option>
              <option value="moderadamente ativo">Moderadamente ativo</option>
              <option value="ativo">Ativo</option>
            </select>
            {errors.atividade && <span className="error">{errors.atividade}</span>}
          </div>

          {errors.submit && <div className="error-message">{errors.submit}</div>}

          <button type="submit" disabled={loading}>
            {loading ? 'Calculando...' : 'Calcular'}
          </button>
        </form>

        {resultado && (
          <div className="resultado" id="resultado">
            <h2>Resultado para {formData.nome}</h2>
            
            <div className="resultado-section">
              <h3>📊 Índice de Massa Corporal (IMC)</h3>
              <p>
                <strong>IMC:</strong> <span style={{color: resultado.corIMC}}>{resultado.imc}</span> - {resultado.classificado}
              </p>
              <p><strong>Peso Ideal:</strong> {resultado.pesoIdeal} kg</p>
            </div>

            <div className="resultado-section">
              <h3>🔥 Gasto Energético</h3>
              <p><strong>GEB (Atual):</strong> {resultado.geb} kcal/dia</p>
              <p><strong>GEB (Ideal):</strong> {resultado.gebIdeal} kcal/dia</p>
              <p><strong>GET (Atual):</strong> {resultado.get} kcal/dia</p>
              <p><strong>GET (Ideal):</strong> {resultado.getIdeal} kcal/dia</p>
            </div>

            <div className="resultado-section">
              <h3>🍽️ Distribuição de Refeições (baseado no GET ideal)</h3>
              {Object.entries(resultado.refeicoes).map(([refeicao, calorias]) => (
                <p key={refeicao}><strong>{refeicao}:</strong> {calorias} kcal</p>
              ))}
            </div>

            <div className="resultado-section">
              <h3>🥗 Macronutrientes (baseado no GET ideal)</h3>
              {Object.entries(resultado.macroNutrientes).map(([macro, dados]) => (
                <div key={macro} style={{marginBottom: '10px'}}>
                  <p><strong>{macro}:</strong></p>
                  <p style={{marginLeft: '20px', color: dados.cor}}>
                    Calorias: {dados.min} - {dados.max} kcal/dia<br/>
                    Gramas: {dados.gramasMin} - {dados.gramasMax} g/dia
                  </p>
                </div>
              ))}
            </div>
          </div>
        )}
      </div>
    </div>
  );
};

export default NutritionalCalculator;