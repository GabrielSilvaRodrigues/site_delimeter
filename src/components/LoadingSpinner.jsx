import React, { useState } from 'react';
import './LoadingSpinner.css';

const LoadingSpinner = () => {
  return (
    <div className="loading-spinner">
      <div className="spinner"></div>
      <p>Processando...</p>
    </div>
  );
};

export default LoadingSpinner;