<?php
session_start();
include '../../backend/includes/db.php';

// Recebe os dados do formulário
$email = $_POST['email'];
$senha = $_POST['senha'];

// Verifica se o e-mail e a senha foram fornecidos
if (empty($email) || empty($senha)) {
    echo json_encode(["status" => "error", "message" => "Preencha todos os campos."]);
    exit();
}

// Busca o usuário no banco de dados
$stmt = $pdo->prepare("SELECT id, senha, validado FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o usuário existe
if (!$usuario) {
    echo json_encode(["status" => "error", "message" => "E-mail ou senha incorretos."]);
    exit();
}

// Verifica se a senha está correta
if (!password_verify($senha, $usuario['senha'])) {
    echo json_encode(["status" => "error", "message" => "E-mail ou senha incorretos."]);
    exit();
}

// Verifica se o e-mail foi validado
if ($usuario['validado'] == 0) {
    echo json_encode(["status" => "error", "message" => "Você precisa validar seu e-mail antes de acessar o sistema."]);
    exit();
}

// Login bem-sucedido
$_SESSION['usuario_id'] = $usuario['id']; // Armazena o ID do usuário na sessão
echo json_encode(["status" => "success", "message" => "Login realizado com sucesso!"]);
?>