<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['idUsuario'])) {
    echo "Você precisa estar logado para fazer um pedido.";
    exit();
}

$host = 'localhost';
$dbname = 'projeto_final';
$username = 'root';
$password = '';
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se os dados do pedido foram enviados via POST
if (isset($_POST['idItem']) && isset($_POST['quantidade']) && isset($_POST['preco'])) {
    $idUsuario = $_SESSION['idUsuario'];
    $idItem = $_POST['idItem'];
    $quantidade = $_POST['quantidade'];
    $preco = $_POST['preco'];

    $sql = "INSERT INTO tb_itens_pedido (idUsuario, idItem, quantidade, preco, finalizado) 
            VALUES (?, ?, ?, ?, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiid", $idUsuario, $idItem, $quantidade, $preco);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Item adicionado ao pedido com sucesso!";
        header("Location: menu.php"); // Redireciona para a página menu.php
        exit(); // Encerra o script após o redirecionamento
    } else {
        echo "Erro ao adicionar o item ao pedido.";
    }

    $stmt->close();
} else {
    echo "Dados inválidos.";
}

$conn->close();
?>
