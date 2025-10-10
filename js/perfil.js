document.addEventListener('DOMContentLoaded', () => {
    // 1. Elementos essenciais (MUITO IMPORTANTE!)
    const editButton = document.getElementById('edit-button'); 
    
    // As duas divisões principais
    const viewMode = document.getElementById('view-mode');
    const editMode = document.getElementById('edit-mode');
    
    // Botão de Cancelar
    const cancelButton = document.getElementById('cancel-button'); 

    // NOVO: Seleciona a DIV de estatísticas para escondê-la
    const statsBox = document.querySelector('.div-estatistica'); 

    // Lista de campos que serão apenas leitura (adicione os IDs corretos dos inputs)
    const readOnlyFields = [
        document.getElementById('edit-username'),
        document.getElementById('edit-data-nascimento'),
        document.getElementById('edit-cpf')
    ];

    // --- FUNÇÃO PRINCIPAL DE ALTERNÂNCIA ---
    function toggleEditMode() {
        if (viewMode.style.display !== 'none') {
            // Mudar para o modo de EDIÇÃO
            viewMode.style.display = 'none';
            editMode.style.display = 'block';
            
            // AÇÃO: Esconde a caixa de estatísticas
            if (statsBox) {
                statsBox.style.display = 'none';
            }

            // Aplica a regra de Read-Only
            readOnlyFields.forEach(field => {
                if (field) {
                    field.setAttribute('readonly', 'readonly');
                    field.classList.add('read-only-field'); 
                }
            });

        } else {
            // Mudar para o modo de VISUALIZAÇÃO
            editMode.style.display = 'none';
            viewMode.style.display = 'block';
            
            // AÇÃO: Mostra a caixa de estatísticas novamente
            if (statsBox) {
                statsBox.style.display = 'block';
            }
        }
    }

    // --- EVENT LISTENERS ---
    
    // 1. Ao clicar no LÁPIS (iniciar a edição)
    if (editButton) {
        editButton.addEventListener('click', (e) => {
            e.preventDefault();
            toggleEditMode();
        });
    }

    // 2. Ao clicar em CANCELAR (voltar para a visualização)
    if (cancelButton) {
        cancelButton.addEventListener('click', (e) => {
            e.preventDefault();
            toggleEditMode();
        });
    }
});