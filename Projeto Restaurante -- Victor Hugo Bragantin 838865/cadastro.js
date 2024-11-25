document.getElementById('formCadastro').addEventListener('submit', function(event) {
    let nome = document.getElementById('nome').value;
    let email = document.getElementById('email').value;
    let senha = document.getElementById('senha').value;
    let confirmarSenha = document.getElementById('confirmar_senha').value;

    if (!nome || !email || !senha || !confirmarSenha) {
        alert("Todos os campos são obrigatórios.");
        event.preventDefault();
        return;
    }

    // Validação do e-mail
    let emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!emailRegex.test(email)) {
        alert("Por favor, insira um e-mail válido.");
        event.preventDefault();
        return;
    }

    // Verifica se as senhas coincidem
    if (senha !== confirmarSenha) {
        alert("As senhas não coincidem.");
        event.preventDefault();
        return;
    }
});
