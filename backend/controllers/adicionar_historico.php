<?php
session_start();
include '../../backend/includes/db.php';

if (!isset($_SESSION['usuario_id'])) {
    die("Acesso negado.");
}

$chamado_id = $_POST['chamado_id'];
$nova_descricao = $_POST['nova_descricao'];

// Verifica se o chamado pertence ao usuário logado
$stmt = $pdo->prepare("SELECT id FROM chamados WHERE id = ? AND usuario_id = ?");
$stmt->execute([$chamado_id, $_SESSION['usuario_id']]);
if (!$stmt->fetch()) {
    die("Chamado não encontrado ou você não tem permissão para editá-lo.");
}

// Insere a nova descrição no histórico
$stmt = $pdo->prepare("INSERT INTO historico_chamado (chamado_id, descricao) VALUES (?, ?)");
$stmt->execute([$chamado_id, $nova_descricao]);

echo "Descrição adicionada com sucesso!";
?>