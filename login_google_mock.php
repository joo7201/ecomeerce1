<?php
session_start();
include 'conexao.php';

// Simulando dados retornados pelo Google OAuth após o clique
$google_email = "cliente.google@gmail.com";
$google_nome = "Cliente Google Demo";
$google_id = "g_id_987654321";

$sql = "SELECT * FROM usuarios WHERE email = '$google_email'";
$res = $conn->query($sql);

if ($res->num_rows > 0) {
    $user = $res->fetch_assoc();
} else {
    // Cadastra automaticamente se for o primeiro login com Google
    $conn->query("INSERT INTO usuarios (nome, email, google_id, perfil) VALUES ('$google_nome', '$google_email', '$google_id', 'cliente')");
    $user_id = $conn->insert_id;
    $user = ['id' => $user_id, 'nome' => $google_nome, 'perfil' => 'cliente'];
}

$_SESSION['usuario_id'] = $user['id'];
$_SESSION['usuario_nome'] = $user['nome'];
$_SESSION['usuario_perfil'] = $user['perfil'];

header("Location: index.php");
exit;
?>