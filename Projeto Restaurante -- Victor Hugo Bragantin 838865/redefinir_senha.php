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

// Processa o formulário de redefinição de senha
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $novaSenha = $_POST['novaSenha'];

    // Verifica se o e-mail existe no banco de dados
    $sql = "SELECT * FROM tb_usuario WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // E-mail encontrado, atualiza a senha
        $hashSenha = password_hash($novaSenha, PASSWORD_DEFAULT); // Criptografa a nova senha
        $sqlUpdate = "UPDATE tb_usuario SET senha = ? WHERE email = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("ss", $hashSenha, $email);
        
        if ($stmtUpdate->execute()) {
            $mensagem = "Senha redefinida com sucesso.";

            // Redireciona para a tela de login após 2 segundos
            header("refresh:2;url=login.php");
            echo "<div class='sucesso'>$mensagem</div>";
            exit();
        } else {
            $erro = "Erro ao redefinir a senha. Tente novamente.";
        }

        $stmtUpdate->close();
    } else {
        $erro = "E-mail não encontrado.";
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
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="redefinir_senha.css">
</head>
<body>
    <div class="reset-container">
        <h2>Redefinir Senha</h2>
        <?php
        if (isset($erro)) echo "<div class='erro'>$erro</div>";
        ?>
        
        <form method="POST" action="redefinir_senha.php">
            <label for="email">Digite seu e-mail:</label>
            <input type="email" name="email" id="email" required>
            
            <label for="novaSenha">Digite a nova senha:</label>
            <input type="password" name="novaSenha" id="novaSenha" required>
            
            <button type="submit">Redefinir Senha</button>
        </form>
    </div>
</body>
</html>


