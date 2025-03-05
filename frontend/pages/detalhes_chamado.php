<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['usuario_id'])) {
    die("Acesso negado. Faça login para continuar.");
}

if (!isset($_GET['id'])) {
    die("ID do chamado não especificado.");
}

$chamado_id = $_GET['id'];
$usuario_id = $_SESSION['usuario_id'];

include '../../backend/includes/db.php';

// Busca os detalhes do chamado
$stmt = $pdo->prepare("SELECT id, descricao, tipo_incidente, data_abertura FROM chamados WHERE id = ? AND usuario_id = ?");
$stmt->execute([$chamado_id, $usuario_id]);
$chamado = $stmt->fetch();

if (!$chamado) {
    die("Chamado não encontrado ou você não tem permissão para visualizá-lo.");
}

// Busca os anexos do chamado
$stmt = $pdo->prepare("SELECT id, arquivo_base64, tipo_mime, nome_arquivo FROM anexos WHERE chamado_id = ?");
$stmt->execute([$chamado_id]);
$anexos = $stmt->fetchAll();

// Busca os contatos do chamado
$stmt = $pdo->prepare("SELECT nome, telefone, observacao FROM contatos_chamado WHERE chamado_id = ?");
$stmt->execute([$chamado_id]);
$contatos = $stmt->fetchAll();

// Busca o histórico do chamado
$stmt = $pdo->prepare("SELECT descricao, data_evento FROM historico_chamado WHERE chamado_id = ? ORDER BY data_evento DESC");
$stmt->execute([$chamado_id]);
$historico = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Chamado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Plataforma de Chamados</a>
            <div class="navbar-nav">
                <a class="nav-link" href="dashboard.php">Dashboard</a>
                <a class="nav-link" href="http://localhost/chamados_ti/backend/controllers/logout.php">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Detalhes do Chamado #<?= $chamado['id'] ?></h2>
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Tipo de Incidente: <?= $chamado['tipo_incidente'] ?></h5>
                <p class="card-text"><strong>Descrição:</strong></p>
                <div class="border p-3 mb-3"><?= $chamado['descricao'] ?></div>
                <p class="card-text"><small class="text-muted">Aberto em: <?= $chamado['data_abertura'] ?></small></p>
            </div>
        </div>

        <!-- Anexos -->
        <div class="mt-4">
            <h4>Anexos</h4>
            <?php if (empty($anexos)): ?>
                <div class="alert alert-info">Nenhum anexo encontrado.</div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($anexos as $anexo): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <a href="data:<?= $anexo['tipo_mime'] ?>;base64,<?= $anexo['arquivo_base64'] ?>" download="anexo_<?= $anexo['id'] ?>.<?= pathinfo($anexo['nome_arquivo'], PATHINFO_EXTENSION) ?>" class="btn btn-primary btn-sm">
                                        Baixar Anexo
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Formulário para adicionar novos anexos -->
        <div class="mt-4">
            <h4>Adicionar Novos Anexos</h4>
            <form id="formNovoAnexo" enctype="multipart/form-data">
                <input type="hidden" name="chamado_id" value="<?= $chamado['id'] ?>">
                <div class="mb-3">
                    <label for="novos_anexos" class="form-label">Selecione os arquivos</label>
                    <input type="file" class="form-control" id="novos_anexos" name="novos_anexos[]" multiple required>
                </div>
                <button type="submit" class="btn btn-primary">Enviar Anexos</button>
            </form>
        </div>

        <!-- Contatos -->
        <div class="mt-4">
            <h4>Contatos</h4>
            <?php if (empty($contatos)): ?>
                <div class="alert alert-info">Nenhum contato encontrado.</div>
            <?php else: ?>
                <div class="list-group">
                    <?php foreach ($contatos as $contato): ?>
                        <div class="list-group-item">
                            <strong>Nome:</strong> <?= $contato['nome'] ?><br>
                            <strong>Telefone:</strong> <?= $contato['telefone'] ?><br>
                            <strong>Observação:</strong> <?= $contato['observacao'] ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Histórico (Timeline) -->
        <div class="mt-4">
            <h4>Histórico do Chamado</h4>
            <?php if (empty($historico)): ?>
                <div class="alert alert-info">Nenhum evento registrado.</div>
            <?php else: ?>
                <ul class="list-group">
                    <?php foreach ($historico as $evento): ?>
                        <li class="list-group-item">
                            <strong><?= $evento['data_evento'] ?>:</strong> <?= $evento['descricao'] ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <!-- Botão para adicionar nova descrição ao histórico -->
        <div class="mt-4">
            <h4>Adicionar Nova Descrição</h4>
            <form id="formHistorico">
                <input type="hidden" name="chamado_id" value="<?= $chamado_id ?>">
                <div class="mb-3">
                    <textarea class="form-control" name="nova_descricao" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Adicionar</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Adiciona nova descrição ao histórico
            $('#formHistorico').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    url: '../../backend/controllers/adicionar_historico.php',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        alert(response);
                        location.reload(); // Recarrega a página para exibir o novo histórico
                    }
                });
            });

            // Submissão do formulário de novos anexos
            $('#formNovoAnexo').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: '../../backend/controllers/adicionar_anexo.php',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert(response);
                        location.reload(); // Recarrega a página para exibir os novos anexos
                    },
                    error: function(xhr, status, error) {
                        alert("Erro ao adicionar anexos: " + xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>