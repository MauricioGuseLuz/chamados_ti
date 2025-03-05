<?php
session_start();
include '../../backend/includes/db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../lib/vendor/autoload.php';

// Recebe os dados do formulário
$nome = trim($_POST['nome']);
$data_nascimento = trim($_POST['data_nascimento']);
$email = trim($_POST['email']);
$telefone = trim($_POST['telefone']);
$whatsapp = trim($_POST['whatsapp']);
$senha = $_POST['senha'];
$confirmar_senha = $_POST['confirmar_senha'];
$estado = trim($_POST['estado']);
$cidade = trim($_POST['cidade']);

// Verifica se as senhas coincidem
if ($senha !== $confirmar_senha) {
    echo json_encode(["status" => "error", "message" => "As senhas não correspondem."]);
    exit();
}

// Verifica se a senha atende aos requisitos
if (strlen($senha) < 8 || 
    !preg_match("/[A-Z]/", $senha) || 
    !preg_match("/[0-9]/", $senha) || 
    !preg_match("/[!@#$%^&*()\-_=+{};:,<.>]/", $senha)) {
    echo json_encode(["status" => "error", "message" => "A senha deve ter no mínimo 8 caracteres, uma letra maiúscula, um número e um caractere especial."]);
    exit();
}

// Verifica se o usuário tem mais de 18 anos
$hoje = new DateTime();
$nascimento = new DateTime($data_nascimento);
$idade = $hoje->diff($nascimento)->y;

if ($idade < 18) {
    echo json_encode(["status" => "error", "message" => "Você deve ter mais de 18 anos para se cadastrar."]);
    exit();
}

// Verifica se o e-mail já está cadastrado
try {
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "error", "message" => "E-mail já cadastrado."]);
        exit();
    }
} catch (PDOException $e) {
    error_log("Erro no banco de dados: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Erro ao verificar e-mail."]);
    exit();
}

// Criptografa a senha
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Gera um token único para validação de e-mail
$token_validacao = bin2hex(random_bytes(32));

// Insere o usuário no banco de dados com o token de validação
try {
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome_completo, data_nascimento, email, telefone, whatsapp, senha, estado, cidade, token_validacao, validado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
    if ($stmt->execute([$nome, $data_nascimento, $email, $telefone, $whatsapp, $senha_hash, $estado, $cidade, $token_validacao])) {
        // Resposta de sucesso
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Cadastro realizado com sucesso! Verifique seu e-mail para validar sua conta."]);

        // Envia o e-mail em segundo plano
        enviarEmailValidacao($email, $nome, $token_validacao);
    } else {
        throw new Exception("Erro ao cadastrar usuário.");
    }
} catch (Exception $e) {
    error_log("Erro no cadastro: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Erro ao cadastrar usuário."]);
    exit();
}

// Função para enviar o e-mail de validação com layout bonito
function enviarEmailValidacao($email, $nome, $token_validacao) {
    $mail = new PHPMailer(true);
    try {
        // Configuração do servidor SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.mailosaur.net';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'hxpk9jw1@mailosaur.net';
        $mail->Password   = 'OULYYP1TXbR3rBTo85d3gkWTbSQZoBMX';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Configuração do e-mail
        $mail->setFrom('hxpk9jw1@mailosaur.net', 'Mauricio Guse');
        $mail->addAddress($email, $nome);

        $mail->isHTML(true);
        $mail->Subject = 'Confirmação de Cadastro';

        // Conteúdo HTML do e-mail
        $mail->Body = "
        <!DOCTYPE html>
        <html lang='pt-BR'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Validação de E-mail</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #ffffff;
                    border-radius: 8px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                }
                .header {
                    text-align: center;
                    padding: 20px 0;
                }
                .header h1 {
                    color: #333333;
                    font-size: 24px;
                }
                .content {
                    padding: 20px;
                    text-align: center;
                }
                .content p {
                    color: #555555;
                    font-size: 16px;
                    line-height: 1.6;
                }
                .button {
                    display: inline-block;
                    margin-top: 20px;
                    padding: 12px 24px;
                    background-color: #007bff;
                    color: #ffffff !important;
                    text-decoration: none;
                    border-radius: 5px;
                    font-size: 16px;
                }
                .footer {
                    text-align: center;
                    padding: 20px;
                    color: #777777;
                    font-size: 14px;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Validação de E-mail</h1>
                </div>
                <div class='content'>
                    <p>Olá, <strong>$nome</strong>!</p>
                    <p>Obrigado por se cadastrar em nossa plataforma. Para ativar sua conta, clique no botão abaixo:</p>
                    <a href='http://localhost/chamados_ti/backend/validar_email.php?token=$token_validacao' class='button'>Validar E-mail</a>
                    <p>Se o botão não funcionar, copie e cole o link abaixo no seu navegador:</p>
                    <p style='color: #007bff; word-wrap: break-word;'>
                        http://localhost/chamados_ti/backend/validar_email.php?token=$token_validacao
                    </p>
                </div>
                <div class='footer'>
                    <p>Se você não se cadastrou, por favor, ignore este e-mail.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        // Versão alternativa em texto simples (para clientes de e-mail que não suportam HTML)
        $mail->AltBody = "Olá, $nome!\n\nObrigado por se cadastrar em nossa plataforma. Para ativar sua conta, acesse o link abaixo:\n\nhttp://localhost/chamados_ti/backend/validar_email.php?token=$token_validacao\n\nSe você não se cadastrou, por favor, ignore este e-mail.";

        $mail->send(); // Envia o e-mail
    } catch (Exception $e) {
        error_log("Erro ao enviar e-mail: {$mail->ErrorInfo}");
    }
}
?>