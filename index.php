<?php
include 'conexao.php';
$sql = "SELECT p.*, c.nome as categoria_nome FROM produtos p INNER JOIN categorias c ON p.categoria_id = c.id";
$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emporium - Cafés, Grãos e Ervas</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">☕ Emporium Grãos & Ervas</a>
            <div class="d-flex">
                <a href="login.php" class="btn btn-outline-light me-2">Login / Cadastro</a>
                <a href="carrinho.php" class="btn btn-warning">🛒 Carrinho</a>
            </div>
        </div>
    </nav>

    <!-- Header / Banner -->
    <header class="bg-secondary text-white text-center py-5 mb-4">
        <div class="container">
            <h1 class="fw-light">Da Natureza direto para sua xícara</h1>
            <p class="lead text-white-50">Explore nossa seleção exclusiva de cafés especiais, grãos e ervas aromáticas.</p>
        </div>
    </header>

    <!-- Vitrine -->
    <div class="container">
        <div class="row">
            <?php while($produto = $resultado->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="bg-light text-center py-5 border-bottom">
                            <span class="text-muted"><img [Imagem: <?= $produto['imagem']; ?>]></span>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-success mb-2 align-self-start"><?= $produto['categoria_nome']; ?></span>
                            <h5 class="card-title"><?= $produto['nome']; ?></h5>
                            <p class="card-text text-muted small"><?= $produto['descricao']; ?></p>
                            <div class="mt-auto">
                                <h4 class="text-primary mb-3">R$ <?= number_format($produto['preco'], 2, ',', '.'); ?></h4>
                                <a href="carrinho.php?acao=add&id=<?= $produto['id']; ?>" class="btn btn-dark w-100">Comprar / Adicionar</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>