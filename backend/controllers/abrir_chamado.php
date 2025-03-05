<?php
session_start();
include '../../backend/includes/db.php';

if (!isset($_SESSION['usuario_id'])) {
    die("Acesso negado.");
}

try {
    $usuario_id = $_SESSION['usuario_id'];
    $tipo_incidente = $_POST['tipo_incidente'];
    $descricao = $_POST['descricao'];

    // Insere o chamado no banco de dados
    $stmt = $pdo->prepare("INSERT INTO chamados (usuario_id, descricao, tipo_incidente) VALUES (?, ?, ?)");
    $stmt->execute([$usuario_id, $descricao, $tipo_incidente]);
    $chamado_id = $pdo->lastInsertId();

    // Processa os anexos
    if (!empty($_FILES['arquivo']['name'][0])) {
        $tipos_permitidos = ['image/jpeg', 'image/png', 'application/pdf'];
        foreach ($_FILES['arquivo']['tmp_name'] as $key => $tmp_name) {
            $arquivo_nome = $_FILES['arquivo']['name'][$key]; // Nome original do arquivo
            $arquivo_tipo_mime = $_FILES['arquivo']['type'][$key]; // Tipo MIME do arquivo

            // Verifica se o tipo de arquivo é permitido
            if (!in_array($arquivo_tipo_mime, $tipos_permitidos)) {
                die("Tipo de arquivo não permitido: " . $arquivo_tipo_mime);
            }

            // Converte o arquivo para base64
            $arquivo_base64 = base64_encode(file_get_contents($tmp_name));

            // Insere o anexo no banco de dados
            $stmt = $pdo->prepare("INSERT INTO anexos (chamado_id, arquivo_base64, tipo_mime, nome_arquivo) VALUES (?, ?, ?, ?)");
            $stmt->execute([$chamado_id, $arquivo_base64, $arquivo_tipo_mime, $arquivo_nome]);
        }
    }

    // Processa os contatos
    if (!empty($_POST['contato_nome'])) {
        foreach ($_POST['contato_nome'] as $key => $nome) {
            $telefone = $_POST['contato_telefone'][$key];
            $observacao = $_POST['contato_observacao'][$key];
            $stmt = $pdo->prepare("INSERT INTO contatos_chamado (chamado_id, nome, telefone, observacao) VALUES (?, ?, ?, ?)");
            $stmt->execute([$chamado_id, $nome, $telefone, $observacao]);
        }
    }

    echo "Chamado aberto com sucesso!";
} catch (Exception $e) {
    die("Erro ao abrir chamado: " . $e->getMessage());
}
?>