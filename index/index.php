<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma de Chamados de TI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            width: 100%;
            max-width: 400px; /* Largura máxima do card */
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            font-size: 1.5rem; /* Fonte menor para o título */
            margin-bottom: 20px;
            text-align: center;
        }
        .card-text {
            font-size: 1rem; /* Fonte menor para o texto */
            margin-bottom: 20px;
            text-align: center;
        }
        .cta-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .cta-buttons .btn {
            width: 100%;
            font-size: 0.9rem; /* Fonte menor para os botões */
            padding: 8px 12px; /* Tamanho menor para os botões */
        }
        .how-to-use {
            margin-top: 20px;
        }
        .how-to-use h2 {
            font-size: 1.2rem; /* Fonte menor para o título da seção */
            text-align: center;
            margin-bottom: 15px;
        }
        .how-to-use h3 {
            font-size: 1rem; /* Fonte menor para os subtítulos */
            margin-bottom: 10px;
        }
        .how-to-use p {
            font-size: 0.9rem; /* Fonte menor para o texto */
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1 class="card-title">Bem-vindo à Plataforma de Chamados de TI</h1>
        <p class="card-text">
            Aqui você pode registrar problemas técnicos, sugestões ou incidentes, e acompanhar o status dos seus chamados.
            Nossa equipe de suporte está pronta para ajudar!
        </p>

        <!-- Botões de Login e Cadastro -->
        <div class="cta-buttons">
        <a href="http://localhost/chamados_ti/frontend/pages/login.php" class="btn btn-primary btn-lg">Login</a>
        <a href="http://localhost/chamados_ti/frontend/pages/cadastro.php" class="btn btn-secondary btn-lg">Cadastro</a>
        </div>

        <!-- Seção de Como Usar o Sistema -->
        <div class="how-to-use">
            <h2>Como Usar o Sistema</h2>
            <div>
                <h3>1. Cadastre-se</h3>
                <p>
                    Crie uma conta para acessar a plataforma. Preencha seus dados e valide seu e-mail.
                </p>
            </div>
            <div>
                <h3>2. Faça Login</h3>
                <p>
                    Acesse sua conta com seu e-mail e senha para abrir ou acompanhar chamados.
                </p>
            </div>
            <div>
                <h3>3. Abra um Chamado</h3>
                <p>
                    Descreva o problema, anexe arquivos (se necessário) e acompanhe o status do chamado.
                </p>
            </div>
        </div>
    </div>
</body>
</html>