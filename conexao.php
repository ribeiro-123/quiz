<?php
$host = 'localhost';  // O endereço do seu servidor MySQL
$dbname = 'db_quiz';  // O nome do seu banco de dados
$username = 'root';    // O nome de usuário do banco de dados
$password = '';        // A senha do banco de dados (pode ser em branco no XAMPP, mas em produção deve ser configurada)

try {
    // Criar a conexão PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Definir o modo de erro do PDO para exceções
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexão bem-sucedida!";

} catch (PDOException $e) {
    // Em caso de erro, exibir uma mensagem de erro
    die("Erro de conexão com o banco de dados: " . $e->getMessage());
}
?>
