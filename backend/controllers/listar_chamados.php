<?php
session_start();
error_reporting(E_ALL); // Exibe todos os erros
ini_set('display_errors', 1); // Garante que os erros sejam exibidos

if (!isset($_SESSION['usuario_id'])) {
    die("Acesso negado.");
}

include '../../backend/includes/db.php'; // Verifique esse caminho

$usuario_id = $_SESSION['usuario_id'];

// Busca os chamados do usuário
$stmt = $pdo->prepare("SELECT id, descricao, tipo_incidente, data_abertura FROM chamados WHERE usuario_id = ? ORDER BY data_abertura DESC");
$stmt->execute([$usuario_id]);
$chamados = $stmt->fetchAll();

if (empty($chamados)) {
    echo "<div class='alert alert-info'>Nenhum chamado encontrado.</div>";
} else {
    foreach ($chamados as $chamado) {
        echo "
        <div class='card mb-3'>
            <div class='card-body'>
                <h5 class='card-title'>Chamado #{$chamado['id']}</h5>
                <p class='card-text'><strong>Tipo:</strong> {$chamado['tipo_incidente']}</p>
                <p class='card-text'><strong>Descrição:</strong> {$chamado['descricao']}</p>
                <p class='card-text'><small class='text-muted'>Aberto em: {$chamado['data_abertura']}</small></p>
                <a href='detalhes_chamado.php?id={$chamado['id']}' class='btn btn-primary btn-sm'>Ver Detalhes</a>
            </div>
        </div>";
    }
}
?>