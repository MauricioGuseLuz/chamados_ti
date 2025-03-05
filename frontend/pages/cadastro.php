<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/styles.css" rel="stylesheet"> <!-- Link para o seu arquivo CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <style>
        /* Estilos personalizados para reduzir o tamanho da tela */
        .card {
            width: 320px; /* Reduzi o tamanho do card */
            padding: 10px; /* Reduzi o padding interno */
        }
        .form-control-sm {
            font-size: 0.875rem; /* Reduzi o tamanho da fonte dos inputs */
        }
        .form-label {
            font-size: 0.875rem; /* Reduzi o tamanho da fonte dos labels */
            margin-bottom: 0.25rem; /* Reduzi o espaçamento abaixo dos labels */
        }
        .btn-sm {
            font-size: 0.875rem; /* Reduzi o tamanho da fonte dos botões */
        }
        .small {
            font-size: 0.75rem; /* Reduzi o tamanho da fonte das mensagens de requisitos */
        }
        .input-group-text {
            font-size: 0.875rem; /* Reduzi o tamanho da fonte dos ícones */
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="card-title">Cadastro</h4>
            </div>
            <div class="card-body">
                <!-- Área para exibir mensagens -->
                <div id="mensagem"></div>

                <!-- Spinner de carregamento -->
                <div id="loading" class="text-center" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i> Processando...
                </div>

                <form id="formCadastro">
                    <div class="mb-1"> <!-- Reduzi o espaçamento entre os campos -->
                        <label for="nome" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control form-control-sm" id="nome" name="nome" required>
                    </div>
                    <div class="mb-1">
                        <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                        <input type="date" class="form-control form-control-sm" id="data_nascimento" name="data_nascimento" required>
                    </div>
                    <div class="mb-1">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control form-control-sm" id="email" name="email" required>
                        <div id="emailErro" class="text-danger small"></div>
                    </div>
                    <div class="mb-1">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control form-control-sm" id="telefone" name="telefone" required>
                    </div>
                    <div class="mb-1">
                        <label for="whatsapp" class="form-label">WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                            <input type="text" class="form-control form-control-sm" id="whatsapp" name="whatsapp" required>
                        </div>
                    </div>
                    <div class="mb-1">
                        <label for="senha" class="form-label">Senha</label>
                        <div class="input-group">
                            <input type="password" class="form-control form-control-sm" id="senha" name="senha" required>
                            <button type="button" class="btn btn-outline-secondary" id="toggleSenha">
                                <i class="fas fa-eye"></i> <!-- Ícone de olho -->
                            </button>
                        </div>
                        <!-- Mensagem de requisitos da senha -->
                        <div id="senhaRequisitos" class="text-muted small mt-1">
                            A senha deve conter:
                            <ul class="mb-0">
                                <li id="reqTamanho">Pelo menos 8 caracteres</li>
                                <li id="reqMaiuscula">Pelo menos uma letra maiúscula</li>
                                <li id="reqNumero">Pelo menos um número</li>
                                <li id="reqEspecial">Pelo menos um caractere especial</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mb-1">
                        <label for="confirmar_senha" class="form-label">Confirmar Senha</label>
                        <div class="input-group">
                            <input type="password" class="form-control form-control-sm" id="confirmar_senha" name="confirmar_senha" required>
                            <button type="button" class="btn btn-outline-secondary" id="toggleConfirmarSenha">
                                <i class="fas fa-eye"></i> <!-- Ícone de olho -->
                            </button>
                        </div>
                    </div>
                    <div class="mb-1">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-control form-control-sm" id="estado" name="estado" required>
                            <option value="">Selecione</option>
                            <option value="SP">São Paulo</option>
                            <option value="RJ">Rio de Janeiro</option>
                            <!-- Adicione mais estados -->
                        </select>
                    </div>
                    <div class="mb-1">
                        <label for="cidade" class="form-label">Cidade</label>
                        <select class="form-control form-control-sm" id="cidade" name="cidade" required>
                            <option value="">Selecione o estado primeiro</option>
                        </select>
                    </div>
                    <div class="d-grid gap-1"> <!-- Reduzi o espaçamento entre os botões -->
                        <button type="submit" class="btn btn-primary btn-sm">Cadastrar</button>
                        <a href="login.php" class="btn btn-secondary btn-sm">Voltar para o Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Máscaras de telefone e WhatsApp
            $('#telefone, #whatsapp').mask('(00) 00000-0000');

            // Carrega as cidades ao selecionar o estado
            $('#estado').change(function() {
                var estado = $(this).val();
                if (estado) {
                    $.ajax({
                        url: '../../backend/controllers/get_cidades.php',
                        method: 'POST',
                        data: { estado: estado },
                        success: function(response) {
                            $('#cidade').html(response);
                        }
                    });
                } else {
                    $('#cidade').html('<option value="">Selecione o estado primeiro</option>');
                }
            });

            // Função para alternar a visibilidade da senha
            function togglePasswordVisibility(inputId, buttonId) {
                const input = document.getElementById(inputId);
                const button = document.getElementById(buttonId);
                const icon = button.querySelector('i');

                if (input.type === "password") {
                    input.type = "text"; // Mostra a senha
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash'); // Altera o ícone para "olho fechado"
                } else {
                    input.type = "password"; // Oculta a senha
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye'); // Altera o ícone para "olho aberto"
                }
            }

            // Adiciona o evento de clique ao botão de mostrar/ocultar senha
            document.getElementById('toggleSenha').addEventListener('click', function() {
                togglePasswordVisibility('senha', 'toggleSenha');
            });

            // Adiciona o evento de clique ao botão de mostrar/ocultar confirmar senha
            document.getElementById('toggleConfirmarSenha').addEventListener('click', function() {
                togglePasswordVisibility('confirmar_senha', 'toggleConfirmarSenha');
            });

            // Validação da senha em tempo real
            $('#senha').on('input', function() {
                const senha = $(this).val();

                // Verifica os requisitos da senha
                const temTamanho = senha.length >= 8;
                const temMaiuscula = /[A-Z]/.test(senha);
                const temNumero = /[0-9]/.test(senha);
                const temEspecial = /[!@#$%^&*()\-_=+{};:,<.>]/.test(senha);

                // Atualiza a mensagem de requisitos
                $('#reqTamanho').toggleClass('text-success', temTamanho);
                $('#reqMaiuscula').toggleClass('text-success', temMaiuscula);
                $('#reqNumero').toggleClass('text-success', temNumero);
                $('#reqEspecial').toggleClass('text-success', temEspecial);
            });

            // Validação do formulário antes de enviar
            $('#formCadastro').submit(function(e) {
                e.preventDefault();

                // Validação básica
                if ($('#senha').val() !== $('#confirmar_senha').val()) {
                    alert("As senhas não coincidem.");
                    return;
                }

                if ($('#estado').val() === "" || $('#cidade').val() === "") {
                    alert("Selecione o estado e a cidade.");
                    return;
                }

                // Mostra o spinner de carregamento
                $('#loading').show();
                $('#formCadastro button[type="submit"]').prop('disabled', true); // Desabilita o botão de submit

                // Envia os dados via AJAX
                var formData = $(this).serialize();
                $.ajax({
                    url: '../../backend/controllers/cadastro.php',
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        console.log("Resposta do backend:", response);
                        $('#loading').hide(); // Esconde o spinner
                        $('#formCadastro button[type="submit"]').prop('disabled', false); // Reabilita o botão de submit

                        if (response.status === "success") {
                            $("#mensagem").html('<div class="alert alert-success">' + response.message + '</div>');
                            setTimeout(function() {
                                window.location.href = 'login.php';
                            }, 3000); // Redireciona após 3 segundos
                        } else {
                            $("#mensagem").html('<div class="alert alert-danger">' + response.message + '</div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#loading').hide(); // Esconde o spinner
                        $('#formCadastro button[type="submit"]').prop('disabled', false); // Reabilita o botão de submit
                        console.error("Erro na requisição:", xhr.responseText);
                        $("#mensagem").html('<div class="alert alert-danger">Erro na requisição. Tente novamente.</div>');
                    }
                });
            });
        });
    </script>
</body>
</html>