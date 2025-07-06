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
    data_consulta DATE
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
    data_consulta DATE
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