<?php
session_start();
include 'conexao.php';

$erro = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $acao = $_POST['acao'] ?? '';
    
    if ($acao == 'login') {
        $email = $conn->real_escape_string($_POST['email']);
        $senha = $_POST['senha'];
        
        $sql = "SELECT * FROM usuarios WHERE email = '$email'";
        $res = $conn->query($sql);
        if ($res->num_rows > 0) {
            $user = $res->fetch_assoc();
            if (password_verify($senha, $user['senha'])) {
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['usuario_nome'] = $user['nome'];
                $_SESSION['usuario_perfil'] = $user['perfil'];
                header("Location: index.php");
                exit;
            } else {
                $erro = "Senha incorreta!";
            }
        } else {
            $erro = "Usuário não encontrado!";
        }
    } elseif ($acao == 'cadastro') {
        $nome = $conn->real_escape_string($_POST['nome']);
        $email = $conn->real_escape_string($_POST['email']);
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO usuarios (nome, email, senha, perfil) VALUES ('$nome', '$email', '$senha', 'cliente')";
        if ($conn->query($sql)) {
            echo "<script>alert('Cadastro realizado com sucesso! Faça login.'); window.location='login.php';</script>";
        } else {
            $erro = "Erro ao cadastrar (E-mail já pode estar em uso).";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login / Cadastro - Emporium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Ícones FontAwesome para o logo do Google -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php if($erro): ?>
                    <div class="alert alert-danger"><?= $erro; ?></div>
                <?php endif; ?>
                
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4">Acesse sua Conta</h3>
                        
                        <!-- Botão de Login com Google (Demonstração / Integração OAuth) -->
                        <a href="login_google_mock.php" class="btn btn-outline-danger w-100 mb-3 py-2">
                            <i class="fab fa-google me-2"></i> Continuar com o Google
                        </a>
                        
                        <div class="text-center text-muted mb-3"><span>ou utilize seu e-mail</span></div>

                        <!-- Abas para alternar entre Entrar e Cadastrar -->
                        <ul class="nav nav-tabs mb-3" id="authTab" role="tablist">
                            <li class="nav-item w-50 text-center"><button class="nav-link active w-100" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-pane" type="button">Entrar</button></li>
                            <li class="nav-item w-50 text-center"><button class="nav-link w-100" id="cadastro-tab" data-bs-toggle="tab" data-bs-target="#cadastro-pane" type="button">Cadastrar</button></li>
                        </ul>

                        <div class="tab-content" id="authTabContent">
                            <!-- Painel Login -->
                            <div class="tab-pane fade show active" id="login-pane">
                                <form method="POST">
                                    <input type="hidden" name="acao" value="login">
                                    <div class="mb-3">
                                        <label>E-mail</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Senha</label>
                                        <input type="password" name="senha" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-dark w-100">Entrar</button>
                                </form>
                            </div>
                            
                            <!-- Painel Cadastro -->
                            <div class="tab-pane fade" id="cadastro-pane">
                                <form method="POST">
                                    <input type="hidden" name="acao" value="cadastro">
                                    <div class="mb-3">
                                        <label>Nome Completo</label>
                                        <input type="text" name="nome" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>E-mail</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Senha</label>
                                        <input type="password" name="senha" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">Criar Conta</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="index.php" class="text-decoration-none text-muted">← Voltar para a Loja</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>