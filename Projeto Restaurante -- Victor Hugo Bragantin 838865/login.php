<?php
// Inicia a sessão
session_start();

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

// Processa o formulário de login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Consulta o banco de dados para verificar o usuário
    $sql = "SELECT * FROM tb_usuario WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o usuário existe
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        // Verifica a senha
        if (password_verify($senha, $usuario['senha'])) {
            // Define a sessão do usuário
            $_SESSION['idUsuario'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            
            // Redireciona para o menu.php
            header("Location: menu.php");
            exit();
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Usuário não encontrado.";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <!-- Adiciona o logotipo acima do formulário de login -->
        <img src="imagens/logo.png" alt="Logotipo" class="logo">

        <h2>Login</h2>
        <?php if (isset($erro)) echo "<div class='erro'>$erro</div>"; ?>
        
        <form method="POST" action="login.php">
            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" required>
            
            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required>
            
            <button type="submit">Entrar</button>
            
            <!-- Link para a página de redefinição de senha -->
            <p><a href="redefinir_senha.php">Esqueci minha senha</a></p>
            
            <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
        </form>
    </div>
</body>
</html>



