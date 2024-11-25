<?php

// Conexão com o banco de dados
$host = 'localhost';
$dbname = 'projeto_final';
$username = 'root';
$password = '';
$conn = new mysqli($host, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pegando os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $data_nascimento = $_POST['data_nascimento'];
    $telefone = $_POST['telefone'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografando a senha
    $cep = $_POST['cep'];
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];
    $bairro = $_POST['bairro'];
    $complemento = $_POST['complemento'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];

    // Insere os dados no banco
    $sql = "INSERT INTO tb_usuario (nome, email, data_nascimento, telefone, senha, cep, rua, numero, bairro, complemento, cidade, estado)
            VALUES ('$nome', '$email', '$data_nascimento', '$telefone', '$senha', '$cep', '$rua', '$numero', '$bairro', '$complemento', '$cidade', '$estado')";

    if ($conn->query($sql) === TRUE) {
        // Exibe mensagem de sucesso
        echo "<script>alert('Cadastro realizado com sucesso! Você será redirecionado para a página de login.');</script>";

        // Redireciona após 3 segundos para a página de login
        echo "<script>setTimeout(function(){ window.location.href = 'login.php'; }, 3000);</script>";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }

    // Fecha a conexão
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="cadastro.css">
</head>
<body>
    <div class="container">
        <h2>Cadastro de Usuário</h2>
        <form method="POST" action="cadastro.php">
            <!-- Campos do formulário -->
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>
    
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
    
            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" id="data_nascimento" name="data_nascimento">
    
            <label for="telefone">Telefone:</label>
            <input type="tel" id="telefone" name="telefone">
    
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
    
            <label for="senha_confirmacao">Confirmação de Senha:</label>
            <input type="password" id="senha_confirmacao" name="senha_confirmacao" required>
    
            <h3>Endereço</h3>
            <label for="cep">CEP:</label>
            <input type="text" id="cep" name="cep">
    
            <label for="rua">Rua:</label>
            <input type="text" id="rua" name="rua">
    
            <label for="numero">Número:</label>
            <input type="text" id="numero" name="numero">
    
            <label for="bairro">Bairro:</label>
            <input type="text" id="bairro" name="bairro">
    
            <label for="complemento">Complemento:</label>
            <input type="text" id="complemento" name="complemento">
    
            <label for="cidade">Cidade:</label>
            <input type="text" id="cidade" name="cidade">
    
            <label for="estado">Estado:</label>
            <input type="text" id="estado" name="estado">
    
            <button type="submit">Cadastrar</button>
        </form>
    </div>
    <script src="cadastro.js"></script>


</body>
</html>