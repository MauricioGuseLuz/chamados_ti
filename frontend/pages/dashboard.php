<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['usuario_id'])) {
    die("Acesso negado. Faça login para continuar.");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Plataforma de Chamados</a>
            <div class="navbar-nav">
                <a class="nav-link" href="abrir_chamado.php">Abrir Chamado</a>
                <a class="nav-link" href="http://localhost/chamados_ti/backend/controllers/logout.php">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Meus Chamados</h2>
        <div id="listaChamados" class="mt-3">
            <!-- Lista de chamados será carregada aqui via AJAX -->
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Carrega a lista de chamados ao abrir a página
            function carregarChamados() {
                $.ajax({
                    url: '../../backend/controllers/listar_chamados.php',
                    method: 'GET',
                    success: function(response) {
                        $('#listaChamados').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error("Erro na requisição AJAX:", error);
                        $('#listaChamados').html("<div class='alert alert-danger'>Erro ao carregar chamados.</div>");
                    }
                });
            }

            carregarChamados();
        });
    </script>
</body>
</html>