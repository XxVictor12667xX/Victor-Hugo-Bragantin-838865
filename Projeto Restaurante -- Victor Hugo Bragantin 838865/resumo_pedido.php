<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
    exit();
}

// Conexão com o banco de dados
$host = 'localhost';
$dbname = 'projeto_final';
$username = 'root';
$password = '';
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$idUsuario = $_SESSION['idUsuario'];

// Recupera os itens do pedido do usuário
$sql = "SELECT ip.id, i.nome, ip.quantidade, ip.preco, (ip.quantidade * ip.preco) AS total_item
        FROM tb_itens_pedido ip
        JOIN tb_itens i ON ip.idItem = i.id
        WHERE ip.idUsuario = ? AND ip.finalizado = FALSE";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();

$itens = [];
$totalGeral = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $itens[] = $row;
        $totalGeral += $row['total_item'];
    }
}

// Função para alterar a quantidade ou remover um item
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemId = $_POST['id'];
    $acao = $_POST['acao'];

    if ($acao === "remover") {
        $sql = "DELETE FROM tb_itens_pedido WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $itemId);
        $stmt->execute();
        header("Location: resumo_pedido.php");
        exit();
    } elseif ($acao === "alterar" && isset($_POST['nova_quantidade'])) {
        $novaQuantidade = $_POST['nova_quantidade'];
        $sql = "UPDATE tb_itens_pedido SET quantidade = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $novaQuantidade, $itemId);
        $stmt->execute();
        header("Location: menu.php"); // Redireciona para menu.php após a alteração
        exit();
    }
}
// Finalizar pedido
if (isset($_POST['confirmar'])) {
    $sql = "UPDATE tb_itens_pedido SET finalizado = TRUE WHERE idUsuario = ? AND finalizado = FALSE";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    echo "Pedido confirmado com sucesso!";
    header("Location: menu.php"); // Redireciona para a página de menu após confirmar o pedido
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resumo do Pedido</title>
    <link rel="stylesheet" href="resumo_pedido.css">
</head>
<body>
    <h1>Resumo do Pedido</h1>

    <?php if (!empty($itens)): ?>
        <form method="POST" action="resumo_pedido.php">
            <ul>
                <?php foreach ($itens as $item): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($item['nome']); ?></strong><br>
                        Quantidade: 
                        <input type="number" name="nova_quantidade" value="<?php echo $item['quantidade']; ?>" min="1">
                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                        <input type="hidden" name="acao" value="alterar">
                        <button type="submit">Atualizar</button>
                        <button type="submit" name="acao" value="remover">Remover</button><br>
                        Preço Unitário: R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?><br>
                        Preço Total do Item: R$ <?php echo number_format($item['total_item'], 2, ',', '.'); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p><strong>Total Geral: R$ <?php echo number_format($totalGeral, 2, ',', '.'); ?></strong></p>
            <button type="submit" name="confirmar">Confirmar Pedido</button>
        </form>
    <?php else: ?>
        <p>Seu pedido está vazio.</p>
    <?php endif; ?>
</body>
</html>

