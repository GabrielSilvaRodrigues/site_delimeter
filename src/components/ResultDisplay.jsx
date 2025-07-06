import React from 'react';
import './ResultDisplay.css';

const ResultDisplay = ({ result }) => {
  if (!result) return null;

  const copyToClipboard = () => {
    navigator.clipboard.writeText(result).then(() => {
      alert('Resultado copiado para a área de transferência!');
    });
  };

  return (
    <div className="result-display">
      <h3>Resultado:</h3>
      <div className="result-content">
        <pre>{result}</pre>
      </div>
      <button onClick={copyToClipboard} className="copy-btn">
        Copiar Resultado
      </button>
    </div>
  );
};

export default ResultDisplay;