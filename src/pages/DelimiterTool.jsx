import React, { useState } from 'react';
import './DelimiterTool.css';

const DelimiterTool = () => {
  const [text, setText] = useState('');
  const [delimiter, setDelimiter] = useState(',');
  const [result, setResult] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!text.trim()) return;

    setLoading(true);
    try {
      const response = await fetch('/api/delimiter.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          text: text,
          delimiter: delimiter
        })
      });
      
      const data = await response.json();
      
      if (data.result) {
        setResult(data.result);
      } else {
        setResult('Erro: ' + (data.error || 'Erro desconhecido'));
      }
    } catch (error) {
      setResult('Erro ao processar: ' + error.message);
    }
    setLoading(false);
  };

  const copyToClipboard = () => {
    navigator.clipboard.writeText(result).then(() => {
      alert('Resultado copiado para a área de transferência!');
    });
  };

  return (
    <div className="delimiter-tool">
      <div className="tool-header">
        <h1>Ferramenta de Delimitação</h1>
        <p>Delimite seu texto com o caractere desejado</p>
      </div>
      
      <div className="tool-content">
        <form className="delimiter-form" onSubmit={handleSubmit}>
          <div className="form-group">
            <label htmlFor="text">Texto para delimitar:</label>
            <textarea
              id="text"
              value={text}
              onChange={(e) => setText(e.target.value)}
              placeholder="Digite seu texto aqui..."
              rows="5"
              required
            />
          </div>
          
          <div className="form-group">
            <label htmlFor="delimiter">Delimitador:</label>
            <select
              id="delimiter"
              value={delimiter}
              onChange={(e) => setDelimiter(e.target.value)}
            >
              <option value=",">Vírgula (,)</option>
              <option value=";">Ponto e vírgula (;)</option>
              <option value="|">Pipe (|)</option>
              <option value="\t">Tab</option>
              <option value=" ">Espaço</option>
              <option value="-">Hífen (-)</option>
            </select>
          </div>
          
          <button type="submit" disabled={loading || !text.trim()}>
            {loading ? 'Processando...' : 'Delimitar'}
          </button>
        </form>

        {result && (
          <div className="result-display">
            <h3>Resultado:</h3>
            <div className="result-content">
              <pre>{result}</pre>
            </div>
            <button onClick={copyToClipboard} className="copy-btn">
              Copiar Resultado
            </button>
          </div>
        )}
      </div>
    </div>
  );
};

export default DelimiterTool;