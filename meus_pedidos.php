<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$pedidos = $conn->query("SELECT * FROM pedidos WHERE usuario_id = $usuario_id ORDER BY data_pedido DESC");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meus Pedidos - Emporium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">☕ Emporium</a>
            <a href="index.php" class="btn btn-outline-light btn-sm">Continuar Comprando</a>
        </div>
    </nav>

    <div class="container py-5">
        <h2 class="mb-4">Meus Pedidos Registrados</h2>
        <?php if ($pedidos->num_rows == 0): ?>
            <div class="alert alert-info">Você ainda não realizou nenhum pedido.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Nº do Pedido</th>
                            <th>Data</th>
                            <th>Forma de Pagamento</th>
                            <th>Valor Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($p = $pedidos->fetch_assoc()): ?>
                            <tr>
                                <td>#<?= $p['id']; ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($p['data_pedido'])); ?></td>
                                <td><?= $p['forma_pagamento']; ?></td>
                                <td>R$ <?= number_format($p['valor_total'], 2, ',', '.'); ?></td>
                                <td><span class="badge bg-warning text-dark"><?= $p['status']; ?></span></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>