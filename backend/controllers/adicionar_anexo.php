<?php
session_start();
include '../../backend/includes/db.php';

if (!isset($_SESSION['usuario_id'])) {
    die("Acesso negado.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Método não permitido.");
}

try {
    $chamado_id = $_POST['chamado_id'];
    $usuario_id = $_SESSION['usuario_id'];

    // Verifica se o chamado pertence ao usuário ou se o usuário tem permissão
    $stmt = $pdo->prepare("SELECT id FROM chamados WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$chamado_id, $usuario_id]);
    $chamado = $stmt->fetch();

    if (!$chamado) {
        die("Chamado não encontrado ou você não tem permissão para adicionar anexos.");
    }

    // Processa os novos anexos
    if (!empty($_FILES['novos_anexos']['name'][0])) {
        $tipos_permitidos = ['image/jpeg', 'image/png', 'application/pdf'];
        foreach ($_FILES['novos_anexos']['tmp_name'] as $key => $tmp_name) {
            $arquivo_nome = $_FILES['novos_anexos']['name'][$key]; // Nome original do arquivo
            $arquivo_tipo_mime = $_FILES['novos_anexos']['type'][$key]; // Tipo MIME do arquivo

            // Verifica se o tipo de arquivo é permitido
            if (!in_array($arquivo_tipo_mime, $tipos_permitidos)) {
                die("Tipo de arquivo não permitido: " . $arquivo_tipo_mime);
            }

            // Converte o arquivo para base64
            $arquivo_base64 = base64_encode(file_get_contents($tmp_name));

            // Insere o novo anexo no banco de dados
            $stmt = $pdo->prepare("INSERT INTO anexos (chamado_id, arquivo_base64, tipo_mime, nome_arquivo) VALUES (?, ?, ?, ?)");
            $stmt->execute([$chamado_id, $arquivo_base64, $arquivo_tipo_mime, $arquivo_nome]);
        }
    }

    echo "Anexos adicionados com sucesso!";
} catch (Exception $e) {
    die("Erro ao adicionar anexos: " . $e->getMessage());
}
?>