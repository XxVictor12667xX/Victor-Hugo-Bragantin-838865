<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php"); // Redireciona para a página de login
    exit();
}

// Dados de conexão com o banco de dados
$host = 'localhost';
$dbname = 'projeto_final';
$username = 'root';
$password = '';
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Recuperar categorias do banco
$sqlCategorias = "SELECT * FROM tb_categorias";
$resultadoCategorias = $conn->query($sqlCategorias);

if ($resultadoCategorias && $resultadoCategorias->num_rows > 0) {
    $categorias = $resultadoCategorias->fetch_all(MYSQLI_ASSOC); // Atribui o resultado à variável
} else {
    $categorias = []; // Caso não haja categorias, define como um array vazio
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu do Restaurante</title>
    <link rel="stylesheet" href="menu.css">
</head>
<body>
    <header>
        <h1>Menu do Restaurante</h1>
        <!-- Botão para acessar o resumo do pedido -->
        <a href="resumo_pedido.php" class="btn-resumo-pedido">Ver Resumo do Pedido</a>
    </header>

    <div class="container">
        <?php if (count($categorias) > 0): ?>
            <?php foreach ($categorias as $categoria): ?>
                <div class="category">
                    <h2><?php echo htmlspecialchars($categoria['nome']); ?></h2>
                    <p>Explore nossas deliciosas opções de <?php echo htmlspecialchars($categoria['nome']); ?>.</p>

                    <!-- Listar os itens dentro de cada categoria -->
                    <div class="item-list">
    <?php
    $sqlItens = "SELECT * FROM tb_itens WHERE idCategoria = ?";
    $stmt = $conn->prepare($sqlItens);
    $stmt->bind_param("i", $categoria['id']);
    $stmt->execute();
    $resultadoItens = $stmt->get_result();

    if ($resultadoItens && $resultadoItens->num_rows > 0) {
        while ($item = $resultadoItens->fetch_assoc()) {
            echo '<div class="item">';
            // Definindo a imagem com base no nome do item
            if ($item['nome'] === 'Bruschetta') {
                $imageSrc = 'imagens/bruschetta.jpeg';
            } elseif ($item['nome'] === 'Filé à Parmegiana') {
                $imageSrc = 'imagens/file a parmegiana.jpeg';
            } elseif ($item['nome'] === 'Coca-Cola') {
                $imageSrc = 'imagens/refri.jpeg';
            } elseif ($item['nome'] === 'Torta de Limão') {
                $imageSrc = 'imagens/torta.jpeg';
            } else {
                $imageSrc = 'images/default.jpg'; // imagem padrão, caso o item não tenha uma imagem específica
            }

            echo '<img src="' . htmlspecialchars($imageSrc) . '" alt="' . htmlspecialchars($item['nome']) . '">';
            echo '<h3>' . htmlspecialchars($item['nome']) . '</h3>';
            echo '<p>' . htmlspecialchars($item['descricao']) . '</p>';
            echo '<p class="price">R$ ' . number_format($item['preco'], 2, ',', '.') . '</p>';
            echo '<a href="detalhes_item.php?idItem=' . $item['id'] . '" class="btn">Ver Detalhes</a>';
            echo '</div>';
        }
    }
    $stmt->close();
    ?>
</div>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Não há categorias disponíveis no momento.</p>
        <?php endif; ?>
    </div>

    <?php $conn->close(); // Fechar a conexão com o banco ?>
</body>
</html>


