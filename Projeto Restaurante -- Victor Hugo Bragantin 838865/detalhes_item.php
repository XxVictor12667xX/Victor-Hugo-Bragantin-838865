
<?php
session_start(); // Inicia a sessão

// Dados de conexão com o banco de dados
$host = 'localhost';
$dbname = 'projeto_final';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if (isset($_GET['idItem'])) {
    $idItem = $_GET['idItem'];

    $sql = "SELECT * FROM tb_itens WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idItem);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
    } else {
        die("Item não encontrado.");
    }

    $stmt->close();
} else {
    die("ID do item não especificado.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Item</title>
    <link rel="stylesheet" href="detalhes_item.css">
</head>
<body>
    <div class="item-details">
        <?php if (isset($item)): ?>
            <h1><?php echo htmlspecialchars($item['nome']); ?></h1>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($item['foto']); ?>" alt="<?php echo htmlspecialchars($item['nome']); ?>" class="item-image">
            <p><strong>Descrição:</strong> <?php echo nl2br(htmlspecialchars($item['descricao'])); ?></p>
            <p><strong>Preço:</strong> R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></p>

            <form action="adicionar_pedido.php" method="POST">
                <input type="hidden" name="idItem" value="<?php echo $item['id']; ?>">
                <input type="hidden" name="preco" value="<?php echo $item['preco']; ?>">
                <label for="quantidade">Quantidade:</label>
                <input type="number" name="quantidade" id="quantidade" value="1" min="1" required>
                <button type="submit">Adicionar ao Pedido</button>
            </form>
        <?php else: ?>
            <p>Item não encontrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>
