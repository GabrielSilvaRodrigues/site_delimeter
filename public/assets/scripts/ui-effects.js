class UIEffectsManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupCardHoverEffects();
        this.setupButtonEffects();
        this.setupFormEffects();
    }

    setupCardHoverEffects() {
        const cards = document.querySelectorAll('.funcionalidade-card');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => this.onCardHover(card));
            card.addEventListener('mouseleave', () => this.onCardLeave(card));
            card.addEventListener('focus', () => this.onCardHover(card));
            card.addEventListener('blur', () => this.onCardLeave(card));
        });
    }

    setupButtonEffects() {
        const buttons = document.querySelectorAll('button, .btn, a[href]');
        
        buttons.forEach(button => {
            button.addEventListener('mouseenter', () => this.onButtonHover(button));
            button.addEventListener('mouseleave', () => this.onButtonLeave(button));
        });
    }

    setupFormEffects() {
        const inputs = document.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            input.addEventListener('focus', () => this.onInputFocus(input));
            input.addEventListener('blur', () => this.onInputBlur(input));
        });
    }

    onCardHover(card) {
        card.style.transform = 'translateY(-5px)';
        card.style.boxShadow = '0 4px 20px rgba(76,175,80,0.2)';
        card.style.transition = 'all 0.3s ease';
    }

    onCardLeave(card) {
        card.style.transform = 'translateY(0)';
        card.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
    }

    onButtonHover(button) {
        if (!button.dataset.originalTransform) {
            button.dataset.originalTransform = button.style.transform || 'none';
        }
        button.style.transform = 'scale(1.02)';
        button.style.transition = 'transform 0.2s ease';
    }

    onButtonLeave(button) {
        button.style.transform = button.dataset.originalTransform || 'none';
    }

    onInputFocus(input) {
        input.style.borderColor = '#4caf50';
        input.style.boxShadow = '0 0 0 2px rgba(76,175,80,0.2)';
        input.style.transition = 'all 0.3s ease';
    }

    onInputBlur(input) {
        input.style.borderColor = '#ddd';
        input.style.boxShadow = 'none';
    }

    animateSuccess(element) {
        element.style.animation = 'pulse 0.6s ease-in-out';
        setTimeout(() => {
            element.style.animation = '';
        }, 600);
    }

    animateError(element) {
        element.style.animation = 'shake 0.6s ease-in-out';
        setTimeout(() => {
            element.style.animation = '';
        }, 600);
    }

    showLoading(element, text = 'Carregando...') {
        const originalText = element.textContent;
        element.dataset.originalText = originalText;
        element.textContent = text;
        element.disabled = true;
        element.style.opacity = '0.7';
        
        return () => this.hideLoading(element);
    }

    hideLoading(element) {
        element.textContent = element.dataset.originalText || element.textContent;
        element.disabled = false;
        element.style.opacity = '1';
        delete element.dataset.originalText;
    }
}

// CSS animations inline (pode ser movido para um arquivo CSS separado)
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
`;
document.head.appendChild(style);

// Instância global
let uiEffects = null;

document.addEventListener('DOMContentLoaded', function() {
    uiEffects = new UIEffectsManager();
});
