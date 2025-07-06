# Correção do Erro "Já existe um paciente cadastrado com este CPF"

## 🔍 Problemas Identificados

### 1. **Ordem Incorreta dos Parâmetros no Construtor**
O `PacienteController` estava passando parâmetros na ordem errada:
- **Esperado**: `Paciente($id_paciente, $id_usuario, $cpf, $nis)`
- **Estava sendo passado**: `Paciente($id_usuario, $cpf, $nis)`

### 2. **Conversão de Tipos**
O `$id_usuario` da sessão pode ser string, mas o construtor espera `int`.

### 3. **Duplicação de Tabela no SQL**
O arquivo `database/init.sql` tinha duas definições da tabela `paciente`.

### 4. **Falta de Verificação Prévia**
Não havia verificação se o usuário já possui cadastro de paciente.

## ✅ **Correções Aplicadas**

### 1. **PacienteController.php - Método `criar()`**
```php
// ANTES:
$paciente = new \Htdocs\Src\Models\Entity\Paciente(
    $id_usuario,
    $cpf,
    $nis
);

// DEPOIS:
// Converter id_usuario para int se for string
$id_usuario = is_string($id_usuario) ? (int)$id_usuario : $id_usuario;

// Verificar se o usuário já possui cadastro de paciente
$pacienteExistente = $this->service->getPacienteRepository()->findByUsuarioId($id_usuario);
if ($pacienteExistente) {
    echo json_encode(['error' => 'Este usuário já possui um cadastro de paciente.']);
    return;
}

$paciente = new \Htdocs\Src\Models\Entity\Paciente(
    null, // id_paciente será gerado pelo banco
    $id_usuario,
    $cpf,
    $nis
);
```

### 2. **PacienteController.php - Método `atualizarConta()`**
```php
// Buscar o paciente existente para obter o id_paciente
$pacienteExistente = $this->service->getPacienteRepository()->findByUsuarioId($id_usuario);
$id_paciente = $pacienteExistente ? $pacienteExistente['id_paciente'] : null;

$paciente = new \Htdocs\Src\Models\Entity\Paciente(
    $id_paciente,
    $id_usuario,
    $cpf,
    $nis
);
```

### 3. **PacienteRepository.php - Melhor Tratamento de Erros**
```php
} catch (\PDOException $e) {
    if ($e->getCode() == 23000) {
        $errorMessage = $e->getMessage();
        if (strpos($errorMessage, 'cpf') !== false) {
            throw new \Exception("Já existe um paciente cadastrado com este CPF.");
        } elseif (strpos($errorMessage, 'id_usuario') !== false) {
            throw new \Exception("Este usuário já possui um cadastro de paciente.");
        } else {
            throw new \Exception("Violação de integridade: dados duplicados.");
        }
    }
    throw $e;
}
```

### 4. **Arquivos de Debug Criados**
- `debug_paciente.php` - Para verificar dados na tabela
- `debug_paciente.sql` - Script SQL para debug

## 🎯 **Resultado Esperado**

Agora o sistema deve:
1. ✅ Verificar se o usuário já é paciente antes de tentar criar
2. ✅ Passar parâmetros na ordem correta para o construtor
3. ✅ Converter tipos adequadamente (string → int)
4. ✅ Dar mensagens de erro mais específicas
5. ✅ Funcionar corretamente sem conflitos de CPF/usuário

## 🔧 **Como Testar**

1. **Acesse** `debug_paciente.php` para verificar estado da tabela
2. **Tente cadastrar** um paciente via interface
3. **Verifique** se não há mais erro de CPF duplicado
4. **Teste** atualização de dados do paciente

O sistema agora deve funcionar corretamente! 🎉
