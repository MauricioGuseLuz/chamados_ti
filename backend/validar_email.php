<?php
session_start();
include 'includes/db.php';

// Verifica se o token foi passado na URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Busca o usuário com o token
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE token_validacao = ?");
    $stmt->execute([$token]);

    if ($stmt->rowCount() > 0) {
        // Atualiza o campo `validado` para 1 e remove o token
        $stmt = $pdo->prepare("UPDATE usuarios SET validado = 1, token_validacao = NULL WHERE token_validacao = ?");
        $stmt->execute([$token]);

        // Redireciona para a tela de login
        header('Location: http://localhost/chamados_ti/frontend/pages/login.php');
        exit();
    } else {
        echo "Token inválido ou já utilizado.";
    }
} else {
    echo "Token não fornecido.";
}
?>