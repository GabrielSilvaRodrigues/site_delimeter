import React, { useState, useEffect } from 'react';
import './WelcomeTour.css';

const WelcomeTour = () => {
  const [showTour, setShowTour] = useState(false);
  const [currentSlide, setCurrentSlide] = useState(0);

  const slides = [
    {
      title: "Bem-vindo ao Delimeter!",
      text: "Sua plataforma completa para saúde nutricional inteligente.",
      image: "/public/assets/images/persefone-feliz.png"
    },
    {
      title: "Soluções Integradas",
      text: "Desde cálculo nutricional até conexão com nutricionistas - tudo em um só lugar.",
      image: "/public/assets/images/nutricionista.jpg"
    },
    {
      title: "Parcerias de Confiança",
      text: "Trabalhamos com SUS, CRN3 e CREMESP para oferecer o melhor serviço.",
      image: "/public/assets/images/sus.jpeg"
    }
  ];

  useEffect(() => {
    const tourCompleted = sessionStorage.getItem('delimeterTourCompleted');
    if (tourCompleted !== 'true') {
      setTimeout(() => setShowTour(true), 800);
    }
  }, []);

  const nextSlide = () => {
    if (currentSlide < slides.length - 1) {
      setCurrentSlide(currentSlide + 1);
    } else {
      completeTour();
    }
  };

  const prevSlide = () => {
    if (currentSlide > 0) {
      setCurrentSlide(currentSlide - 1);
    }
  };

  const completeTour = () => {
    sessionStorage.setItem('delimeterTourCompleted', 'true');
    setShowTour(false);
  };

  const handleKeyPress = (e) => {
    if (e.key === 'ArrowRight') {
      nextSlide();
    } else if (e.key === 'ArrowLeft') {
      prevSlide();
    } else if (e.key === 'Escape') {
      completeTour();
    }
  };

  useEffect(() => {
    if (showTour) {
      document.addEventListener('keydown', handleKeyPress);
      return () => document.removeEventListener('keydown', handleKeyPress);
    }
  }, [showTour, currentSlide]);

  if (!showTour) return null;

  return (
    <div className="welcome-tour-overlay" onClick={completeTour}>
      <div className="welcome-tour-modal" onClick={(e) => e.stopPropagation()}>
        <button className="close-tour" onClick={completeTour}>×</button>
        
        <div className="tour-content">
          <h2>{slides[currentSlide].title}</h2>
          
          <div className="tour-image">
            <img 
              src={slides[currentSlide].image} 
              alt={slides[currentSlide].title}
            />
          </div>
          
          <p>{slides[currentSlide].text}</p>
          
          <div className="tour-indicators">
            {slides.map((_, index) => (
              <div 
                key={index}
                className={`indicator ${index === currentSlide ? 'active' : ''}`}
                onClick={() => setCurrentSlide(index)}
              />
            ))}
          </div>
          
          <div className="tour-navigation">
            <button 
              onClick={prevSlide} 
              disabled={currentSlide === 0}
              className="nav-btn prev"
            >
              Anterior
            </button>
            
            <button 
              onClick={nextSlide} 
              className="nav-btn next"
            >
              {currentSlide === slides.length - 1 ? 'Finalizar' : 'Próximo'}
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default WelcomeTour;