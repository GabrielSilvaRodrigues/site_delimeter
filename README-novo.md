# Sistema Delimeter - Gestão Nutricional e Saúde

Sistema completo para gestão de saúde nutricional, desenvolvido em PHP com arquitetura MVC, permitindo o acompanhamento de pacientes, médicos e nutricionistas.

## 🏗️ Arquitetura

### Estrutura de Banco de Dados

O sistema utiliza um banco de dados MySQL com as seguintes entidades principais:

#### Usuários e Perfis
- **usuario**: Dados básicos dos usuários (nome, email, senha, status)
- **endereco_usuario** e **telefone_usuario**: Informações de contato
- **medico**, **nutricionista**, **paciente**: Perfis específicos por tipo de usuário

#### Dados Clínicos
- **dados_antropometricos**: Medidas corporais (altura em metros, peso em kg, IMC) com histórico
- **dieta**: Planos alimentares criados por nutricionistas
- **receita**: Prescrições médicas
- **consulta**: Agendamentos e consultas realizadas

#### Alimentação
- **alimento**: Catálogo de alimentos com informações nutricionais
- **diario_de_alimentos**: Registro diário da alimentação do paciente
- **relacao_diario_alimento**: Alimentos consumidos por dia
- **relacao_alimento_dieta**: Alimentos incluídos em dietas

#### Validações e Relacionamentos
- **valida_dados_antropometricos**: Validação médica das medidas
- **valida_dieta**: Aprovação médica das dietas
- **valida_receita**: Validação médica das receitas
- **valida_diario**: Revisão nutricional dos diários

## 🚀 Funcionalidades

### Para Pacientes
- ✅ **Dados Antropométricos**: Registro e acompanhamento de medidas corporais
- ✅ **Diário de Alimentos**: Registro diário da alimentação
- ✅ **Cálculo de IMC**: Automático com classificação
- 🏗️ **Visualização de Dietas**: Planos alimentares prescritos
- 🏗️ **Histórico de Consultas**: Acompanhamento de consultas

### Para Nutricionistas
- 🏗️ **Gestão de Alimentos**: Cadastro e edição do catálogo de alimentos
- 🏗️ **Criação de Dietas**: Desenvolvimento de planos alimentares
- 🏗️ **Acompanhamento de Pacientes**: Monitoramento dos diários alimentares
- 🏗️ **Validação de Registros**: Aprovação dos registros dos pacientes

### Para Médicos
- 🏗️ **Prescrições**: Criação de receitas médicas
- 🏗️ **Validação de Dados**: Aprovação de medidas antropométricas
- 🏗️ **Aprovação de Dietas**: Validação de planos alimentares
- 🏗️ **Consultas**: Agendamento e gerenciamento de consultas

## 🛠️ Tecnologias

- **Backend**: PHP 8+ com arquitetura MVC
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Banco de Dados**: MySQL 8+
- **Containerização**: Docker e Docker Compose
- **Servidor Web**: Apache
- **Dependências**: Composer para autoload

## 📁 Estrutura do Projeto

```
├── src/
│   ├── Config/           # Configurações de banco
│   ├── Controllers/      # Controladores MVC
│   ├── Models/
│   │   ├── Entity/       # Entidades do domínio
│   │   └── Repository/   # Acesso a dados
│   ├── Services/         # Lógica de negócio
│   └── Routes/           # Definição de rotas
├── view/                 # Templates e views
│   ├── includes/         # Header e footer
│   ├── paciente/         # Views do paciente
│   ├── nutricionista/    # Views do nutricionista
│   └── medico/           # Views do médico
├── public/               # Assets públicos
│   ├── assets/           # CSS, JS, imagens
│   └── index.php         # Ponto de entrada
├── database/             # Scripts de banco
└── docker/               # Configurações Docker
```

## 🚀 Como Executar

### Pré-requisitos
- Docker e Docker Compose instalados

### Passos

1. **Clone o repositório**
   ```bash
   git clone <repository-url>
   cd site_delimeter
   ```

2. **Inicie os containers**
   ```bash
   docker-compose up -d
   ```

3. **Acesse o sistema**
   - Sistema: http://localhost:8080
   - phpMyAdmin: http://localhost:8081

### Estrutura de Containers

- **app**: Aplicação PHP com Apache
- **db**: MySQL 8.0 com dados persistentes
- **phpmyadmin**: Interface web para o banco

## 📊 API Endpoints

### Dados Antropométricos
- `POST /api/dados-antropometricos/criar` - Criar nova medida
- `GET /api/dados-antropometricos/buscar-por-paciente` - Buscar por paciente
- `GET /api/dados-antropometricos/calcular-imc` - Calcular IMC

### Diário de Alimentos
- `POST /api/diario-alimentos/criar` - Criar registro
- `GET /api/diario-alimentos/buscar-por-paciente` - Buscar por paciente
- `POST /api/diario-alimentos/associar-alimento` - Associar alimento

### Alimentos
- `GET /api/alimentos/listar` - Listar todos
- `GET /api/alimentos/buscar-por-descricao` - Buscar por nome
- `POST /api/alimentos/criar` - Cadastrar alimento

### Dietas
- `POST /api/dietas/criar` - Criar dieta
- `GET /api/dietas/buscar-por-paciente` - Buscar por paciente
- `POST /api/dietas/associar-paciente` - Associar paciente

## 🎨 Interface

O sistema possui uma interface moderna e responsiva com:

- **Design System**: Cores consistentes por tipo de usuário
- **Responsividade**: Funciona em desktop, tablet e mobile
- **UX Intuitiva**: Navegação clara e formulários validados
- **Feedback Visual**: Alertas e confirmações para ações

### Cores por Perfil
- **Paciente**: Laranja (#ff9800)
- **Nutricionista**: Verde (#4caf50)
- **Médico**: Azul (#2196f3)

## 🔒 Segurança

- Senhas criptografadas com `password_hash()`
- Validação de dados de entrada
- Proteção contra SQL Injection via prepared statements
- Controle de sessões para autenticação

## 📈 Próximas Funcionalidades

- [ ] Dashboard com gráficos e estatísticas
- [ ] Sistema de notificações
- [ ] Relatórios em PDF
- [ ] API REST completa
- [ ] Aplicativo móvel
- [ ] Integração com dispositivos de medição

## 🤝 Contribuição

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para detalhes.

## 👥 Desenvolvedores

Sistema desenvolvido para gestão de saúde nutricional, focando na integração entre pacientes, nutricionistas e médicos.

---

**Status do Projeto**: 🚧 Em desenvolvimento ativo

**Versão Atual**: 2.0.0 - Sistema completo com banco de dados expandido

**Última Atualização**: Janeiro 2025
