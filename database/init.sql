CREATE DATABASE IF NOT EXISTS delimeter;
USE delimeter;

CREATE TABLE usuario (
    id_usuario BIGINT AUTO_INCREMENT PRIMARY KEY,
    nome_usuario VARCHAR(255) NOT NULL,
    email_usuario VARCHAR(255) UNIQUE,
    senha_usuario VARCHAR(255) NOT NULL,
    status_usuario TINYINT DEFAULT 1 -- 1=ativo, 0=inativo
);

CREATE TABLE endereco_usuario (
    id_endereco BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_usuario BIGINT NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE telefone_usuario (
    id_telefone BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_usuario BIGINT NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE medico (
    id_medico BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_usuario BIGINT NOT NULL UNIQUE,
    crm_medico VARCHAR(50),
    cpf VARCHAR(20) UNIQUE,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE nutricionista (
    id_nutricionista BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_usuario BIGINT NOT NULL UNIQUE,
    crm_nutricionista VARCHAR(50),
    cpf VARCHAR(20) UNIQUE,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE paciente (
    id_paciente BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_usuario BIGINT NOT NULL UNIQUE,
    cpf VARCHAR(20) UNIQUE,
    nis VARCHAR(20) UNIQUE,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE dados_antropometricos (
    id_medida BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_paciente BIGINT NOT NULL,
    sexo_paciente TINYINT,
    altura_paciente FLOAT, -- altura em metros (ex: 1.75)
    peso_paciente FLOAT,
    status_paciente TINYINT,
    data_medida DATE,
    FOREIGN KEY (id_paciente) REFERENCES paciente(id_paciente)
);

CREATE TABLE dieta (
    id_dieta BIGINT AUTO_INCREMENT PRIMARY KEY,
    data_inicio_dieta DATE,
    data_termino_dieta DATE,
    descricao_dieta TEXT
);

CREATE TABLE alimento (
    id_alimento BIGINT AUTO_INCREMENT PRIMARY KEY,
    descricao_alimento VARCHAR(255),
    dados_nutricionais TEXT
);

CREATE TABLE diario_de_alimentos (
    id_diario BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_paciente BIGINT,
    data_diario DATE,
    descricao_diario TEXT,
    FOREIGN KEY (id_paciente) REFERENCES paciente(id_paciente)
);

CREATE TABLE receita (
    id_receita BIGINT AUTO_INCREMENT PRIMARY KEY,
    data_inicio_receita DATE,
    data_termino_receita DATE,
    descricao_receita TEXT
);

CREATE TABLE consulta (
    id_consulta BIGINT AUTO_INCREMENT PRIMARY KEY,
    data_consulta DATETIME NOT NULL
);

CREATE TABLE agenda (
    id_agenda BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_consulta BIGINT NOT NULL,
    id_paciente BIGINT NOT NULL,
    id_medico BIGINT NULL,
    id_nutricionista BIGINT NULL,
    tipo_consulta ENUM('medico', 'nutricionista') NOT NULL,
    status_agenda ENUM('agendado', 'confirmado', 'realizado', 'cancelado') DEFAULT 'agendado',
    observacoes TEXT,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_consulta) REFERENCES consulta(id_consulta) ON DELETE CASCADE,
    FOREIGN KEY (id_paciente) REFERENCES paciente(id_paciente) ON DELETE CASCADE,
    FOREIGN KEY (id_medico) REFERENCES medico(id_medico) ON DELETE SET NULL,
    FOREIGN KEY (id_nutricionista) REFERENCES nutricionista(id_nutricionista) ON DELETE SET NULL,
    INDEX idx_paciente_data (id_paciente, data_criacao),
    INDEX idx_medico_data (id_medico, data_criacao),
    INDEX idx_nutricionista_data (id_nutricionista, data_criacao),
    INDEX idx_status (status_agenda)
);

CREATE TABLE historico_clinico (
    id_historico_clinico BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_paciente BIGINT,
    id_receita BIGINT,
    id_dieta BIGINT,
    FOREIGN KEY (id_paciente) REFERENCES paciente(id_paciente),
    FOREIGN KEY (id_receita) REFERENCES receita(id_receita),
    FOREIGN KEY (id_dieta) REFERENCES dieta(id_dieta)
);

CREATE TABLE relacao_diario_alimento (
    id_alimento BIGINT,
    id_diario BIGINT,
    PRIMARY KEY (id_alimento, id_diario),
    FOREIGN KEY (id_alimento) REFERENCES alimento(id_alimento),
    FOREIGN KEY (id_diario) REFERENCES diario_de_alimentos(id_diario)
);

CREATE TABLE relacao_alimento_dieta (
    id_alimento BIGINT,
    id_dieta BIGINT,
    PRIMARY KEY (id_alimento, id_dieta),
    FOREIGN KEY (id_alimento) REFERENCES alimento(id_alimento),
    FOREIGN KEY (id_dieta) REFERENCES dieta(id_dieta)
);

CREATE TABLE relacao_nutricionista_dieta (
    id_dieta BIGINT,
    id_nutricionista BIGINT,
    PRIMARY KEY (id_dieta, id_nutricionista),
    FOREIGN KEY (id_dieta) REFERENCES dieta(id_dieta),
    FOREIGN KEY (id_nutricionista) REFERENCES nutricionista(id_nutricionista)
);

CREATE TABLE valida_medidas_nutricionista (
    id_medida BIGINT,
    id_nutricionista BIGINT,
    PRIMARY KEY (id_medida, id_nutricionista),
    FOREIGN KEY (id_medida) REFERENCES dados_antropometricos(id_medida),
    FOREIGN KEY (id_nutricionista) REFERENCES nutricionista(id_nutricionista)
);

CREATE TABLE relacao_paciente_receita (
    id_paciente BIGINT,
    id_receita BIGINT,
    PRIMARY KEY (id_paciente, id_receita),
    FOREIGN KEY (id_paciente) REFERENCES paciente(id_paciente),
    FOREIGN KEY (id_receita) REFERENCES receita(id_receita)
);

CREATE TABLE relacao_nutricionista_receita (
    id_receita BIGINT,
    id_nutricionista BIGINT,
    PRIMARY KEY (id_receita, id_nutricionista),
    FOREIGN KEY (id_receita) REFERENCES receita(id_receita),
    FOREIGN KEY (id_nutricionista) REFERENCES nutricionista(id_nutricionista)
);

CREATE TABLE relacao_paciente_dieta (
    id_dieta BIGINT,
    id_paciente BIGINT,
    PRIMARY KEY (id_dieta, id_paciente),
    FOREIGN KEY (id_dieta) REFERENCES dieta(id_dieta),
    FOREIGN KEY (id_paciente) REFERENCES paciente(id_paciente)
);

CREATE TABLE valida_dieta (
    id_medico BIGINT,
    id_dieta BIGINT,
    PRIMARY KEY (id_medico, id_dieta),
    FOREIGN KEY (id_medico) REFERENCES medico(id_medico),
    FOREIGN KEY (id_dieta) REFERENCES dieta(id_dieta)
);

CREATE TABLE valida_receita (
    id_receita BIGINT,
    id_medico BIGINT,
    PRIMARY KEY (id_receita, id_medico),
    FOREIGN KEY (id_receita) REFERENCES receita(id_receita),
    FOREIGN KEY (id_medico) REFERENCES medico(id_medico)
);

CREATE TABLE valida_dados_antropometricos (
    id_medida BIGINT,
    id_medico BIGINT,
    PRIMARY KEY (id_medida, id_medico),
    FOREIGN KEY (id_medida) REFERENCES dados_antropometricos(id_medida),
    FOREIGN KEY (id_medico) REFERENCES medico(id_medico)
);

CREATE TABLE valida_diario (
    id_nutricionista BIGINT,
    id_diario BIGINT,
    PRIMARY KEY (id_nutricionista, id_diario),
    FOREIGN KEY (id_nutricionista) REFERENCES nutricionista(id_nutricionista),
    FOREIGN KEY (id_diario) REFERENCES diario_de_alimentos(id_diario)
);

CREATE TABLE relacao_paciente_consulta (
    id_consulta BIGINT,
    id_paciente BIGINT,
    PRIMARY KEY (id_consulta, id_paciente),
    FOREIGN KEY (id_consulta) REFERENCES consulta(id_consulta),
    FOREIGN KEY (id_paciente) REFERENCES paciente(id_paciente)
);

CREATE TABLE relacao_consulta_nutricionista (
    id_consulta BIGINT,
    id_nutricionista BIGINT,
    PRIMARY KEY (id_consulta, id_nutricionista),
    FOREIGN KEY (id_consulta) REFERENCES consulta(id_consulta),
    FOREIGN KEY (id_nutricionista) REFERENCES nutricionista(id_nutricionista)
);

CREATE TABLE relacao_consulta_medico (
    id_consulta BIGINT,
    id_medico BIGINT,
    PRIMARY KEY (id_consulta, id_medico),
    FOREIGN KEY (id_consulta) REFERENCES consulta(id_consulta),
    FOREIGN KEY (id_medico) REFERENCES medico(id_medico)
);

-- Inserir alguns dados de exemplo para alimentos
INSERT INTO alimento (descricao_alimento, dados_nutricionais) VALUES
('Arroz branco cozido', '{"calorias": 130, "carboidratos": 28, "proteinas": 2.7, "gorduras": 0.3}'),
('Feijão preto cozido', '{"calorias": 132, "carboidratos": 24, "proteinas": 8.9, "gorduras": 0.5}'),
('Frango grelhado (peito)', '{"calorias": 165, "carboidratos": 0, "proteinas": 31, "gorduras": 3.6}'),
('Brócolis cozido', '{"calorias": 25, "carboidratos": 5, "proteinas": 3, "gorduras": 0.3}'),
('Batata doce cozida', '{"calorias": 86, "carboidratos": 20, "proteinas": 1.6, "gorduras": 0.1}'),
('Banana', '{"calorias": 89, "carboidratos": 23, "proteinas": 1.1, "gorduras": 0.3}'),
('Aveia', '{"calorias": 68, "carboidratos": 12, "proteinas": 2.4, "gorduras": 1.4}'),
('Leite desnatado', '{"calorias": 34, "carboidratos": 5, "proteinas": 3.4, "gorduras": 0.1}'),
('Ovos cozidos', '{"calorias": 155, "carboidratos": 1.1, "proteinas": 13, "gorduras": 11}'),
('Salmão grelhado', '{"calorias": 206, "carboidratos": 0, "proteinas": 22, "gorduras": 12}');

-- Inserir usuários de exemplo com senhas criptografadas
INSERT INTO usuario (nome_usuario, email_usuario, senha_usuario, status_usuario) VALUES
('Gabriel Santos', 'gabriel@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1), -- senha: password
('Maria Silva', 'maria@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1), -- senha: password
('Dr. João Cardiologista', 'joao.medico@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1), -- senha: password
('Dra. Ana Nutricionista', 'ana.nutri@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1), -- senha: password
('Carlos Paciente', 'carlos@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1), -- senha: password
('Julia Lima', 'julia@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1), -- senha: password
('Dr. Pedro Endocrinologista', 'pedro.medico@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1), -- senha: password
('Nutri. Sandra Oliveira', 'sandra.nutri@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1); -- senha: password

-- Inserir dados de pacientes
INSERT INTO paciente (id_usuario, cpf, nis) VALUES
(1, '123.456.789-01', '123.45678.90-1'), -- Gabriel
(2, '987.654.321-02', '987.65432.10-2'), -- Maria
(5, '555.666.777-05', '555.66677.70-5'), -- Carlos
(6, '444.333.222-06', '444.33322.20-6'); -- Julia

-- Inserir dados de médicos
INSERT INTO medico (id_usuario, crm_medico, cpf) VALUES
(3, '123456/SP', '111.222.333-03'), -- Dr. João
(7, '789012/RJ', '777.888.999-07'); -- Dr. Pedro

-- Inserir dados de nutricionistas
INSERT INTO nutricionista (id_usuario, crm_nutricionista, cpf) VALUES
(4, '12345/SP', '222.333.444-04'), -- Ana
(8, '67890/RJ', '888.999.000-08'); -- Sandra

-- Inserir endereços de exemplo
INSERT INTO endereco_usuario (id_usuario, endereco) VALUES
(1, 'Rua das Flores, 123 - São Paulo, SP'),
(2, 'Av. Principal, 456 - Rio de Janeiro, RJ'),
(3, 'Rua dos Médicos, 789 - São Paulo, SP'),
(4, 'Av. da Saúde, 321 - São Paulo, SP'),
(5, 'Rua do Centro, 654 - Belo Horizonte, MG'),
(6, 'Av. das Palmeiras, 987 - Salvador, BA'),
(7, 'Rua Especialista, 147 - Rio de Janeiro, RJ'),
(8, 'Av. Nutrição, 258 - Rio de Janeiro, RJ');

-- Inserir telefones de exemplo
INSERT INTO telefone_usuario (id_usuario, telefone) VALUES
(1, '(11) 99999-1111'),
(2, '(21) 88888-2222'),
(3, '(11) 77777-3333'),
(4, '(11) 66666-4444'),
(5, '(31) 55555-5555'),
(6, '(71) 44444-6666'),
(7, '(21) 33333-7777'),
(8, '(21) 22222-8888');

-- Inserir dados antropométricos de exemplo
INSERT INTO dados_antropometricos (id_paciente, sexo_paciente, altura_paciente, peso_paciente, status_paciente, data_medida) VALUES
(1, 1, 1.75, 70.5, 1, '2024-12-01'), -- Gabriel - masculino
(1, 1, 1.75, 69.8, 1, '2024-12-15'), -- Gabriel - medida mais recente
(2, 0, 1.65, 60.2, 1, '2024-12-01'), -- Maria - feminino
(2, 0, 1.65, 59.5, 1, '2024-12-20'), -- Maria - medida mais recente
(3, 1, 1.80, 85.0, 1, '2024-11-25'), -- Carlos
(4, 0, 1.68, 65.3, 1, '2024-12-10'); -- Julia

-- Inserir dietas de exemplo
INSERT INTO dieta (data_inicio_dieta, data_termino_dieta, descricao_dieta) VALUES
('2024-12-01', '2025-01-01', 'Dieta para redução de peso - 1800 kcal/dia com foco em proteínas magras e vegetais'),
('2024-12-10', '2025-02-10', 'Dieta para ganho de massa muscular - 2200 kcal/dia rica em proteínas'),
('2024-11-20', '2024-12-20', 'Dieta mediterrânea para controle do colesterol');

-- Inserir receitas de exemplo
INSERT INTO receita (data_inicio_receita, data_termino_receita, descricao_receita) VALUES
('2024-12-01', '2024-12-31', 'Suplemento de Vitamina D3 - 2000 UI ao dia, em jejum'),
('2024-12-05', '2025-01-05', 'Omega 3 - 1g duas vezes ao dia após refeições'),
('2024-11-15', '2024-12-15', 'Probióticos - 1 cápsula ao dia, preferencialmente à noite');

-- Inserir consultas de exemplo
INSERT INTO consulta (data_consulta) VALUES
('2024-12-15 09:00:00'),
('2024-12-20 14:30:00'),
('2024-12-22 10:15:00'),
('2025-01-05 11:00:00'),
('2025-01-10 15:30:00');

-- Inserir registros de diário de alimentos
INSERT INTO diario_de_alimentos (id_paciente, data_diario, descricao_diario) VALUES
(1, '2024-12-01', 'Café da manhã: aveia com banana e leite. Almoço: arroz, feijão, frango grelhado e salada. Jantar: sopa de legumes.'),
(1, '2024-12-02', 'Dia com alimentação balanceada, incluí mais vegetais nas refeições principais.'),
(2, '2024-12-01', 'Tentei reduzir carboidratos refinados. Substituí pão branco por integral.'),
(3, '2024-11-25', 'Aumentei a ingestão de proteínas conforme orientação nutricional.'),
(4, '2024-12-10', 'Dia dedicado a hidratação e alimentos ricos em fibras.');

-- Inserir agenda/agendamentos
INSERT INTO agenda (id_consulta, id_paciente, id_medico, id_nutricionista, tipo_consulta, status_agenda, observacoes) VALUES
(1, 1, 1, NULL, 'medico', 'confirmado', 'Consulta de rotina para acompanhamento'),
(2, 2, NULL, 1, 'nutricionista', 'agendado', 'Primeira consulta nutricional'),
(3, 3, 2, NULL, 'medico', 'realizado', 'Consulta para avaliação endocrinológica'),
(4, 1, NULL, 2, 'nutricionista', 'agendado', 'Reavaliação do plano alimentar'),
(5, 4, 1, NULL, 'medico', 'agendado', 'Consulta preventiva');

-- Inserir relacionamentos paciente-receita
INSERT INTO relacao_paciente_receita (id_paciente, id_receita) VALUES
(1, 1), -- Gabriel com Vitamina D3
(2, 2), -- Maria com Omega 3
(3, 3); -- Carlos com Probióticos

-- Inserir relacionamentos paciente-dieta
INSERT INTO relacao_paciente_dieta (id_dieta, id_paciente) VALUES
(1, 1), -- Dieta de redução para Gabriel
(2, 3), -- Dieta de ganho para Carlos
(3, 2); -- Dieta mediterrânea para Maria

-- Inserir relacionamentos nutricionista-dieta
INSERT INTO relacao_nutricionista_dieta (id_dieta, id_nutricionista) VALUES
(1, 1), -- Ana criou dieta de redução
(2, 1), -- Ana criou dieta de ganho
(3, 2); -- Sandra criou dieta mediterrânea

-- Inserir relacionamentos nutricionista-receita
INSERT INTO relacao_nutricionista_receita (id_receita, id_nutricionista) VALUES
(1, 1), -- Ana prescreveu Vitamina D3
(2, 2), -- Sandra prescreveu Omega 3
(3, 1); -- Ana prescreveu Probióticos

-- Inserir relacionamentos alimento-dieta
INSERT INTO relacao_alimento_dieta (id_alimento, id_dieta) VALUES
(1, 1), -- Arroz na dieta de redução
(2, 1), -- Feijão na dieta de redução
(3, 1), -- Frango na dieta de redução
(4, 1), -- Brócolis na dieta de redução
(3, 2), -- Frango na dieta de ganho
(9, 2), -- Ovos na dieta de ganho
(10, 2), -- Salmão na dieta de ganho
(10, 3), -- Salmão na dieta mediterrânea
(6, 3), -- Banana na dieta mediterrânea
(7, 3); -- Aveia na dieta mediterrânea

-- Inserir relacionamentos diário-alimento
INSERT INTO relacao_diario_alimento (id_alimento, id_diario) VALUES
(1, 1), -- Arroz no diário do Gabriel
(2, 1), -- Feijão no diário do Gabriel
(3, 1), -- Frango no diário do Gabriel
(6, 1), -- Banana no diário do Gabriel
(7, 1), -- Aveia no diário do Gabriel
(1, 2), -- Arroz no segundo dia do Gabriel
(4, 2), -- Brócolis no segundo dia do Gabriel
(7, 3), -- Aveia no diário da Maria
(8, 3), -- Leite no diário da Maria
(3, 4), -- Frango no diário do Carlos
(9, 4), -- Ovos no diário do Carlos
(4, 5), -- Brócolis no diário da Julia
(5, 5); -- Batata doce no diário da Julia

-- Inserir relacionamentos paciente-consulta
INSERT INTO relacao_paciente_consulta (id_consulta, id_paciente) VALUES
(1, 1), -- Gabriel na primeira consulta
(2, 2), -- Maria na segunda consulta
(3, 3), -- Carlos na terceira consulta
(4, 1), -- Gabriel na quarta consulta
(5, 4); -- Julia na quinta consulta

-- Inserir relacionamentos consulta-medico
INSERT INTO relacao_consulta_medico (id_consulta, id_medico) VALUES
(1, 1), -- Dr. João na primeira consulta
(3, 2), -- Dr. Pedro na terceira consulta
(5, 1); -- Dr. João na quinta consulta

-- Inserir relacionamentos consulta-nutricionista
INSERT INTO relacao_consulta_nutricionista (id_consulta, id_nutricionista) VALUES
(2, 1), -- Ana na segunda consulta
(4, 2); -- Sandra na quarta consulta

-- Inserir validações médicas
INSERT INTO valida_dados_antropometricos (id_medida, id_medico) VALUES
(1, 1), -- Dr. João validou medidas do Gabriel
(3, 1), -- Dr. João validou medidas da Maria
(5, 2); -- Dr. Pedro validou medidas do Carlos

INSERT INTO valida_dieta (id_medico, id_dieta) VALUES
(1, 1), -- Dr. João validou dieta de redução
(2, 2), -- Dr. Pedro validou dieta de ganho
(1, 3); -- Dr. João validou dieta mediterrânea

INSERT INTO valida_receita (id_receita, id_medico) VALUES
(1, 1), -- Dr. João validou receita de Vitamina D3
(2, 2), -- Dr. Pedro validou receita de Omega 3
(3, 1); -- Dr. João validou receita de Probióticos

-- Inserir validações nutricionais
INSERT INTO valida_medidas_nutricionista (id_medida, id_nutricionista) VALUES
(2, 1), -- Ana validou segunda medida do Gabriel
(4, 1), -- Ana validou segunda medida da Maria
(6, 2); -- Sandra validou medidas da Julia

INSERT INTO valida_diario (id_nutricionista, id_diario) VALUES
(1, 1), -- Ana validou primeiro diário do Gabriel
(1, 2), -- Ana validou segundo diário do Gabriel
(2, 3), -- Sandra validou diário da Maria
(1, 4), -- Ana validou diário do Carlos
(2, 5); -- Sandra validou diário da Julia

-- Inserir histórico clínico
INSERT INTO historico_clinico (id_paciente, id_receita, id_dieta) VALUES
(1, 1, 1), -- Gabriel: Vitamina D3 + dieta de redução
(2, 2, 3), -- Maria: Omega 3 + dieta mediterrânea
(3, 3, 2); -- Carlos: Probióticos + dieta de ganho

-- Comentário sobre as senhas
-- TODAS AS SENHAS DOS USUÁRIOS DE EXEMPLO SÃO: password
-- Hash usado: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi

-- Usuários criados:
-- 1. gabriel@gmail.com (paciente)
-- 2. maria@gmail.com (paciente)  
-- 3. joao.medico@gmail.com (médico)
-- 4. ana.nutri@gmail.com (nutricionista)
-- 5. carlos@gmail.com (paciente)
-- 6. julia@gmail.com (paciente)
-- 7. pedro.medico@gmail.com (médico)
-- 8. sandra.nutri@gmail.com (nutricionista)