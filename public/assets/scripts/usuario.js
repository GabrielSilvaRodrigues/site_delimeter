class ApiClient {
    async post(url, data) {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        });
        return response.json();
    }
}

class MessageManager {
    success(message) {
        Swal.fire({
            icon: 'success',
            title: 'Sucesso',
            text: message,
            timer: 3000,
            showConfirmButton: false
        });
    }

    error(message) {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: message,
            timer: 3000,
            showConfirmButton: false
        });
    }
}

class FormValidator {
    constructor() {
        this.rules = {};
    }

    addRule(field, rules) {
        this.rules[field] = rules;
        return this;
    }

    validate(form) {
        let isValid = true;

        for (const field in this.rules) {
            const fieldRules = this.rules[field];
            const value = form[field].value.trim();
            fieldRules.forEach(rule => {
                if (!this.applyRule(rule, value, form)) {
                    isValid = false;
                    this.showError(field, rule.message);
                } else {
                    this.clearError(field);
                }
            });
        }

        return isValid;
    }

    applyRule(rule, value, form) {
        switch (rule.type) {
            case 'required':
                return value !== '';
            case 'minLength':
                return value.length >= rule.min;
            case 'email':
                return /^\S+@\S+\.\S+$/.test(value);
            case 'match':
                return value === form[rule.field].value;
            default:
                return true;
        }
    }

    showError(field, message) {
        const input = document.querySelector(`[name="${field}"]`);
        input.classList.add('erro');
        let error = input.parentNode.querySelector('.mensagem-erro');
        if (!error) {
            error = document.createElement('span');
            error.classList.add('mensagem-erro');
            input.parentNode.insertBefore(error, input.nextSibling);
        }
        error.textContent = message;
    }

    clearError(field) {
        const input = document.querySelector(`[name="${field}"]`);
        input.classList.remove('erro');
        const error = input.parentNode.querySelector('.mensagem-erro');
        if (error) {
            error.remove();
        }
    }
}

class UsuarioManager {
    constructor() {
        this.apiClient = new ApiClient();
        this.messageManager = new MessageManager();
        this.validator = new FormValidator();
        this.initializeEventListeners();
        this.setupValidation();
    }

    initializeEventListeners() {
        // Form submission
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        });

        // UI effects
        this.setupUIEffects();
        
        // Tour if not completed
        this.checkAndShowTour();
    }

    setupValidation() {
        this.validator
            .addRule('nome', [
                { type: 'required' },
                { type: 'minLength', min: 2 }
            ])
            .addRule('email', [
                { type: 'required' },
                { type: 'email' }
            ])
            .addRule('senha', [
                { type: 'required' },
                { type: 'minLength', min: 6 }
            ])
            .addRule('confirmar_senha', [
                { type: 'required' },
                { type: 'match', field: 'senha' }
            ]);
    }

    handleFormSubmit(e) {
        const form = e.target;
        
        if (!this.validator.validate(form)) {
            e.preventDefault();
            this.messageManager.error('Por favor, corrija os erros no formulário.');
            return;
        }
        
        // Form específico handling pode ser adicionado aqui
    }

    setupUIEffects() {
        // Hover effects for cards
        const cards = document.querySelectorAll('.usuario-card, a[style*="background:#e0f7fa"], a[style*="background:#e8f5e9"], a[style*="background:#e3f2fd"]');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 4px 12px rgba(76,175,80,0.2)';
                this.style.transition = 'all 0.3s ease';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 1px 6px #4caf5011';
            });
        });
    }

    checkAndShowTour() {
        if (sessionStorage.getItem('delimeterTourCompleted') !== 'true') {
            new TourManager().start();
        }
    }

    // Métodos para gerenciamento de perfis
    async criarPerfil(tipo) {
        const urls = {
            paciente: '/paciente/cadastro',
            nutricionista: '/nutricionista/cadastro',
            medico: '/medico/cadastro'
        };
        
        if (urls[tipo]) {
            window.location.href = urls[tipo];
        } else {
            this.messageManager.error('Tipo de perfil inválido.');
        }
    }

    async atualizarPerfil(tipo, dados) {
        try {
            const response = await this.apiClient.post(`/api/${tipo}/atualizar`, dados);
            
            if (response.success) {
                this.messageManager.success(`Dados do ${tipo} atualizados com sucesso!`);
                setTimeout(() => location.reload(), 1500);
            } else {
                throw new Error(response.error || 'Erro desconhecido');
            }
        } catch (error) {
            this.messageManager.error(`Erro ao atualizar: ${error.message}`);
        }
    }

    async excluirPerfil(tipo) {
        if (!confirm(`Tem certeza que deseja excluir seu perfil de ${tipo}?`)) {
            return;
        }
        
        try {
            const response = await this.apiClient.post(`/api/${tipo}/excluir`);
            
            if (response.success) {
                this.messageManager.success('Perfil excluído com sucesso!');
                setTimeout(() => location.reload(), 1500);
            } else {
                throw new Error(response.error || 'Erro desconhecido');
            }
        } catch (error) {
            this.messageManager.error(`Erro ao excluir perfil: ${error.message}`);
        }
    }

    // ...existing code...
}

class TourManager {
    constructor() {
        this.slides = [
            {
                title: "Bem-vindo ao Delimeter!",
                text: "Sua plataforma completa para saúde nutricional inteligente.",
                image: "/public/assets/images/persefone-feliz.png"
            },
            {
                title: "Soluções Integradas",
                text: "Desde cálculo nutricional até conexão com nutricionistas - tudo em um só lugar.",
                image: "/public/assets/images/nutricionista.jpg"
            },
            {
                title: "Parcerias de Confiança",
                text: "Trabalhamos com SUS, CRN3 e CREMESP para oferecer o melhor serviço.",
                image: "/public/assets/images/sus.jpeg"
            }
        ];
        this.currentSlide = 0;
    }

    start() {
        this.showSlide();
    }

    showSlide() {
        Swal.fire({
            title: this.slides[this.currentSlide].title,
            html: this.getSlideHTML(),
            showConfirmButton: false,
            showCancelButton: false,
            allowOutsideClick: true,
            background: '#ffffff',
            width: 600,
            didOpen: () => {
                const popup = Swal.getPopup();
                popup.addEventListener('click', (e) => this.handleInteraction(e));
                document.addEventListener('keydown', (e) => this.handleInteraction(e));
            },
            willClose: () => {
                document.removeEventListener('keydown', this.handleInteraction);
            }
        });
    }

    getSlideHTML() {
        return `
            <div style="text-align: center; cursor: pointer;">
                <img src="${this.slides[this.currentSlide].image}" 
                     style="max-width: 100%; max-height: 200px; object-fit: contain; border-radius: 8px; margin: 10px 0 15px;"
                     alt="${this.slides[this.currentSlide].title}">
                <p style="color: #555; margin: 15px 0 20px; font-size: 1.05em;">
                    ${this.slides[this.currentSlide].text}
                </p>
                <div style="display: flex; justify-content: center; gap: 8px;">
                    ${this.slides.map((_, i) => `
                        <div style="width: 10px; height: 10px; border-radius: 50%; 
                            background: ${i === this.currentSlide ? '#4CAF50' : '#ddd'};"></div>
                    `).join('')}
                </div>
            </div>
        `;
    }

    handleInteraction(e) {
        const isNext = e.key === 'ArrowRight' || e.type === 'click';
        const isPrev = e.key === 'ArrowLeft';
        
        if (isNext) {
            if (this.currentSlide < this.slides.length - 1) {
                this.currentSlide++;
                this.showSlide();
            } else {
                this.complete();
            }
        } else if (isPrev && this.currentSlide > 0) {
            this.currentSlide--;
            this.showSlide();
        }
    }

    complete() {
        sessionStorage.setItem('delimeterTourCompleted', 'true');
        Swal.close();
    }
}

// Inicializar quando DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    window.usuarioManager = new UsuarioManager();
});

// Funções globais para compatibilidade com HTML inline
function criarPerfil(tipo) {
    if (window.usuarioManager) {
        window.usuarioManager.criarPerfil(tipo);
    }
}

function atualizarPaciente() {
    if (window.usuarioManager) {
        const dados = {
            cpf: document.getElementById('cpf_paciente').value,
            nis: document.getElementById('nis_paciente').value
        };
        window.usuarioManager.atualizarPerfil('paciente', dados);
    }
}

function atualizarNutricionista() {
    if (window.usuarioManager) {
        const dados = {
            crm_nutricionista: document.getElementById('crm_nutricionista').value,
            cpf: document.getElementById('cpf_nutricionista').value
        };
        window.usuarioManager.atualizarPerfil('nutricionista', dados);
    }
}

function atualizarMedico() {
    if (window.usuarioManager) {
        const dados = {
            crm_medico: document.getElementById('crm_medico').value,
            cpf: document.getElementById('cpf_medico').value
        };
        window.usuarioManager.atualizarPerfil('medico', dados);
    }
}

function excluirPerfil(tipo) {
    if (window.usuarioManager) {
        window.usuarioManager.excluirPerfil(tipo);
    }
}

function sairPerfil(tipo) {
    if (!confirm(`Tem certeza que deseja sair do perfil de ${tipo}?`)) {
        return;
    }
    
    fetch(`/${tipo}/conta/sair`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(() => {
        window.location.href = '/usuario';
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao sair do perfil. Tente novamente.');
    });
}

function confirmarExclusaoTotal() {
    if (!confirm('ATENÇÃO: Esta ação irá excluir sua conta completamente, incluindo todos os perfis e dados associados. Esta ação não pode ser desfeita.\n\nTem certeza que deseja continuar?')) {
        return;
    }
    
    if (!confirm('Última confirmação: Tem ABSOLUTA certeza que deseja excluir sua conta permanentemente?')) {
        return;
    }
    
    fetch('/api/usuario/excluir-completo', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('Conta excluída com sucesso. Você será redirecionado.');
            window.location.href = '/';
        } else {
            alert('Erro ao excluir conta: ' + (result.error || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro de conexão. Tente novamente.');
    });
}

function sairCompleto() {
    if (!confirm('Tem certeza que deseja sair do sistema?')) {
        return;
    }
    
    window.location.href = '/usuario/conta/sair';
}

// Máscara para CPF
function aplicarMascaraCPF(elemento) {
    elemento.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 11) {
            value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
            e.target.value = value;
        }
    });
}

// Aplicar máscaras quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    // CPF masks
    const cpfInputs = document.querySelectorAll('input[id*="cpf"]');
    cpfInputs.forEach(aplicarMascaraCPF);
    
    // NIS mask
    const nisInput = document.getElementById('nis_paciente');
    if (nisInput) {
        nisInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d{5})(\d{2})(\d{1})/, '$1.$2.$3-$4');
                e.target.value = value;
            }
        });
    }
});