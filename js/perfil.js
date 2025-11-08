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

function validarSenha(senha) {
    if (senha.length === 0) {
         return { valido: true, mensagem: "" };
    }
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

document.addEventListener('DOMContentLoaded', () => {
    const editButton = document.getElementById('edit-button'); 
    const viewMode = document.getElementById('view-mode');
    const editMode = document.getElementById('edit-mode');
    const cancelButton = document.getElementById('cancel-button'); 
    const statsBox = document.querySelector('.div-estatistica'); 

    const viewNome = document.querySelector('#view-mode p[data-field="nome"]');
    const viewEmail = document.querySelector('#view-mode p[data-field="email"]');

    const readOnlyFields = [
        document.getElementById('edit-username'),
        document.getElementById('edit-data-nascimento'),
        document.getElementById('edit-cpf')
    ];

    function toggleEditMode() {
        if (viewMode.style.display !== 'none') {
            viewMode.style.display = 'none';
            editMode.style.display = 'block';
            
            if (statsBox) {
                statsBox.style.display = 'none';
            }

            readOnlyFields.forEach(field => {
                if (field) {
                    field.setAttribute('readonly', 'readonly');
                    field.classList.add('read-only-field'); 
                }
            });

        } else {
            editMode.style.display = 'none';
            viewMode.style.display = 'block';
            
            if (statsBox) {
                statsBox.style.display = 'block';
            }
        }
    }

    if (editButton) {
        editButton.addEventListener('click', (e) => {
            e.preventDefault();
            toggleEditMode();
        });
    }

    if (cancelButton) {
        cancelButton.addEventListener('click', (e) => {
            e.preventDefault();
            toggleEditMode();
        });
    }

    const formEdicao = document.getElementById('edit-mode');
    const inputNome = document.getElementById('edit-nome');
    const inputEmail = document.getElementById('edit-email');
    const inputSenha = document.getElementById('edit-senha');
    const inputTelefone = document.getElementById('edit-telefone');

    if (inputTelefone) {
        inputTelefone.addEventListener('input', function() {
            mascararTelefone(this); 
        });
    }
    
    async function validarEAtualizarPerfil(event) {
        event.preventDefault();
        
        let formularioValido = true;
        
        limparErro(inputNome);
        limparErro(inputEmail);
        limparErro(inputSenha);
        limparErro(inputTelefone);

        const resNome = validarNome(inputNome.value);
        if (!resNome.valido) {
            mostrarErro(inputNome, resNome.mensagem);
            formularioValido = false;
        }

        const resEmail = validarEmail(inputEmail.value);
        if (!resEmail.valido) {
            mostrarErro(inputEmail, resEmail.mensagem);
            formularioValido = false;
        }

        const resSenha = validarSenha(inputSenha.value);
        if (!resSenha.valido) {
            mostrarErro(inputSenha, resSenha.mensagem);
            formularioValido = false;
        }

        const resTelefone = validarTelefone(inputTelefone.value);
        if (!resTelefone.valido) {
            mostrarErro(inputTelefone, resTelefone.mensagem);
            formularioValido = false;
        }

        if (formularioValido) {
            const formData = new FormData();
            formData.append('nome_completo', inputNome.value);
            formData.append('email', inputEmail.value);
            formData.append('telefone', inputTelefone.value);
            formData.append('senha', inputSenha.value);

            try {
                const response = await fetch('../backend/atualizar_perfil.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    alert(result.message);
                    
                    if(viewNome) viewNome.textContent = result.newData.nome;
                    if(viewEmail) viewEmail.textContent = result.newData.email;
                    
                    inputSenha.value = "";
                    
                    toggleEditMode();
                } else {
                    alert('Erro: ' + result.message);
                }

            } catch (error) {
                console.error('Erro na requisição fetch:', error);
                alert('Ocorreu um erro de comunicação com o servidor.');
            }
            
        } else {
            alert('Por favor, corrija os erros no formulário.');
        }
    }

    if (formEdicao) {
        formEdicao.addEventListener('submit', validarEAtualizarPerfil);
    }

    function validarAoPerderFoco(input, funcaoValidacao) {
        if (input) {
            input.addEventListener('blur', function() {
                const valor = this.value;

                if (valor.trim() === '' && input.id !== 'edit-email') {
                    limparErro(this);
                    return;
                }

                const resultado = funcaoValidacao(valor);
                
                if (resultado && !resultado.valido) {
                    mostrarErro(this, resultado.mensagem);
                } else if (resultado && resultado.valido) {
                    limparErro(this);
                }
            });
        }
    }

    validarAoPerderFoco(inputNome, validarNome);
    validarAoPerderFoco(inputEmail, validarEmail);
    validarAoPerderFoco(inputSenha, validarSenha);
    validarAoPerderFoco(inputTelefone, validarTelefone);
});