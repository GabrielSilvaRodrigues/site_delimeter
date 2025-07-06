import React, { useState } from 'react';
import NutritionalCalculator from '../../components/NutritionalCalculator/NutritionalCalculator';
import './Calculo.css';

const Calculo = () => {
  return (
    <main className="calculo-main">
      <section className="container-main">
        <div className="container-main-image">
          <img src="/public/assets/images/almoço.jpg" alt="Alimentação saudável" />
          <h1>PRIORIZAMOS A SUA ALIMENTAÇÃO</h1>
        </div>
      </section>
      
      <div className="container-calc" id="container-formulario-nutricional">
        <NutritionalCalculator />
      </div>
    </main>
  );
};

export default Calculo;