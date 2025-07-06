<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deliméter - Priorize sua Alimentação</title>
    <?php
    try {
        if (isset($_SESSION['usuario'])) {
            echo '<link rel="stylesheet" href="/public/assets/styles/' . $_SESSION['usuario']['tipo'] . '.css">';
        } else {
            echo '<link rel="stylesheet" href="/public/assets/styles/delimeter.css">';
        }
    } catch (Exception $e) {
        echo "Erro ao incluir o CSS do usuário: " . $e->getMessage();
        exit(1);
    }
    ?>
    <link rel="stylesheet" href="/public/assets/styles/acessibilidade.css">
</head>

<body>
    <header>
        <div class="logo">
            <a href="/delimeter"><img src="/public/assets/images/logo.png" alt="Logo Delímiter"></a>
        </div>
        <div class="menu-hamburguer">
            <input type="checkbox" id="menu-toggle">
            <label for="menu-toggle" class="menu-icon">
                <div class="linha"></div>
                <div class="linha"></div>
                <div class="linha"></div>
            </label>
            <div class="overlay">
                <nav>
                    <ul aria-label="Acessibilidade" class="acessibilidade">
                        <li><a href="/delimeter/sobre" class="link">Sobre Nós</a></li>
                        <li><a href="/delimeter/calculo" class="link">Cálculo nutricional</a></li>
                        <?php if (isset($_SESSION['usuario'])): ?>
                            <?php if ($_SESSION['usuario']['tipo'] === 'paciente'): ?>
                                <li><a href="/paciente" class="link">Painel</a></li>
                            <?php elseif ($_SESSION['usuario']['tipo'] === 'nutricionista'): ?>
                                <li><a href="/nutricionista" class="link">Painel</a></li>
                            <?php elseif ($_SESSION['usuario']['tipo'] === 'medico'): ?>
                                <li><a href="/medico" class="link">Painel</a></li>
                            <?php endif; ?>
                            <li><a href="/conta" class="link">Conta</a></li>
                            <li><a href="/usuario" class="link">Home</a></li>
                        <?php else: ?>
                            <li><a href="/usuario/cadastro" class="link">Cadastrar-se</a></li>
                            <li><a href="/usuario/login" class="link">Login</a></li>
                        <?php endif; ?>
                        <li><p>Modificar tamanho da fonte</p></li>
                        <li><button onclick="aumentarFonte()" id="aumentar-fonte-btn" aria-label="Aumentar tamanho da fonte" accesskey="2" tabindex="2">A+</button></li>
                        <li><button onclick="diminuirFonte()" id="diminuir-fonte-btn" aria-label="Diminuir tamanho da fonte" accesskey="3" tabindex="3">A-</button></li>
                        <li><p>Modificar estilo da exibição</p></li>
                        <li><button onclick="toggleContraste()" id="contraste-btn" aria-pressed="false" aria-label="Ativar ou desativar alto contraste">Alto Contraste</button></li>
                        <li><button onclick="toggleDaltonismo('protanopia')" aria-label="Simular protanopia">Protanopia</button></li>
                        <li><button onclick="toggleDaltonismo('deuteranopia')" aria-label="Simular deuteranopia">Deuteranopia</button></li>
                        <li><button onclick="toggleDaltonismo('tritanopia')" aria-label="Simular tritanopia">Tritanopia</button></li>
                        <button onclick="resetarAcessibilidade()" aria-label="Restaurar configurações de acessibilidade">Voltar ao normal</button>
                    </ul>
                </nav>
            </div>
        </div>
    </header>