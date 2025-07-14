class FormValidator {
    constructor() {
        this.rules = {};
        this.messages = {
            required: 'Este campo é obrigatório.',
            email: 'Digite um email válido.',
            minLength: 'Este campo deve ter pelo menos {min} caracteres.',
            maxLength: 'Este campo deve ter no máximo {max} caracteres.',
            match: 'Os campos não coincidem.',
            cpf: 'CPF inválido.',
            nis: 'NIS inválido.'
        };
    }

    addRule(fieldId, rules) {
        this.rules[fieldId] = rules;
        return this;
    }

    validate(formElement) {
        this.clearErrors();
        
        let isValid = true;
        const formData = new FormData(formElement);
        
        for (const [fieldId, rules] of Object.entries(this.rules)) {
            const field = document.getElementById(fieldId);
            const value = formData.get(field?.name || fieldId) || '';
            
            for (const rule of rules) {
                const result = this.validateField(value, rule, formData);
                if (!result.valid) {
                    this.showFieldError(field, result.message);
                    isValid = false;
                    break;
                }
            }
        }

        return isValid;
    }

    validateField(value, rule, formData) {
        switch (rule.type) {
            case 'required':
                return {
                    valid: value.trim() !== '',
                    message: rule.message || this.messages.required
                };
                
            case 'email':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return {
                    valid: !value || emailRegex.test(value),
                    message: rule.message || this.messages.email
                };
                
            case 'minLength':
                return {
                    valid: value.length >= rule.min,
                    message: rule.message || this.messages.minLength.replace('{min}', rule.min)
                };
                
            case 'maxLength':
                return {
                    valid: value.length <= rule.max,
                    message: rule.message || this.messages.maxLength.replace('{max}', rule.max)
                };
                
            case 'match':
                const matchValue = formData.get(rule.field) || '';
                return {
                    valid: value === matchValue,
                    message: rule.message || this.messages.match
                };
                
            case 'cpf':
                return {
                    valid: this.validateCPF(value),
                    message: rule.message || this.messages.cpf
                };
                
            case 'nis':
                return {
                    valid: this.validateNIS(value),
                    message: rule.message || this.messages.nis
                };
                
            default:
                return { valid: true, message: '' };
        }
    }

    validateCPF(cpf) {
        if (!cpf) return true; // Opcional
        
        cpf = cpf.replace(/[^\d]/g, '');
        
        if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
            return false;
        }
        
        // Validação do dígito verificador
        let sum = 0;
        for (let i = 0; i < 9; i++) {
            sum += parseInt(cpf.charAt(i)) * (10 - i);
        }
        
        let remainder = 11 - (sum % 11);
        let digit1 = remainder >= 10 ? 0 : remainder;
        
        if (parseInt(cpf.charAt(9)) !== digit1) return false;
        
        sum = 0;
        for (let i = 0; i < 10; i++) {
            sum += parseInt(cpf.charAt(i)) * (11 - i);
        }
        
        remainder = 11 - (sum % 11);
        let digit2 = remainder >= 10 ? 0 : remainder;
        
        return parseInt(cpf.charAt(10)) === digit2;
    }

    validateNIS(nis) {
        if (!nis) return true; // Opcional
        
        nis = nis.replace(/[^\d]/g, '');
        return nis.length === 11;
    }

    showFieldError(field, message) {
        if (!field) return;
        
        field.classList.add('error');
        
        // Remover mensagem anterior
        const existingError = field.parentNode.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }
        
        // Adicionar nova mensagem
        const errorElement = document.createElement('span');
        errorElement.className = 'error-message';
        errorElement.textContent = message;
        errorElement.style.cssText = `
            color: #dc3545;
            font-size: 12px;
            display: block;
            margin-top: 4px;
        `;
        
        field.parentNode.insertBefore(errorElement, field.nextSibling);
    }

    clearErrors() {
        // Remover classes de erro
        document.querySelectorAll('.error').forEach(el => {
            el.classList.remove('error');
        });
        
        // Remover mensagens de erro
        document.querySelectorAll('.error-message').forEach(el => {
            el.remove();
        });
    }
}
