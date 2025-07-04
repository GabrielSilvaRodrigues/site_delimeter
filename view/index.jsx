import React from "react";
import { createRoot } from "react-dom/client";
import App from "./App";
import "./assets/styles/delimeter.css";
import "./assets/styles/acessibilidade.css";
// Importe outros CSS globais se necessário

const container = document.getElementById("root");
const root = createRoot(container);
root.render(<App />);
