<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="card bg-dark text-light">
            <div class="card-body">
                <img src="/assets/img/logo.png" id="logo" class="rounded mx-auto d-block" alt="logo">
                <h2 class="text-center mb-4">Login</h2>
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu email" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" id="btn-submit">Entrar</button>
                    </div>
                </form>
                <p class="text-center mt-3">
                    Não tem uma conta? <a href="cadastro.php" class="text-info">Cadastre-se</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Exemplo de exibição de mensagem com SweetAlert2
        const urlParams = new URLSearchParams(window.location.search);
        const mensagem = urlParams.get('mensagem');
        if (mensagem) {
            Swal.fire({
                title: 'Aviso',
                text: mensagem,
                icon: 'info',
                confirmButtonText: 'Ok'
            });
        }
    </script>
</body>
</html>
