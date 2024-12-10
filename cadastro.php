<?php
session_start();
require_once 'conexao.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_completo = trim($_POST['nome_completo'] ?? '');
    $genero = $_POST['genero'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

    // Validação do nome completo
    if (!preg_match('/^[a-zA-ZÀ-ÿ\s]{10,}$/', $nome_completo) || str_word_count($nome_completo) < 2) {
        $mensagem = "O nome completo deve ter no mínimo 10 caracteres e conter pelo menos 2 palavras.";
    } elseif ($senha !== $confirmar_senha) {
        $mensagem = "As senhas não coincidem.";
    } elseif (strlen($senha) < 8) {
        $mensagem = "A senha deve ter no mínimo 8 caracteres.";
    } else {
        try {
            // Verificar se o email já existe
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                $mensagem = "O e-mail informado já está em uso.";
            } else {
                // Geração do nome de usuário
                $nome_split = explode(' ', $nome_completo); // Divide o nome em partes
                $ultimo_nome = end($nome_split); // Pega o último nome
                $usuario = $nome_split[0] . '_' . $ultimo_nome . '-' . date('Hi'); // Cria o nome de usuário

                // Verificar se o nome de usuário já existe
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = ?");
                $stmt->execute([$usuario]);
                if ($stmt->fetchColumn() > 0) {
                    $mensagem = "Erro interno: Tente novamente para criar um nome de usuário único.";
                } else {
                    // Criptografar a senha
                    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

                    // Inserir no banco de dados
                    $stmt = $pdo->prepare("INSERT INTO usuarios 
                        (nome_completo, genero, email, usuario, senha, vidas, pontuacao, fator_d, criado_em)
                        VALUES (?, ?, ?, ?, ?, 3, 0, 1, NOW())");
                    $stmt->execute([$nome_completo, $genero, $email, $usuario, $senha_hash]);

                    // Sucesso no cadastro
                    $_SESSION['sucesso'] = "Cadastro realizado com sucesso! Faça login para continuar.";
                    header("Location: login.php");
                    exit;
                }
            }
        } catch (PDOException $e) {
            // Mensagem de erro amigável com o código do erro
            $mensagem = "Não foi possível realizar o cadastro. Erro [" . $e->getCode() . "]. Por favor, tente novamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
        }
        .alert {
            margin-bottom: 20px;
        }
        input, select, button {
            border-radius: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Cadastro</h1>

        <?php if (!empty($mensagem)) : ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="nome_completo" class="form-label">Nome Completo</label>
                <input type="text" class="form-control" id="nome_completo" name="nome_completo" minlength="10" required>
            </div>
            <div class="mb-3">
                <label for="genero" class="form-label">Gênero</label>
                <select class="form-select" id="genero" name="genero" required>
                    <option value="">Selecione</option>
                    <option value="M">Masculino</option>
                    <option value="F">Feminino</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" minlength="8" required>
            </div>
            <div class="mb-3">
                <label for="confirmar_senha" class="form-label">Confirmar Senha</label>
                <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" minlength="8" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
        </form>
    </div>
</body>
</html>
