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

// Verifica se o formulário de login foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém as informações do formulário
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Busca o usuário no banco de dados pelo e-mail
    $stmt = $pdo->prepare('SELECT id, nome, senha, isAdmin FROM usuarios WHERE email = :email');
    $stmt->execute(array(':email' => $email));
    $usuario = $stmt->fetch();

    // Verifica se o usuário existe e a senha está correta
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Define as informações do usuário na sessão
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_isAdmin'] = $usuario['isAdmin'];

        // Redireciona para a página de index
        header('Location: index.php');
        exit;
    } else {
        // Mostra uma mensagem de erro se as informações estiverem incorretas
        $mensagem_erro = 'E-mail ou senha incorretos.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($mensagem_erro)): ?>
        <p><?php echo $mensagem_erro; ?></p>
    <?php endif; ?>
    <form method="post">
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required><br>
        <button type="submit">Entrar</button>
    </form>
    <p>Não tem uma conta? <a href="register.php">Registre-se aqui</a>.</p>
</body>
</html>
