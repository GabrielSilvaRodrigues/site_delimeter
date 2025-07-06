import React, { useEffect } from 'react';
import './WelcomeTour.css';

const WelcomeTour = () => {
  useEffect(() => {
    // Verificar se o tour já foi completado
    const tourCompleted = sessionStorage.getItem('delimeterTourCompleted');
    
    if (tourCompleted !== 'true') {
      // Implementar tour de boas-vindas
      const showTour = () => {
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

        let currentSlide = 0;

        const showSlide = () => {
          // Criar modal do tour
          const modal = document.createElement('div');
          modal.className = 'welcome-tour-modal';
          modal.innerHTML = `
            <div class="welcome-tour-content">
              <h2>${slides[currentSlide].title}</h2>
              <img src="${slides[currentSlide].image}" alt="${slides[currentSlide].title}" />
              <p>${slides[currentSlide].text}</p>
              <div class="tour-indicators">
                ${slides.map((_, i) => `
                  <div class="indicator ${i === currentSlide ? 'active' : ''}"></div>
                `).join('')}
              </div>
              <div class="tour-buttons">
                ${currentSlide > 0 ? '<button class="tour-prev">Anterior</button>' : ''}
                <button class="tour-next">${currentSlide === slides.length - 1 ? 'Finalizar' : 'Próximo'}</button>
              </div>
            </div>
          `;

          document.body.appendChild(modal);

          // Event listeners
          const nextBtn = modal.querySelector('.tour-next');
          const prevBtn = modal.querySelector('.tour-prev');

          nextBtn?.addEventListener('click', () => {
            document.body.removeChild(modal);
            if (currentSlide === slides.length - 1) {
              sessionStorage.setItem('delimeterTourCompleted', 'true');
            } else {
              currentSlide++;
              showSlide();
            }
          });

          prevBtn?.addEventListener('click', () => {
            document.body.removeChild(modal);
            currentSlide--;
            showSlide();
          });

          // Fechar clicando fora
          modal.addEventListener('click', (e) => {
            if (e.target === modal) {
              document.body.removeChild(modal);
              sessionStorage.setItem('delimeterTourCompleted', 'true');
            }
          });
        };

        setTimeout(showSlide, 1000);
      };

      showTour();
    }
  }, []);

  return null; // Componente não renderiza nada visualmente
};

export default WelcomeTour;