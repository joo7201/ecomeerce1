<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Simulando carrinho temporário na sessão (caso o usuário adicione itens)
if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    // Para teste rápido, injetamos um item padrão se o carrinho estiver vazio
    $_SESSION['carrinho'] = [1 => 2]; // Produto ID 1, quantidade 2
}

// Calcular total do pedido
$total = 0;
$itens_detalhes = [];
foreach ($_SESSION['carrinho'] as $produto_id => $qtd) {
    $res = $conn->query("SELECT * FROM produtos WHERE id = $produto_id");
    if ($p = $res->fetch_assoc()) {
        $subtotal = $p['preco'] * $qtd;
        $total += $subtotal;
        $itens_detalhes[] = ['id' => $p['id'], 'nome' => $p['nome'], 'preco' => $p['preco'], 'qtd' => $qtd];
    }
}

$pedido_finalizado = false;
$pedido_id = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirmar_pedido'])) {
    // Inserir Pedido
    $conn->query("INSERT INTO pedidos (usuario_id, valor_total, forma_pagamento, status) VALUES ($usuario_id, $total, 'PIX', 'Aguardando Pagamento')");
    $pedido_id = $conn->insert_id;
    
    // Inserir Itens do Pedido e baixar estoque
    foreach ($itens_detalhes as $item) {
        $pid = $item['id'];
        $qtd = $item['qtd'];
        $preco = $item['preco'];
        $conn->query("INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario) VALUES ($pedido_id, $pid, $qtd, $preco)");
        $conn->query("UPDATE produtos SET estoque = estoque - $qtd WHERE id = $pid");
    }
    
    // Limpar carrinho
    unset($_SESSION['carrinho']);
    $pedido_finalizado = true;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Checkout e Pagamento PIX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php if(!$pedido_finalizado): ?>
                    <div class="card shadow">
                        <div class="card-body p-4">
                            <h3 class="mb-4">Finalizar Pedido</h3>
                            <h5>Resumo da Compra</h5>
                            <ul class="list-group mb-4">
                                <?php foreach($itens_detalhes as $item): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?= $item['nome']; ?> (Qtd: <?= $item['qtd']; ?>)
                                        <span>R$ <?= number_format($item['preco'] * $item['qtd'], 2, ',', '.'); ?></span>
                                    </li>
                                <?php endforeach; ?>
                                <li class="list-group-item d-flex justify-content-between bg-light fw-bold">
                                    Total a Pagar:
                                    <span class="text-success">R$ <?= number_format($total, 2, ',', '.'); ?></span>
                                </li>
                            </ul>

                            <form method="POST">
                                <h5 class="mb-3">Forma de Pagamento</h5>
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="radio" checked id="pix">
                                    <label class="form-check-label fw-bold text-success" for="pix">
                                        ⚡ PIX (Aprovação Instantânea - Demonstração)
                                    </label>
                                </div>
                                <button type="submit" name="confirmar_pedido" class="btn btn-success w-100 py-2">Gerar QR Code PIX</button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- TELA DO PIX (DEMONSTRAÇÃO) -->
                    <div class="card shadow text-center p-4">
                        <div class="card-body">
                            <h3 class="text-success mb-3">Pedido #<?= $pedido_id; ?> Registrado com Sucesso!</h3>
                            <p class="text-muted">Escaneie o QR Code abaixo com o aplicativo do seu banco para pagar via PIX (Demonstração)</p>
                            
                            <!-- Imagem Ilustrativa de QR Code PIX -->
                            <div class="my-3">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=00020126580014br.gov.bcb.pix...pedido_id_<?= $pedido_id ?>" alt="QR Code PIX" class="border p-2 bg-white shadow-sm">
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">PIX Copia e Cola:</label>
                                <input type="text" class="form-control text-center font-monospace" readonly value="00020126580014br.gov.bcb.pix0136123e4567-e89b-12d3-a456-4266141740005204000053039865802BR5925EMPORIUM GRAOS E ERVAS6009SAO PAULO62070503***63041D3A">
                            </div>

                            <a href="meus_pedidos.php" class="btn btn-dark mt-2">Ver Meus Pedidos Registrados</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>