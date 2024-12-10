<?php
session_start();
require_once 'conexao.php';

$mensagem = '';
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_completo = trim($_POST['nome_completo'] ?? '');
    $genero = $_POST['genero'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

    // Validação do nome completo
    if (!preg_match('/^[a-zA-ZÀ-ÿ\s]{10,}$/', $nome_completo) || str_word_count($nome_completo) < 2) {
        $mensagem = "O nome completo deve ter no mínimo 10 caracteres e conter pelo menos 2 palavras.";
    } elseif (empty($genero)) {
        $mensagem = "Por favor, selecione o seu gênero.";
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
                $nome_split = explode(' ', $nome_completo);
                $ultimo_nome = end($nome_split);
                $usuario = $nome_split[0] . '_' . $ultimo_nome . '-' . date('Hi');

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
                    
                    $sucesso = true;
                }
            }
        } catch (PDOException $e) {
            $mensagem = "Não foi possível realizar o cadastro. Erro [" . $e->getCode() . "]. Por favor, tente novamente.";
        }
    }
}
?>




<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-dark text-light">

    <div class="container mt-5">
        <h2 class="text-center text-primary">Cadastro</h2>
        <form action="cadastro.php" method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" name="nome_completo" placeholder="Nome Completo" required>
            </div>
            <div class="mb-3">
                <select class="form-control" name="genero" required>
                    <option value="" disabled selected>Selecione seu gênero</option>
                    <option value="masculino">Masculino</option>
                    <option value="feminino">Feminino</option>
                    <option value="outro">Outro</option>
                </select>
            </div>
            <div class="mb-3">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="senha" placeholder="Senha" value="12345678" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="confirmar_senha" placeholder="Confirmar Senha" value="12345678" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
        </form>
    </div>

    <script>
        // Mensagens de sucesso ou erro vindas do PHP
        <?php if ($mensagem): ?>
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: '<?= $mensagem ?>'
            });
        <?php elseif ($sucesso): ?>
            Swal.fire({
                icon: 'success',
                title: 'Sucesso',
                text: 'Cadastro realizado com sucesso!',
                confirmButtonText: 'Ir para login'
            }).then(() => {
                window.location.href = 'login.php';
            });
        <?php endif; ?>
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
