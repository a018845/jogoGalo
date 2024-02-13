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

// Verifica se o usuário chegou a esta página com um ID de usuário válido
if (!isset($_GET['usuario_id']) || empty($_GET['usuario_id'])) {
    echo 'Erro: ID de usuário inválido.';
    exit;
}


// Obtém o ID do usuário adversário
$adversario_id = $_GET['usuario_id'];

// Obtém o nome do adversário
$resultado_adversario = mysqli_query($conexao, "SELECT nome FROM usuarios WHERE id = $adversario_id");
$adversario = mysqli_fetch_array($resultado_adversario);

// Cria um novo jogo na tabela de jogos
$stmt = $conexao->prepare('INSERT INTO jogos (usuario1_id, usuario2_id) VALUES (?, ?)');
$stmt->bind_param('ii', $_SESSION['usuario_id'], $adversario_id);
$stmt->execute();

// Obtém o ID do novo jogo
$novo_jogo_id = $stmt->insert_id;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Novo Jogo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1>Novo Jogo</h1>
        <p>Você está jogando contra <?php echo $adversario['nome']; ?></p>
        <a href="jogomult.php?id=<?php echo $novo_jogo_id; ?>" class="btn btn-primary">Iniciar jogo</a>
    </div>
</body>

</html>