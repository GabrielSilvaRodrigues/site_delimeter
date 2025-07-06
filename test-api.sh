#!/bin/bash

echo "🧪 Testando API do Deliméter"
echo "============================"

# Iniciar servidor PHP se não estiver rodando
if ! pgrep -f "php.*8000" > /dev/null; then
    echo "📡 Iniciando servidor PHP..."
    cd backend
    php -S localhost:8000 > /dev/null 2>&1 &
    PHP_PID=$!
    cd ..
    sleep 3
else
    echo "📡 Servidor PHP já está rodando"
fi

echo ""
echo "🏠 Testando endpoint /api/home:"
curl -s http://localhost:8000/api/home | jq '.' || echo "Erro ao acessar /api/home"

echo ""
echo "ℹ️  Testando endpoint /api/sobre:"
curl -s http://localhost:8000/api/sobre | jq '.' || echo "Erro ao acessar /api/sobre"

echo ""
echo "🧮 Testando endpoint /api/calculo:"
curl -s -X POST http://localhost:8000/api/calculo \
  -H "Content-Type: application/json" \
  -d '{
    "peso": 70,
    "altura": 175,
    "idade": 25,
    "sexo": "masculino",
    "atividade": 1.55
  }' | jq '.' || echo "Erro ao acessar /api/calculo"

echo ""
echo "✅ Testes concluídos!"

# Parar servidor se foi iniciado por este script
if [ ! -z "$PHP_PID" ]; then
    echo "🛑 Parando servidor de teste..."
    kill $PHP_PID 2>/dev/null
fi
