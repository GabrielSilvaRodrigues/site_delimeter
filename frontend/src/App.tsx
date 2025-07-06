import React, { useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { Header, Footer } from './components';
import { Home, Sobre, Calculo, Login } from './pages';
import './App.css';

function App() {
  useEffect(() => {
    // Configurar funcionalidades de acessibilidade
    const setupAccessibility = () => {
      // Controle de alto contraste
      const toggleContrast = () => {
        document.body.classList.toggle('high-contrast');
        localStorage.setItem('high-contrast', 
          document.body.classList.contains('high-contrast').toString()
        );
      };

      // Controle de tamanho da fonte
      const increaseFontSize = () => {
        document.body.classList.remove('font-small');
        document.body.classList.add('font-large');
        localStorage.setItem('font-size', 'large');
      };

      const decreaseFontSize = () => {
        document.body.classList.remove('font-large');
        document.body.classList.add('font-small');
        localStorage.setItem('font-size', 'small');
      };

      const resetFontSize = () => {
        document.body.classList.remove('font-large', 'font-small');
        localStorage.setItem('font-size', 'normal');
      };

      // Restaurar preferências salvas
      const savedContrast = localStorage.getItem('high-contrast');
      if (savedContrast === 'true') {
        document.body.classList.add('high-contrast');
      }

      const savedFontSize = localStorage.getItem('font-size');
      if (savedFontSize === 'large') {
        document.body.classList.add('font-large');
      } else if (savedFontSize === 'small') {
        document.body.classList.add('font-small');
      }

      // Event listeners para botões de acessibilidade
      const contrastBtn = document.getElementById('toggleContrast');
      const increaseFontBtn = document.getElementById('increaseFontSize');
      const decreaseFontBtn = document.getElementById('decreaseFontSize');

      if (contrastBtn) contrastBtn.addEventListener('click', toggleContrast);
      if (increaseFontBtn) increaseFontBtn.addEventListener('click', increaseFontSize);
      if (decreaseFontBtn) decreaseFontBtn.addEventListener('click', decreaseFontSize);

      // Atalhos de teclado para acessibilidade
      const handleKeyboard = (e: KeyboardEvent) => {
        // Alt + C para alto contraste
        if (e.altKey && e.key === 'c') {
          e.preventDefault();
          toggleContrast();
        }
        // Alt + + para aumentar fonte
        if (e.altKey && e.key === '+') {
          e.preventDefault();
          increaseFontSize();
        }
        // Alt + - para diminuir fonte
        if (e.altKey && e.key === '-') {
          e.preventDefault();
          decreaseFontSize();
        }
        // Alt + 0 para resetar fonte
        if (e.altKey && e.key === '0') {
          e.preventDefault();
          resetFontSize();
        }
      };

      document.addEventListener('keydown', handleKeyboard);

      // Cleanup
      return () => {
        document.removeEventListener('keydown', handleKeyboard);
        if (contrastBtn) contrastBtn.removeEventListener('click', toggleContrast);
        if (increaseFontBtn) increaseFontBtn.removeEventListener('click', increaseFontSize);
        if (decreaseFontBtn) decreaseFontBtn.removeEventListener('click', decreaseFontSize);
      };
    };

    // Aguardar o DOM estar pronto
    const timer = setTimeout(setupAccessibility, 100);
    return () => clearTimeout(timer);
  }, []);

  return (
    <Router>
      <div className="App">
        <Header />
        <main className="main-content" role="main">
          <Routes>
            <Route path="/" element={<Home />} />
            <Route path="/sobre" element={<Sobre />} />
            <Route path="/calculo" element={<Calculo />} />
            <Route path="/login" element={<Login />} />
            {/* Rota 404 */}
            <Route path="*" element={
              <div className="error-page">
                <h1>Página não encontrada</h1>
                <p>A página que você está procurando não existe.</p>
                <a href="/" className="btn btn-primary">Voltar ao início</a>
              </div>
            } />
          </Routes>
        </main>
        <Footer />
      </div>
    </Router>
  );
}

export default App;
