import React, { useState } from 'react';
import { delimeterService } from '../services/api';
import './Calculo.css';

interface FormData {
  peso: number | '';
  altura: number | '';
  idade: number | '';
  sexo: string;
  atividade: number;
}

interface Resultado {
  imc: number;
  classificacao_imc: string;
  tmb: number;
  get: number;
  macronutrientes: {
    proteinas: number;
    lipidios: number;
    carboidratos: number;
  };
}

const Calculo: React.FC = () => {
  const [formData, setFormData] = useState<FormData>({
    peso: '',
    altura: '',
    idade: '',
    sexo: '',
    atividade: 1.2,
  });

  const [resultado, setResultado] = useState<Resultado | null>(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: name === 'sexo' ? value : (value === '' ? '' : Number(value))
    }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    try {
      const dados = {
        peso: Number(formData.peso),
        altura: Number(formData.altura),
        idade: Number(formData.idade),
        sexo: formData.sexo,
        atividade: formData.atividade,
      };

      const response = await delimeterService.calcularNutricional(dados);
      
      if (response.success) {
        setResultado(response.data);
      } else {
        setError(response.error || 'Erro ao calcular');
      }
    } catch (err: any) {
      setError(err.response?.data?.error || 'Erro de conexão com o servidor');
      console.error('Erro:', err);
    } finally {
      setLoading(false);
    }
  };

  const getNivelAtividade = (valor: number) => {
    const niveis = {
      1.2: 'Sedentário',
      1.375: 'Levemente ativo',
      1.55: 'Moderadamente ativo',
      1.725: 'Muito ativo',
      1.9: 'Extremamente ativo'
    };
    return niveis[valor as keyof typeof niveis] || 'Desconhecido';
  };

  return (
    <div className="calculo">
      <div className="container">
        <header className="page-header">
          <h1>Calculadora Nutricional</h1>
          <p>Calcule seu IMC, gasto energético e necessidades de macronutrientes</p>
        </header>

        <div className="calculo-content">
          <div className="form-section">
            <form onSubmit={handleSubmit} className="calculo-form">
              <div className="form-group">
                <label htmlFor="peso">Peso (kg)</label>
                <input
                  type="number"
                  id="peso"
                  name="peso"
                  value={formData.peso}
                  onChange={handleInputChange}
                  required
                  min="1"
                  step="0.1"
                  placeholder="Ex: 70"
                />
              </div>

              <div className="form-group">
                <label htmlFor="altura">Altura (cm)</label>
                <input
                  type="number"
                  id="altura"
                  name="altura"
                  value={formData.altura}
                  onChange={handleInputChange}
                  required
                  min="1"
                  step="1"
                  placeholder="Ex: 175"
                />
              </div>

              <div className="form-group">
                <label htmlFor="idade">Idade (anos)</label>
                <input
                  type="number"
                  id="idade"
                  name="idade"
                  value={formData.idade}
                  onChange={handleInputChange}
                  required
                  min="1"
                  step="1"
                  placeholder="Ex: 25"
                />
              </div>

              <div className="form-group">
                <label htmlFor="sexo">Sexo</label>
                <select
                  id="sexo"
                  name="sexo"
                  value={formData.sexo}
                  onChange={handleInputChange}
                  required
                >
                  <option value="">Selecione</option>
                  <option value="masculino">Masculino</option>
                  <option value="feminino">Feminino</option>
                </select>
              </div>

              <div className="form-group">
                <label htmlFor="atividade">Nível de Atividade</label>
                <select
                  id="atividade"
                  name="atividade"
                  value={formData.atividade}
                  onChange={handleInputChange}
                  required
                >
                  <option value={1.2}>Sedentário (pouco ou nenhum exercício)</option>
                  <option value={1.375}>Levemente ativo (exercício leve 1-3 dias/semana)</option>
                  <option value={1.55}>Moderadamente ativo (exercício moderado 3-5 dias/semana)</option>
                  <option value={1.725}>Muito ativo (exercício pesado 6-7 dias/semana)</option>
                  <option value={1.9}>Extremamente ativo (exercício muito pesado, trabalho físico)</option>
                </select>
              </div>

              {error && <div className="error-message">{error}</div>}

              <button type="submit" className="btn btn-primary" disabled={loading}>
                {loading ? 'Calculando...' : 'Calcular'}
              </button>
            </form>
          </div>

          {resultado && (
            <div className="resultado-section">
              <h2>Seus Resultados</h2>
              
              <div className="resultado-cards">
                <div className="resultado-card imc">
                  <h3>Índice de Massa Corporal (IMC)</h3>
                  <div className="valor">{resultado.imc}</div>
                  <div className="classificacao">{resultado.classificacao_imc}</div>
                </div>

                <div className="resultado-card tmb">
                  <h3>Taxa Metabólica Basal (TMB)</h3>
                  <div className="valor">{resultado.tmb} kcal/dia</div>
                  <p>Energia necessária em repouso</p>
                </div>

                <div className="resultado-card get">
                  <h3>Gasto Energético Total (GET)</h3>
                  <div className="valor">{resultado.get} kcal/dia</div>
                  <p>Energia total necessária por dia</p>
                </div>
              </div>

              <div className="macronutrientes">
                <h3>Distribuição de Macronutrientes</h3>
                <div className="macro-cards">
                  <div className="macro-card proteinas">
                    <h4>Proteínas</h4>
                    <div className="valor">{resultado.macronutrientes.proteinas}g</div>
                    <div className="porcentagem">15% das calorias</div>
                  </div>
                  <div className="macro-card lipidios">
                    <h4>Lipídios</h4>
                    <div className="valor">{resultado.macronutrientes.lipidios}g</div>
                    <div className="porcentagem">25% das calorias</div>
                  </div>
                  <div className="macro-card carboidratos">
                    <h4>Carboidratos</h4>
                    <div className="valor">{resultado.macronutrientes.carboidratos}g</div>
                    <div className="porcentagem">60% das calorias</div>
                  </div>
                </div>
              </div>

              <div className="observacoes">
                <h4>Observações importantes:</h4>
                <ul>
                  <li>Estes valores são estimativas baseadas em fórmulas científicas</li>
                  <li>Consulte sempre um nutricionista para um plano alimentar personalizado</li>
                  <li>Os valores podem variar conforme seu metabolismo individual</li>
                </ul>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default Calculo;
