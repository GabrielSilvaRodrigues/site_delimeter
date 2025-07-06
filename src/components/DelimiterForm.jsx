import React, { useState } from 'react';
import './DelimiterForm.css';

const DelimiterForm = ({ onSubmit, loading }) => {
  const [text, setText] = useState('');
  const [delimiter, setDelimiter] = useState(',');

  const handleSubmit = (e) => {
    e.preventDefault();
    if (text.trim()) {
      onSubmit(text, delimiter);
    }
  };

  return (
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
  );
};

export default DelimiterForm;