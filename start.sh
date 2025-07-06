#!/bin/bash

echo "🥗 Iniciando Deliméter - React + PHP"
echo "=================================="

# Verifica se o Docker está rodando
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker não está rodando. Por favor, inicie o Docker primeiro."
    exit 1
fi

echo "🐳 Construindo e iniciando containers..."
docker-compose up --build -d

echo ""
echo "✅ Aplicação iniciada com sucesso!"
echo ""
echo "📱 Frontend React: http://localhost:3000"
echo "🔗 Backend API: http://localhost:8000"
echo "🗄️  phpMyAdmin: http://localhost:8080"
echo ""
echo "Para ver os logs em tempo real:"
echo "docker-compose logs -f"
echo ""
echo "Para parar a aplicação:"
echo "docker-compose down"
