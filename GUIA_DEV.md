# 🚀 Guia Rápido de Desenvolvimento - Deliméter

## Executar o Projeto

### Com Docker (Recomendado)
```bash
# Construir e iniciar todos os serviços
./start.sh

# Ou manualmente
docker-compose up --build
```

### Desenvolvimento Local
```bash
# Iniciar frontend e backend simultaneamente
./dev.sh

# Ou manualmente:
# Terminal 1 - Backend
cd backend && composer install && php -S localhost:8000

# Terminal 2 - Frontend  
cd frontend && npm install && npm start
```

## URLs de Acesso

- **Frontend React**: http://localhost:3000
- **Backend API**: http://localhost:8000
- **phpMyAdmin**: http://localhost:8080 (apenas com Docker)

## API Endpoints

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| GET | `/api/home` | Dados da página inicial |
| GET | `/api/sobre` | Informações do projeto |
| POST | `/api/calculo` | Cálculo nutricional |

### Exemplo de Uso da API

```bash
# Teste rápido
./test-api.sh

# Cálculo nutricional
curl -X POST http://localhost:8000/api/calculo \
  -H "Content-Type: application/json" \
  -d '{
    "peso": 70,
    "altura": 175,
    "idade": 25,
    "sexo": "masculino",
    "atividade": 1.55
  }'
```

## Estrutura do Projeto

```
site_delimeter/
├── frontend/          # React + TypeScript
│   ├── src/
│   │   ├── components/  # Componentes reutilizáveis
│   │   ├── pages/      # Páginas da aplicação
│   │   └── services/   # Serviços da API
│   └── public/         # Assets estáticos
├── backend/           # PHP + Composer
│   ├── src/
│   │   ├── Controllers/ # Controladores da API
│   │   ├── Models/     # Modelos e entidades
│   │   └── Routes/     # Definição de rotas
│   └── database/      # Scripts SQL
└── docker-compose.yml # Orquestração
```

## Comandos Úteis

```bash
# Parar todos os containers
docker-compose down

# Ver logs em tempo real
docker-compose logs -f

# Reinstalar dependências do frontend
cd frontend && rm -rf node_modules && npm install

# Reinstalar dependências do backend
cd backend && rm -rf vendor && composer install

# Executar testes da API
./test-api.sh
```

## Recursos de Acessibilidade

### Atalhos de Teclado
- `Alt + C`: Alternar alto contraste
- `Alt + +`: Aumentar fonte
- `Alt + -`: Diminuir fonte
- `Alt + 0`: Resetar fonte

### Funcionalidades
- Alto contraste
- Ajuste de tamanho da fonte
- Navegação por teclado
- Compatibilidade com leitores de tela
- Design responsivo

## Tecnologias

- **Frontend**: React 18, TypeScript, React Router, Axios
- **Backend**: PHP 8.1, Composer, API RESTful
- **Banco**: MySQL 8.0
- **DevOps**: Docker, Docker Compose

## Próximos Passos

1. Implementar autenticação JWT
2. Adicionar testes automatizados
3. Criar sistema de usuários completo
4. Implementar histórico de cálculos
5. Deploy em produção
