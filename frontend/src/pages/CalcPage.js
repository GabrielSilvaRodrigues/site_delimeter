import React, { useState } from 'react';
import './CalcPage.css';

const CalcPage = () => {
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
  const [error, setError] = useState('');

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({
      ...formData,
      [name]: value
    });
  };

  const validarFormulario = () => {
    for (const key in formData) {
      if (!formData[key]) {
        setError(`Por favor, preencha o campo ${key}`);
        return false;
      }
    }
    
    if (isNaN(formData.idade) || formData.idade <= 0) {
      setError('A idade deve ser um número positivo');
      return false;
    }
    
    if (isNaN(formData.peso) || formData.peso <= 0) {
      setError('O peso deve ser um número positivo');
      return false;
    }
    
    if (isNaN(formData.altura) || formData.altura <= 0) {
      setError('A altura deve ser um número positivo');
      return false;
    }
    
    setError('');
    return true;
  };

  const enviarDados = async () => {
    if (!validarFormulario()) return;
    
    setLoading(true);
    
    try {
      // Simulando o cálculo - em produção aqui seria feita uma chamada API
      // Fórmula de Harris-Benedict para calcular GEB
      let geb;
      if (formData.sexo === 'masculino') {
        geb = 88.362 + (13.397 * parseFloat(formData.peso)) + (4.799 * parseFloat(formData.altura)) - (5.677 * parseInt(formData.idade));
      } else {
        geb = 447.593 + (9.247 * parseFloat(formData.peso)) + (3.098 * parseFloat(formData.altura)) - (4.330 * parseInt(formData.idade));
      }
      
      // Fator de atividade para calcular GET
      let fatorAtividade;
      switch (formData.atividade) {
        case 'sedentário':
          fatorAtividade = 1.2;
          break;
        case 'moderadamente ativo':
          fatorAtividade = 1.55;
          break;
        case 'ativo':
          fatorAtividade = 1.9;
          break;
        default:
          fatorAtividade = 1.2;
      }
      
      const get = geb * fatorAtividade;
      
      // Cálculo dos macronutrientes (exemplo simplificado)
      const proteinas = (get * 0.25) / 4; // 25% das calorias, dividido por 4 calorias por grama
      const carboidratos = (get * 0.55) / 4; // 55% das calorias
      const gorduras = (get * 0.20) / 9; // 20% das calorias, dividido por 9 calorias por grama
      
      setResultado({
        nome: formData.nome,
        idade: formData.idade,
        sexo: formData.sexo,
        peso: formData.peso,
        altura: formData.altura,
        atividade: formData.atividade,
        geb: Math.round(geb),
        get: Math.round(get),
        proteinas: Math.round(proteinas),
        carboidratos: Math.round(carboidratos),
        gorduras: Math.round(gorduras)
      });
      
    } catch (error) {
      setError('Erro ao calcular. Por favor, tente novamente.');
      console.error('Erro:', error);
    } finally {
      setLoading(false);
    }
  };

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
          {error && <div className="error-message">{error}</div>}
          <form id="formulario">
            <div className="form-group">
              <label htmlFor="nome">Nome</label>
              <input 
                type="text" 
                name="nome" 
                required 
                id="nome"
                value={formData.nome}
                onChange={handleChange}
              />
            </div>
            <div className="form-group">
              <label htmlFor="idade">Idade</label>
              <input 
                type="number" 
                name="idade" 
                required 
                id="idade"
                value={formData.idade}
                onChange={handleChange}
              />
            </div>
            <div className="form-group">
              <label htmlFor="sexo">Sexo</label>
              <select 
                name="sexo" 
                required 
                id="sexo"
                value={formData.sexo}
                onChange={handleChange}
              >
                <option value="">Selecione</option>
                <option value="masculino">MASCULINO</option>
                <option value="feminino">FEMININO</option>
              </select>
            </div>
            <div className="form-group">
              <label htmlFor="peso">Peso</label>
              <input 
                type="number" 
                name="peso" 
                step="0.01" 
                required 
                id="peso"
                value={formData.peso}
                onChange={handleChange}
              />
            </div>
            <div className="form-group">
              <label htmlFor="altura">Altura (em centímetros)</label>
              <input 
                type="number" 
                name="altura" 
                step="0.01" 
                required 
                id="altura"
                value={formData.altura}
                onChange={handleChange}
              />
            </div>
            <div className="form-group">
              <label htmlFor="atividade">Nível de atividade física</label>
              <select 
                name="atividade" 
                required 
                id="atividade"
                value={formData.atividade}
                onChange={handleChange}
              >
                <option value="">Selecione</option>
                <option value="sedentário">Sedentário</option>
                <option value="moderadamente ativo">Moderadamente ativo</option>
                <option value="ativo">Ativo</option>
              </select>
            </div>
            <button 
              type="button" 
              onClick={enviarDados}
              disabled={loading}
            >
              {loading ? 'Calculando...' : 'Enviar'}
            </button>
          </form>
        </div>
        <div className="container" id="resultado">
          {resultado && (
            <div className="resultado-calculo">
              <h2>Resultado para {resultado.nome}</h2>
              <p><strong>Idade:</strong> {resultado.idade} anos</p>
              <p><strong>Sexo:</strong> {resultado.sexo}</p>
              <p><strong>Peso:</strong> {resultado.peso} kg</p>
              <p><strong>Altura:</strong> {resultado.altura} cm</p>
              <p><strong>Nível de Atividade:</strong> {resultado.atividade}</p>
              <hr />
              <p><strong>Gasto Energético Basal (GEB):</strong> {resultado.geb} kcal/dia</p>
              <p><strong>Gasto Energético Total (GET):</strong> {resultado.get} kcal/dia</p>
              <hr />
              <h3>Recomendação de Macronutrientes:</h3>
              <p><strong>Proteínas:</strong> {resultado.proteinas} g/dia</p>
              <p><strong>Carboidratos:</strong> {resultado.carboidratos} g/dia</p>
              <p><strong>Gorduras:</strong> {resultado.gorduras} g/dia</p>
              <div className="nota-calculo">
                <p>Nota: Este é um cálculo estimado baseado na fórmula de Harris-Benedict. 
                   Para uma avaliação mais precisa, consulte um nutricionista.</p>
              </div>
            </div>
          )}
        </div>
      </div>
    </main>
  );
};

export default CalcPage;