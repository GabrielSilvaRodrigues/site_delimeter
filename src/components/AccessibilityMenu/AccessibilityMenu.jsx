import React, { useState, useEffect } from 'react';
import './AccessibilityMenu.css';

const AccessibilityMenu = () => {
  const [fontSize, setFontSize] = useState(16);
  const [highContrast, setHighContrast] = useState(false);
  const [colorBlindType, setColorBlindType] = useState('');

  useEffect(() => {
    document.documentElement.style.fontSize = fontSize + 'px';
  }, [fontSize]);

  useEffect(() => {
    if (highContrast) {
      document.body.classList.add('high-contrast');
    } else {
      document.body.classList.remove('high-contrast');
    }
  }, [highContrast]);

  useEffect(() => {
    // Remove todas as classes de daltonismo
    document.body.classList.remove('protanopia', 'deuteranopia', 'tritanopia');
    
    if (colorBlindType) {
      document.body.classList.add(colorBlindType);
    }
  }, [colorBlindType]);

  const increaseFontSize = () => {
    if (fontSize < 24) {
      setFontSize(fontSize + 2);
    }
  };

  const decreaseFontSize = () => {
    if (fontSize > 12) {
      setFontSize(fontSize - 2);
    }
  };

  const toggleContraste = () => {
    setHighContrast(!highContrast);
  };

  const toggleDaltonismo = (type) => {
    setColorBlindType(colorBlindType === type ? '' : type);
  };

  const resetarAcessibilidade = () => {
    setFontSize(16);
    setHighContrast(false);
    setColorBlindType('');
  };

  return (
    <div className="accessibility-menu">
      <div className="accessibility-section">
        <p>Modificar tamanho da fonte</p>
        <div className="font-controls">
          <button 
            onClick={increaseFontSize} 
            aria-label="Aumentar tamanho da fonte"
            className="font-btn"
          >
            A+
          </button>
          <button 
            onClick={decreaseFontSize} 
            aria-label="Diminuir tamanho da fonte"
            className="font-btn"
          >
            A-
          </button>
        </div>
      </div>

      <div className="accessibility-section">
        <p>Modificar estilo da exibição</p>
        <div className="display-controls">
          <button 
            onClick={toggleContraste} 
            aria-pressed={highContrast}
            aria-label="Ativar ou desativar alto contraste"
            className={`contrast-btn ${highContrast ? 'active' : ''}`}
          >
            Alto Contraste
          </button>
          
          <button 
            onClick={() => toggleDaltonismo('protanopia')} 
            aria-label="Simular protanopia"
            className={`colorblind-btn ${colorBlindType === 'protanopia' ? 'active' : ''}`}
          >
            Protanopia
          </button>
          
          <button 
            onClick={() => toggleDaltonismo('deuteranopia')} 
            aria-label="Simular deuteranopia"
            className={`colorblind-btn ${colorBlindType === 'deuteranopia' ? 'active' : ''}`}
          >
            Deuteranopia
          </button>
          
          <button 
            onClick={() => toggleDaltonismo('tritanopia')} 
            aria-label="Simular tritanopia"
            className={`colorblind-btn ${colorBlindType === 'tritanopia' ? 'active' : ''}`}
          >
            Tritanopia
          </button>
        </div>
      </div>

      <button 
        onClick={resetarAcessibilidade} 
        aria-label="Restaurar configurações de acessibilidade"
        className="reset-btn"
      >
        Voltar ao normal
      </button>
    </div>
  );
};

export default AccessibilityMenu;