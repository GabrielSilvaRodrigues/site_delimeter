class MessageManager {
    constructor(elementId = 'message') {
        this.messageElement = document.getElementById(elementId);
        this.createMessageElement();
    }

    createMessageElement() {
        if (!this.messageElement) {
            this.messageElement = document.createElement('div');
            this.messageElement.id = 'message';
            this.messageElement.style.cssText = `
                display: none;
                margin-bottom: 15px;
                padding: 12px;
                border-radius: 6px;
                border-left: 4px solid;
                font-weight: 500;
                position: relative;
            `;
            
            // Inserir no início do body ou primeiro container
            const container = document.querySelector('main, .container, body');
            if (container) {
                container.insertBefore(this.messageElement, container.firstChild);
            }
        }
    }

    show(message, type = 'info', autoHide = 5000) {
        if (!this.messageElement) return;

        const typeStyles = {
            success: { 
                backgroundColor: '#d4f6d4', 
                color: '#2d5a2d', 
                borderColor: '#28a745',
                icon: '✅'
            },
            error: { 
                backgroundColor: '#f8d7da', 
                color: '#721c24', 
                borderColor: '#dc3545',
                icon: '❌'
            },
            warning: { 
                backgroundColor: '#fff3cd', 
                color: '#856404', 
                borderColor: '#ffc107',
                icon: '⚠️'
            },
            info: { 
                backgroundColor: '#d1ecf1', 
                color: '#0c5460', 
                borderColor: '#17a2b8',
                icon: 'ℹ️'
            }
        };

        const style = typeStyles[type] || typeStyles.info;
        
        this.messageElement.style.backgroundColor = style.backgroundColor;
        this.messageElement.style.color = style.color;
        this.messageElement.style.borderLeftColor = style.borderColor;
        this.messageElement.innerHTML = `${style.icon} ${message}`;
        this.messageElement.style.display = 'block';

        // Auto-hide
        if (autoHide > 0) {
            setTimeout(() => this.hide(), autoHide);
        }

        // Scroll para a mensagem
        this.messageElement.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    hide() {
        if (this.messageElement) {
            this.messageElement.style.display = 'none';
        }
    }

    success(message, autoHide = 5000) {
        this.show(message, 'success', autoHide);
    }

    error(message, autoHide = 8000) {
        this.show(message, 'error', autoHide);
    }

    warning(message, autoHide = 6000) {
        this.show(message, 'warning', autoHide);
    }

    info(message, autoHide = 5000) {
        this.show(message, 'info', autoHide);
    }
}
