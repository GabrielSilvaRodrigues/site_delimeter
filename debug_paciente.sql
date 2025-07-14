-- Script para corrigir problemas na tabela paciente

-- Primeiro, verificar se há registros
SELECT 'Registros atuais na tabela paciente:' AS info;
SELECT * FROM paciente;

-- Limpar a tabela se necessário (descomente a linha abaixo se quiser limpar)
-- DELETE FROM paciente;

-- Verificar a estrutura da tabela
SELECT 'Estrutura da tabela paciente:' AS info;
DESCRIBE paciente;

-- Verificar constraints
SELECT 'Constraints da tabela paciente:' AS info;
SELECT 
    CONSTRAINT_NAME,
    CONSTRAINT_TYPE,
    TABLE_NAME
FROM information_schema.TABLE_CONSTRAINTS 
WHERE TABLE_NAME = 'paciente' AND TABLE_SCHEMA = 'delimeter';
