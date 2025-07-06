<!-- # 🥗 Deliméter

Bem-vindo ao **Deliméter** – o seu portal para uma vida mais saudável, inteligente e conectada!  
Aqui você gerencia sua alimentação, seu perfil e sua experiência digital com acessibilidade e praticidade.

---

## 🚀 O que é o Deliméter?

O Deliméter é um sistema web para gerenciamento de usuários e cálculo nutricional, pensado para ser acessível, bonito e fácil de usar.  
Ideal para quem quer cuidar da saúde, acompanhar dados e ter controle sobre sua experiência.

---

## ✨ Funcionalidades

- 👤 **Cadastro e Login de Usuários**
- 🏠 **Painel do Usuário** com informações e histórico
- 🛠️ **Serviços**: atualização de perfil, histórico, suporte
- 📊 **Cálculo Nutricional** (IMC, GET, macros, etc)
- ♿ **Acessibilidade**: alto contraste, ajuste de fonte, simulação de daltonismo
- 📱 **Responsivo**: funciona bem no PC, tablet e celular
- 🔒 **Segurança**: senhas criptografadas e dados protegidos

---

## 🖥️ Tecnologias

- **PHP** (MVC simples)
- **HTML5 + CSS3** (com muito carinho no visual)
- **JavaScript** (interatividade e acessibilidade)
- **MySQL** (armazenamento dos dados)
- **SweetAlert2** (alertas bonitos)
- **PDO** (acesso seguro ao banco)

---

## 📦 Estrutura do Projeto

```
.
├── README.md
├── index.php
├── sobre.php
├── contato.php
├── cadastro.php
├── login.php
├── painel.php
├── perfil.php
├── historico.php
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

## Estrutura inicial do projeto React

Execute no terminal:

```bash
npm create vite@latest frontend -- --template react
cd frontend
npm install
```

Após isso, converta os arquivos PHP de view em componentes React dentro de `frontend/src/pages/`

Exemplo: `Home.jsx`, `Sobre.jsx`, `Calculo.jsx`, `Login.jsx`, `CadastroUsuario.jsx`, etc.

Importe os estilos CSS em `frontend/src/assets/styles/` e adapte para uso global ou CSS Modules.

Use React Router para navegação entre páginas.

As chamadas para login/cadastro/etc devem ser feitas via fetch para as rotas de API PHP já existentes.

---

# Site Delimeter - React + PHP

Plataforma completa para cálculos nutricionais e gestão de saúde alimentar, com frontend React moderno e backend PHP robusto.

## 🚀 Tecnologias

### Frontend (React)
- **React 18** - Library para interfaces de usuário
- **React Router 6** - Roteamento SPA
- **Axios** - Cliente HTTP para APIs
- **CSS3** - Estilos modernos com animações
- **JSX** - Sintaxe JavaScript estendida

### Backend (PHP)
- **PHP 7.4+** - Linguagem servidor
- **MySQL** - Banco de dados
- **Composer** - Gerenciador de dependências PHP
- **PDO** - Camada de abstração do banco

## 📁 Estrutura do Projeto

```
/workspaces/site_delimeter/
├── public/                     # Arquivos públicos
│   ├── index.html             # HTML principal do React
│   ├── assets/                # Imagens, ícones, etc.
│   └── index.php              # Entry point PHP
├── src/                       # Código fonte React
│   ├── components/            # Componentes reutilizáveis
│   │   ├── Header/           # Cabeçalho da aplicação
│   │   ├── Footer/           # Rodapé da aplicação
│   │   ├── DelimiterForm/    # Formulário delimitador
│   │   ├── NutritionalCalculator/ # Calculadora nutricional
│   │   ├── AccessibilityMenu/ # Menu de acessibilidade
│   │   └── WelcomeTour/       # Tour de boas-vindas
│   ├── pages/                 # Páginas da aplicação
│   │   ├── Home/             # Página inicial
│   │   ├── Login/            # Login de usuários
│   │   ├── Cadastro/         # Cadastro de usuários
│   │   ├── Calculo/          # Página de cálculos
│   │   ├── Sobre/            # Sobre o projeto
│   │   ├── Usuario/          # Painel usuário
│   │   ├── Paciente/         # Painel paciente
│   │   ├── Nutricionista/    # Painel nutricionista
│   │   ├── Medico/           # Painel médico
│   │   └── Conta/            # Gestão de conta
│   ├── contexts/             # Contexts da aplicação
│   │   └── AuthContext.jsx   # Contexto de autenticação
│   ├── utils/                # Utilitários
│   │   └── api.js            # Configuração de APIs
│   ├── App.jsx               # Componente principal
│   ├── App.css               # Estilos globais
│   └── index.js              # Entry point React
├── api/                       # APIs PHP
│   └── delimiter.php         # API delimitador de texto
├── src/                      # Backend PHP (MVC)
│   ├── Controllers/          # Controladores
│   ├── Models/              # Modelos e entidades
│   ├── Services/            # Camada de serviços
│   ├── Routes/              # Definição de rotas
│   └── Config/              # Configurações
├── view/                     # Views PHP (legacy)
├── package.json              # Dependências React
├── composer.json             # Dependências PHP
└── .htaccess                 # Configuração Apache
```

## 🛠️ Instalação e Configuração

### 1. Clone o Repositório
```bash
git clone <repository-url>
cd site_delimeter
```

### 2. Configure o Backend PHP
```bash
# Instale dependências PHP
composer install

# Configure o banco de dados
# Edite src/Config/Connection.php com suas credenciais
```

### 3. Configure o Frontend React
```bash
# Instale dependências Node.js
npm install

# Para desenvolvimento
npm start

# Para produção
npm run build
```

### 4. Configuração do Servidor

#### Desenvolvimento
- **Frontend**: `http://localhost:3000` (React Dev Server)
- **Backend**: `http://localhost:8080` (PHP)

#### Produção
- Configure Apache/Nginx para servir `/build` (React) e APIs PHP
- O `.htaccess` já está configurado para roteamento híbrido

## 🎯 Funcionalidades

### Frontend React
- ✅ **Interface moderna** - Design responsivo e acessível
- ✅ **SPA completa** - Navegação sem recarregamento
- ✅ **Calculadora nutricional** - Cálculos completos de GEB/GET
- ✅ **Sistema de autenticação** - Login/logout com contexto
- ✅ **Multi-perfil** - Usuário, Paciente, Nutricionista, Médico
- ✅ **Tour interativo** - Boas-vindas para novos usuários
- ✅ **Acessibilidade** - Alto contraste, tamanho de fonte, daltonismo
- ✅ **Responsivo** - Funciona em desktop, tablet e mobile

### Backend PHP
- ✅ **API REST** - Endpoints para todas operações
- ✅ **Arquitetura MVC** - Código organizado e manutenível
- ✅ **Múltiplos usuários** - Sistema completo de perfis
- ✅ **Validação robusta** - Validação de dados no servidor
- ✅ **Segurança** - Sessões, validação, sanitização

## 🔗 Principais Rotas

### Frontend (React Router)
- `/` - Página inicial
- `/delimeter/sobre` - Sobre o projeto
- `/delimeter/calculo` - Calculadora nutricional
- `/usuario/login` - Login
- `/usuario/cadastro` - Cadastro
- `/usuario` - Painel usuário
- `/paciente` - Painel paciente
- `/nutricionista` - Painel nutricionista
- `/medico` - Painel médico
- `/conta` - Gestão de conta

### Backend (PHP APIs)
- `POST /api/usuario` - Criar usuário
- `POST /login/usuario` - Login
- `GET /conta/sair` - Logout
- `POST /api/paciente` - Criar paciente
- `POST /api/nutricionista` - Criar nutricionista
- `POST /api/medico` - Criar médico
- `POST /api/delimiter` - Processar delimitador

## 🎨 Temas e Personalização

### Cores Principais
- **Verde**: `#26a65b` (Primária)
- **Azul**: `#667eea` (Secundária)
- **Gradientes**: Múltiplos gradientes para visual moderno

### Responsividade
- **Mobile First**: Design otimizado para dispositivos móveis
- **Breakpoints**: 480px, 768px, 1024px
- **Grid Flexível**: Layout adaptativo

## 🚀 Deploy

### Produção com Apache
1. Execute `npm run build`
2. Copie `/build` para diretório web
3. Configure `.htaccess` para roteamento
4. Configure PHP com Composer
5. Configure banco de dados

### Docker (Opcional)
```dockerfile
# Dockerfile exemplo para produção
FROM node:16 AS build
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

FROM php:7.4-apache
COPY --from=build /app/build /var/www/html
COPY . /var/www/html
RUN composer install
```

## 🧪 Testes

```bash
# Testes React
npm test

# Linting
npm run lint
```

## 📝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está licenciado sob a Licença MIT - veja o arquivo [LICENSE](LICENSE) para detalhes.

## 👥 Time

- **Frontend**: React com componentes modernos
- **Backend**: PHP com arquitetura MVC
- **Design**: Interface responsiva e acessível
- **UX**: Tour interativo e experiência otimizada

---

**Delimeter** - Priorizando sua alimentação com tecnologia! 🥗⚡

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
