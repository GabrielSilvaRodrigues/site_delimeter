import React from 'react';
import './DelimiterTool.css';
import DelimiterForm from '../components/DelimiterForm';
import ResultDisplay from '../components/ResultDisplay';

const DelimiterTool = () => {
  const [result, setResult] = React.useState('');
  const [loading, setLoading] = React.useState(false);

  const handleDelimit = async (text, delimiter) => {
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
      setResult(data.result || data.error);
    } catch (error) {
      setResult('Erro ao processar: ' + error.message);
    }
    setLoading(false);
  };

  return (
    <div className="delimiter-tool">
      <div className="tool-header">
        <h1>Ferramenta de Delimitação</h1>
        <p>Delimite seu texto com o caractere desejado</p>
      </div>
      
      <div className="tool-content">
        <DelimiterForm onSubmit={handleDelimit} loading={loading} />
        <ResultDisplay result={result} />
      </div>
    </div>
  );
};

export default DelimiterTool;