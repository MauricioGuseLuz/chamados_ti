<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg" style="width: 320px;">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="card-title">Login</h4>
            </div>
            <div class="card-body">
                <!-- Área para exibir mensagens -->
                <div id="mensagem">
                    <?php
                    // Exibe a mensagem de sucesso, se houver
                    if (isset($_GET['status']) && $_GET['status'] === 'success' && isset($_GET['message'])) {
                        echo '<div class="alert alert-success">' . htmlspecialchars($_GET['message']) . '</div>';
                    }
                    ?>
                </div>

                <form id="formLogin">
                    <div class="mb-2">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control form-control-sm" id="email" name="email" required>
                    </div>
                    <div class="mb-2">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control form-control-sm" id="senha" name="senha" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-sm">Entrar</button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se aqui</a>.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#formLogin').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    url: '../../backend/controllers/login.php',
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === "success") {
                            window.location.href = 'dashboard.php'; // Redireciona para a página de dashboard
                        } else {
                            $("#mensagem").html('<div class="alert alert-danger">' + response.message + '</div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Erro na requisição AJAX:", error);
                        $("#mensagem").html('<div class="alert alert-danger">Erro ao tentar fazer login. Tente novamente.</div>');
                    }
                });
            });
        });
    </script>
</body>
</html>