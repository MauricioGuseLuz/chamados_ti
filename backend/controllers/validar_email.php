<?php
session_start();
include '../../backend/includes/db.php';

// Recebe o token de validação da URL
$token = $_GET['token'];

if (empty($token)) {
    echo "Token inválido.";
    exit();
}

// Busca o usuário com o token fornecido
$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE token_validacao = ?");
$stmt->execute([$token]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Token inválido ou expirado.";
    exit();
}

// Atualiza o status do usuário para "validado" e remove o token
$stmt = $pdo->prepare("UPDATE usuarios SET validado = 1, token_validacao = NULL WHERE id = ?");
$stmt->execute([$usuario['id']]);

// Redireciona para a página de login com uma mensagem de sucesso
header("Location: http://localhost/chamados_ti/frontend/pages/login.php?status=success&message=E-mail validado com sucesso! Agora você pode fazer login.");
exit();
?>