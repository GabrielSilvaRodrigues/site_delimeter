class AcessibilidadeManager {
    constructor() {
        this.fontSize = 100;
        this.init();
    }

    init() {
        // Configura eventos e estado inicial
        this.restaurarConfiguracoes();
    }

    toggleContraste() {
        document.body.classList.toggle('alto-contraste');
        const btn = document.getElementById('contraste-btn');
        if (btn) {
            btn.setAttribute('aria-pressed', document.body.classList.contains('alto-contraste'));
        }
        this.salvarConfiguracoes();
    }

    aumentarFonte() {
        if (this.fontSize < 150) {
            this.fontSize += 10;
            document.documentElement.style.fontSize = this.fontSize + '%';
            this.salvarConfiguracoes();
        }
    }

    diminuirFonte() {
        if (this.fontSize > 80) {
            this.fontSize -= 10;
            document.documentElement.style.fontSize = this.fontSize + '%';
            this.salvarConfiguracoes();
        }
    }

    toggleDaltonismo(tipo) {
        const tiposDisponiveis = ['protanopia', 'deuteranopia', 'tritanopia'];
        
        // Remove todos os tipos primeiro
        tiposDisponiveis.forEach(t => document.body.classList.remove(t));
        
        // Adiciona o tipo se não estiver ativo
        if (!document.body.classList.contains(tipo)) {
            document.body.classList.add(tipo);
        }
        
        this.salvarConfiguracoes();
    }

    resetarAcessibilidade() {
        // Remove alto contraste
        document.body.classList.remove('alto-contraste');

        // Restaura tamanho da fonte
        this.fontSize = 100;
        document.documentElement.style.fontSize = '';

        // Remove filtros de daltonismo
        document.body.classList.remove('protanopia', 'deuteranopia', 'tritanopia');

        // Atualiza atributos ARIA
        const btn = document.getElementById('contraste-btn');
        if (btn) btn.setAttribute('aria-pressed', 'false');

        this.limparConfiguracoes();
    }

    salvarConfiguracoes() {
        const config = {
            fontSize: this.fontSize,
            altoContraste: document.body.classList.contains('alto-contraste'),
            daltonismo: this.getDaltonismoAtivo()
        };
        localStorage.setItem('acessibilidade-config', JSON.stringify(config));
    }

    restaurarConfiguracoes() {
        const config = localStorage.getItem('acessibilidade-config');
        if (config) {
            const dados = JSON.parse(config);
            
            // Restaurar fonte
            if (dados.fontSize) {
                this.fontSize = dados.fontSize;
                document.documentElement.style.fontSize = this.fontSize + '%';
            }
            
            // Restaurar contraste
            if (dados.altoContraste) {
                document.body.classList.add('alto-contraste');
                const btn = document.getElementById('contraste-btn');
                if (btn) btn.setAttribute('aria-pressed', 'true');
            }
            
            // Restaurar daltonismo
            if (dados.daltonismo) {
                document.body.classList.add(dados.daltonismo);
            }
        }
    }

    getDaltonismoAtivo() {
        const tipos = ['protanopia', 'deuteranopia', 'tritanopia'];
        return tipos.find(tipo => document.body.classList.contains(tipo)) || null;
    }

    limparConfiguracoes() {
        localStorage.removeItem('acessibilidade-config');
    }
}

// Instância global para compatibilidade com HTML inline
let acessibilidade = null;

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    acessibilidade = new AcessibilidadeManager();
});

// Funções globais para compatibilidade com HTML inline
function toggleContraste() {
    if (acessibilidade) acessibilidade.toggleContraste();
}

function aumentarFonte() {
    if (acessibilidade) acessibilidade.aumentarFonte();
}

function diminuirFonte() {
    if (acessibilidade) acessibilidade.diminuirFonte();
}

function toggleDaltonismo(tipo) {
    if (acessibilidade) acessibilidade.toggleDaltonismo(tipo);
}

function resetarAcessibilidade() {
    if (acessibilidade) acessibilidade.resetarAcessibilidade();
}
