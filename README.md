# 🥗 Deliméter - Versão React + PHP

Bem-vindo ao **Deliméter** renovado – o seu portal para uma vida mais saudável, inteligente e conectada!  
Agora com frontend React e backend PHP com API RESTful.

---

## 🚀 Arquitetura do Projeto

### Frontend (React + TypeScript)
- **React 18** com TypeScript
- **React Router** para navegação
- **Axios** para comunicação com API
- Design responsivo e acessível
- Componentes modernos e reutilizáveis

### Backend (PHP + Composer)
- **PHP 8.1** com Composer
- API RESTful
- **PDO** para acesso ao banco
- CORS configurado para React
- Estrutura MVC organizada

### Banco de Dados
- **MySQL 8.0**
- Estrutura otimizada para usuários e cálculos
- phpMyAdmin para administração

---

## ✨ Funcionalidades

- 👤 **Sistema de Usuários** (em desenvolvimento)
- 🧮 **Calculadora Nutricional** 
  - Cálculo de IMC (Índice de Massa Corporal)
  - Cálculo de TMB (Taxa Metabólica Basal)
  - Cálculo de GET (Gasto Energético Total)
  - Distribuição de Macronutrientes
- � **Design Responsivo** para todos os dispositivos
- ♿ **Recursos de Acessibilidade**
  - Alto contraste
  - Ajuste de tamanho da fonte
  - Navegação por teclado
- 🎨 **Interface Moderna** com React

---

## �️ Tecnologias

### Frontend
- React 18 + TypeScript
- React Router DOM
- Axios
- CSS3 com Grid e Flexbox
- Design responsivo

### Backend
- PHP 8.1 + Composer
- API RESTful
- MySQL 8.0
- Apache
- Docker

### DevOps
- Docker Compose
- Multi-container setup
- Hot reload para desenvolvimento

---

## 📦 Estrutura do Projeto

```
.
├── frontend/                 # Aplicação React
│   ├── src/
│   │   ├── components/      # Componentes reutilizáveis
│   │   ├── pages/          # Páginas da aplicação
│   │   ├── services/       # Serviços para API
│   │   └── ...
│   ├── public/             # Assets estáticos
│   └── Dockerfile
│
├── backend/                 # API PHP
│   ├── src/
│   │   ├── Controllers/    # Controladores da API
│   │   ├── Models/         # Modelos e entidades
│   │   ├── Routes/         # Definição de rotas
│   │   └── Services/       # Lógica de negócio
│   ├── database/           # Scripts SQL
│   └── Dockerfile
│
├── docker-compose.yml      # Orquestração dos containers
└── README.md
```

---

## 🚀 Como Executar

### Pré-requisitos
- Docker e Docker Compose
- Git

### Executando com Docker (Recomendado)

1. **Clone o repositório**
```bash
git clone <repository-url>
cd site_delimeter
```

2. **Execute com Docker Compose**
```bash
docker-compose up --build
```

3. **Acesse as aplicações**
- Frontend React: http://localhost:3000
- Backend API: http://localhost:8000
- phpMyAdmin: http://localhost:8080
├── servicos.php
├── imc.php
├── macros.php
├── daltonismo.php
├── acesso_negado.php
├── assets
│   ├── css
│   │   ├── estilo.css
│   │   └── responsivo.css
│   ├── js
│   │   ├── scripts.js
│   │   └── validacao.js
│   └── img
│       ├── logo.png
│       ├── usuario_padrao.png
│       └── icones
│           ├── home.svg
│           ├── perfil.svg
│           ├── historico.svg
│           ├── servicos.svg
│           ├── imc.svg
│           └── macros.svg
├── includes
│   ├── cabecalho.php
│   ├── rodape.php
│   ├── conexao.php
│   ├── funcoes.php
│   └── autenticar.php
└── docs
    ├── termos_de_uso.md
    └── politica_de_privacidade.md
```

---

## 📚 Como Contribuir

1. Faça um fork deste repositório
2. Crie uma nova branch: `git checkout -b minha-contribuicao`
3. Faça suas alterações e commit: `git commit -m 'Minha contribuição'`
4. Envie para o seu fork: `git push origin minha-contribuicao`
5. Abra um Pull Request

---

## 📝 Licença

O Deliméter é um projeto open-source sob a licença MIT. Sinta-se à vontade para usar, modificar e distribuir!

---

## 👥 Contato

- **E-mail**: contato@delimeter.com
- **Telefone**: (11) 99999-9999
- **Endereço**: Av. da Saúde, 1234 - São Paulo, SP

---

## 🔗 Links Úteis

- [Documentação](docs/)
- [FAQ](faq.md)
- [Suporte](suporte.md)

---

## 🎉 Agradecimentos

Agradecemos a todos que contribuíram para o desenvolvimento do Deliméter, especialmente aos nossos beta testers e colaboradores.

---

## 📅 Roadmap

- [x] Lançamento da versão 1.0
- [ ] Implementar feedback dos usuários
- [ ] Adicionar novas funcionalidades
- [ ] Melhorar a performance e segurança

---

## 📊 Estatísticas do Projeto

- **Linhas de código**: ~10.000
- **Commits**: 150
- **Contribuidores**: 10
- **Issues abertas**: 5

---

## 🛠️ Manutenção

Este projeto é mantido por voluntários. Qualquer ajuda é bem-vinda! Para relatar problemas, sugerir melhorias ou contribuir com código, por favor, abra uma issue ou um pull request.

---

## 📧 Newsletter

Inscreva-se na nossa newsletter para receber novidades, dicas de saúde e atualizações do Deliméter diretamente no seu e-mail!

---

## 🔒 Política de Privacidade

Levamos a sua privacidade a sério. Leia nossa política de privacidade para entender como coletamos, usamos e protegemos suas informações.

---

## 📃 Termos de Uso

Ao usar o Deliméter, você concorda com nossos termos de uso. Por favor, leia atentamente antes de utilizar a plataforma.

---

## 📞 Suporte

Precisa de ajuda? Nossa equipe de suporte está disponível para ajudar com qualquer dúvida ou problema que você tenha.

---

## 🌟 Depoimentos

"Com o Deliméter, consegui melhorar minha alimentação e saúde de forma simples e prática!" - **Maria Silva**

"O painel do usuário é incrível, consigo acompanhar tudo que preciso em um só lugar." - **João Souza**

---

## 📈 Metas Futuras

- Expandir para outras línguas
- Adicionar integração com wearables (ex: Fitbit, Apple Watch)
- Implementar inteligência artificial para sugestões personalizadas

---

## 🤝 Parcerias

Estamos abertos a parcerias com profissionais de saúde, academias, empresas de alimentos saudáveis e outros interessados em promover bem-estar e saúde.

---

## 📅 Eventos

Participe dos nossos eventos online e presenciais sobre saúde, nutrição e bem-estar. Fique atento às nossas redes sociais para mais informações.

---

## 📚 Materiais Gratuitos

Disponibilizamos materiais gratuitos como e-books, guias e planilhas para ajudar você na sua jornada de saúde e bem-estar.

---

## 🎓 Cursos e Workshops

Oferecemos cursos e workshops sobre nutrição, culinária saudável, emagrecimento e outros temas relacionados à saúde.

---

## 🏆 Conquistas

- Mais de 10.000 usuários cadastrados
- Parcerias com 50+ profissionais de saúde
- Reconhecimento como um dos melhores aplicativos de saúde em 2023

---

## 📜 Certificados

Oferecemos certificados de conclusão para os usuários que completam nossos cursos e workshops.

---

## 🎁 Promoções

Fique atento às nossas promoções e descontos em serviços e produtos relacionados à saúde e bem-estar.

---

## 📦 Planos Futuros

- Lançamento de um aplicativo móvel
- Expansão dos serviços oferecidos
- Melhoria contínua da plataforma baseada no feedback dos usuários

---

## 🙏 Agradecimentos Especiais

Agradecemos a todos os nossos usuários, parceiros e colaboradores pelo apoio e confiança no Deliméter. Juntos, estamos fazendo a diferença na saúde e bem-estar de muitas pessoas.

---

## 🔗 Links Importantes

- [Política de Privacidade](docs/politica_privacidade.md)
- [Termos de Uso](docs/termos_uso.md)
- [Suporte](suporte.md)
- [FAQ](faq.md)
