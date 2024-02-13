<?php
// Inclui o arquivo de configuração da conexão com o banco de dados
require_once 'config.php';

// Inicializa a sessão
session_start();

// Verifica se o usuário já está logado, se sim, redireciona para a página de index
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

// Verifica se o formulário de registro foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém as informações do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifica se o e-mail já está em uso
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM usuarios WHERE email = :email');
    $stmt->execute(array(':email' => $email));
    $email_existe = $stmt->fetchColumn();

    if ($email_existe) {
        // Mostra uma mensagem de erro se o e-mail já estiver em uso
        $mensagem_erro = 'Este e-mail já está em uso.';
    } else {
        // Cria o usuário no banco de dados
        $stmt = $pdo->prepare('INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)');
        $stmt->execute(array(':nome' => $nome, ':email' => $email, ':senha' => password_hash($senha, PASSWORD_DEFAULT)));

        // Redireciona para a página de login
        header('Location: login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
</head>
<body>
    <h1>Registro</h1>
    <?php if (isset($mensagem_erro)): ?>
        <p><?php echo $mensagem_erro; ?></p>
    <?php endif; ?>
    <form method="post">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br>
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required><br>
        <button type="submit">Registrar</button>
    </form>
</body>
</html>
