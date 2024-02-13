<?php
// Inclui o arquivo de configuração da conexão com o banco de dados
require_once 'config1.php';

// Inicializa a sessão
session_start();

// Verifica se o usuário não está logado, se sim, redireciona para a página de login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Atualiza o último acesso do usuário atual
$usuario_id = $_SESSION['usuario_id'];
$resultado_atualizar_ultimo_acesso = mysqli_query($conexao, "UPDATE usuarios SET ultimo_acesso = NOW() WHERE id = $usuario_id");

// Obtém a lista de usuários conectados (último acesso nos últimos 5 minutos)
$tempo_maximo_inativo = 5 * 60; // 5 minutos em segundos
$resultado_usuarios_conectados = mysqli_query($conexao, "SELECT id, nome FROM usuarios WHERE id != $usuario_id AND TIMESTAMPDIFF(SECOND, ultimo_acesso, NOW()) <= $tempo_maximo_inativo");

// Verifica se o formulário de criação de jogo foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém o ID do usuário adversário
    $adversario_id = $_POST['adversario_id'];

    // Cria um novo jogo na tabela de jogos
    $stmt = $conexao->prepare('INSERT INTO jogos (usuario1_id, usuario2_id, tipo) VALUES (?, ?, "MULT")');
    $stmt->bind_param('ii', $usuario_id, $adversario_id);
    $stmt->execute();
    $novo_jogo_id = $stmt->insert_id;
    $stmt->close();

    // Redireciona para a página do novo jogo
    header("Location: jogomult.php?id=$novo_jogo_id");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Lista de usuários conectados</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1>Lista de usuários conectados</h1>
        <ul class="list-group">
            <?php while ($usuario = mysqli_fetch_array($resultado_usuarios_conectados)) : ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo $usuario['nome']; ?>
                    <form method="post">
                        <input type="hidden" name="adversario_id" value="<?php echo $usuario['id']; ?>">
                        <button type="submit" class="btn btn-primary">Iniciar jogo</button>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>
        <form method="post" action="criar_jogo.php">
            <button type="submit" class="btn btn-primary">Criar novo jogo</button>
        </form>

    </div>
</body>

</html>