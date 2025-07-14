class MessageManager {
    constructor() {
        this.messageElement = document.getElementById('message');
    }

    show(message, type = 'info') {
        if (!this.messageElement) return;

        const typeStyles = {
            success: { 
                backgroundColor: '#d4edda', 
                color: '#155724', 
                borderLeft: '4px solid #28a745'
            },
            error: { 
                backgroundColor: '#f8d7da', 
                color: '#721c24', 
                borderLeft: '4px solid #dc3545'
            },
            warning: { 
                backgroundColor: '#fff3cd', 
                color: '#856404', 
                borderLeft: '4px solid #ffc107'
            },
            info: { 
                backgroundColor: '#d1ecf1', 
                color: '#0c5460', 
                borderLeft: '4px solid #17a2b8'
            }
        };

        const style = typeStyles[type] || typeStyles.info;
        
        Object.assign(this.messageElement.style, style);
        this.messageElement.textContent = message;
        this.messageElement.style.display = 'block';

        // Auto-hide após 5 segundos
        setTimeout(() => this.hide(), 5000);
    }

    hide() {
        if (this.messageElement) {
            this.messageElement.style.display = 'none';
        }
    }

    success(message) {
        this.show(message, 'success');
    }

    error(message) {
        this.show(message, 'error');
    }

    warning(message) {
        this.show(message, 'warning');
    }

    info(message) {
        this.show(message, 'info');
    }
}
