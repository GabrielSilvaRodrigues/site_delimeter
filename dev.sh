#!/bin/bash

echo "🥗 Iniciando Deliméter - Desenvolvimento Local"
echo "============================================="

# Função para iniciar o backend
start_backend() {
    echo "🔧 Iniciando Backend PHP..."
    cd backend
    if [ ! -d "vendor" ]; then
        echo "📦 Instalando dependências do Composer..."
        composer install
    fi
    php -S localhost:8000 &
    BACKEND_PID=$!
    cd ..
}

# Função para iniciar o frontend
start_frontend() {
    echo "⚛️  Iniciando Frontend React..."
    cd frontend
    if [ ! -d "node_modules" ]; then
        echo "📦 Instalando dependências do NPM..."
        npm install
    fi
    npm start &
    FRONTEND_PID=$!
    cd ..
}

# Função para limpeza
cleanup() {
    echo ""
    echo "🛑 Parando serviços..."
    kill $BACKEND_PID 2>/dev/null
    kill $FRONTEND_PID 2>/dev/null
    exit 0
}

# Captura Ctrl+C
trap cleanup INT

echo "🚀 Iniciando serviços..."

start_backend
start_frontend

echo ""
echo "✅ Aplicação iniciada com sucesso!"
echo ""
echo "📱 Frontend React: http://localhost:3000"
echo "🔗 Backend API: http://localhost:8000"
echo ""
echo "Pressione Ctrl+C para parar os serviços."

# Mantém o script rodando
wait
