function validarNome(nome) {
    const nomeTrim = nome.trim();
    if (nomeTrim.length < 3) {
        return { valido: false, mensagem: "Nome deve ter pelo menos 3 caracteres" };
    }
    if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(nomeTrim)) {
        return { valido: false, mensagem: "Nome deve conter apenas letras" };
    }
    if (nomeTrim.split(' ').length < 2) {
        return { valido: false, mensagem: "Digite seu nome completo" };
    }
    return { valido: true, mensagem: "" };
}

function validarDataNascimento(data) {
    if (!data) {
        return { valido: false, mensagem: "Data de nascimento é obrigatória" };
    }
    
    const dataNasc = new Date(data);
    const hoje = new Date();
    const idade = hoje.getFullYear() - dataNasc.getFullYear();
    const mes = hoje.getMonth() - dataNasc.getMonth();
    
    if (mes < 0 || (mes === 0 && hoje.getDate() < dataNasc.getDate())) {
        idade--;
    }
    
    if (dataNasc > hoje) {
        return { valido: false, mensagem: "Data de nascimento não pode ser futura" };
    }
    
    if (idade < 13) {
        return { valido: false, mensagem: "Você deve ter pelo menos 13 anos" };
    }
    
    if (idade > 120) {
        return { valido: false, mensagem: "Data de nascimento inválida" };
    }
    
    return { valido: true, mensagem: "" };
}

function validarCPF(cpf) {
    cpf = cpf.replace(/[^\d]/g, '');
    
    if (cpf.length !== 11) {
        return { valido: false, mensagem: "CPF deve ter 11 dígitos" };
    }
    
    if (/^(\d)\1{10}$/.test(cpf)) {
        return { valido: false, mensagem: "CPF inválido" };
    }
    
    let soma = 0;
    let resto;
    
    for (let i = 1; i <= 9; i++) {
        soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
    }
    
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.substring(9, 10))) {
        return { valido: false, mensagem: "CPF inválido" };
    }
    
    soma = 0;
    for (let i = 1; i <= 10; i++) {
        soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
    }
    
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.substring(10, 11))) {
        return { valido: false, mensagem: "CPF inválido" };
    }
    
    return { valido: true, mensagem: "" };
}

function validarTelefone(telefone) {
    const telLimpo = telefone.replace(/[^\d]/g, '');
    
    if (telLimpo.length < 10 || telLimpo.length > 11) {
        return { valido: false, mensagem: "Telefone deve ter 10 ou 11 dígitos" };
    }
    
    if (/^(\d)\1+$/.test(telLimpo)) {
        return { valido: false, mensagem: "Telefone inválido" };
    }
    
    return { valido: true, mensagem: "" };
}

function validarEmail(email) {
    const emailTrim = email.trim();
    
    if (emailTrim.length === 0) {
        return { valido: false, mensagem: "Email é obrigatório" };
    }
    
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regex.test(emailTrim)) {
        return { valido: false, mensagem: "Email inválido" };
    }
    
    return { valido: true, mensagem: "" };
}

function validarUsuario(usuario) {
    const usuarioTrim = usuario.trim();
    
    if (usuarioTrim.length < 3) {
        return { valido: false, mensagem: "Usuário deve ter pelo menos 3 caracteres" };
    }
    
    if (usuarioTrim.length > 20) {
        return { valido: false, mensagem: "Usuário deve ter no máximo 20 caracteres" };
    }
    
    if (!/^[a-zA-Z0-9_]+$/.test(usuarioTrim)) {
        return { valido: false, mensagem: "Usuário deve conter apenas letras, números e underscore" };
    }
    
    return { valido: true, mensagem: "" };
}

function validarSenha(senha) {
    if (senha.length < 6) {
        return { valido: false, mensagem: "Senha deve ter pelo menos 6 caracteres" };
    }
    
    if (senha.length > 50) {
        return { valido: false, mensagem: "Senha deve ter no máximo 50 caracteres" };
    }
    
    if (!/[A-Z]/.test(senha)) {
        return { valido: false, mensagem: "Senha deve conter pelo menos uma letra maiúscula" };
    }
    
    if (!/[a-z]/.test(senha)) {
        return { valido: false, mensagem: "Senha deve conter pelo menos uma letra minúscula" };
    }
    
    if (!/[0-9]/.test(senha)) {
        return { valido: false, mensagem: "Senha deve conter pelo menos um número" };
    }
    
    return { valido: true, mensagem: "" };
}

function mostrarErro(input, mensagem) {
    input.classList.add('erro');
    
    const erroAnterior = input.parentElement.querySelector('.mensagem-erro');
    if (erroAnterior) {
        erroAnterior.remove();
    }
    
    const divErro = document.createElement('div');
    divErro.className = 'mensagem-erro';
    divErro.textContent = mensagem;
    input.parentElement.appendChild(divErro);
}

function limparErro(input) {
    input.classList.remove('erro');
    const erroAnterior = input.parentElement.querySelector('.mensagem-erro');
    if (erroAnterior) {
        erroAnterior.remove();
    }
}

function mascararCPF(input) {
    let valor = input.value.replace(/\D/g, '');
    valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
    valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
    valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    input.value = valor;
}

function mascararTelefone(input) {
    let valor = input.value.replace(/\D/g, '');
    if (valor.length <= 10) {
        valor = valor.replace(/(\d{2})(\d)/, '($1) $2');
        valor = valor.replace(/(\d{4})(\d)/, '$1-$2');
    } else {
        valor = valor.replace(/(\d{2})(\d)/, '($1) $2');
        valor = valor.replace(/(\d{5})(\d)/, '$1-$2');
    }
    input.value = valor;
}

function validarFormulario(event) {
    event.preventDefault();
    
    let formularioValido = true;
    
    const inputs = document.querySelectorAll('.standart-form-items');
    const nome = inputs[0].value;
    const dataNascimento = inputs[1].value;
    const cpf = inputs[2].value;
    const telefone = inputs[3].value;
    const email = inputs[4].value;
    const usuario = inputs[5].value;
    const senha = inputs[6].value;
    
    inputs.forEach(input => limparErro(input));
    
    const resultados = [
        { input: inputs[0], resultado: validarNome(nome) },
        { input: inputs[1], resultado: validarDataNascimento(dataNascimento) },
        { input: inputs[2], resultado: validarCPF(cpf) },
        { input: inputs[3], resultado: validarTelefone(telefone) },
        { input: inputs[4], resultado: validarEmail(email) },
        { input: inputs[5], resultado: validarUsuario(usuario) },
        { input: inputs[6], resultado: validarSenha(senha) }
    ];
    
    resultados.forEach(({ input, resultado }) => {
        if (!resultado.valido) {
            mostrarErro(input, resultado.mensagem);
            formularioValido = false;
        }
    });
    
    if (formularioValido) {
        console.log('Formulário válido! Dados prontos para envio:');
        console.log({
            nome,
            dataNascimento,
            cpf,
            telefone,
            email,
            usuario,
            senha: '***' 
        });
        
        alert('Cadastro validado com sucesso!');
        
    } else {
        alert('Por favor, corrija os erros no formulário.');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const inputs = document.querySelectorAll('.standart-form-items');
    
    if (form) {
        form.addEventListener('submit', validarFormulario);
    }
    
    if (inputs[2]) {
        inputs[2].addEventListener('input', function() {
            mascararCPF(this);
        });
    }
    
    if (inputs[3]) {
        inputs[3].addEventListener('input', function() {
            mascararTelefone(this);
        });
    }
    
    inputs.forEach((input, index) => {
        input.addEventListener('blur', function() {
            const valor = this.value;
            let resultado;
            
            switch(index) {
                case 0: resultado = validarNome(valor); break;
                case 1: resultado = validarDataNascimento(valor); break;
                case 2: resultado = validarCPF(valor); break;
                case 3: resultado = validarTelefone(valor); break;
                case 4: resultado = validarEmail(valor); break;
                case 5: resultado = validarUsuario(valor); break;
                case 6: resultado = validarSenha(valor); break;
            }
            
            if (resultado && !resultado.valido && valor.trim() !== '') {
                mostrarErro(this, resultado.mensagem);
            } else if (resultado && resultado.valido) {
                limparErro(this);
            }
        });
    });
});