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

// Obtém o ID do jogo a ser jogado
$jogo_id = $_GET['id'];

// Obtém os detalhes do jogo da tabela de jogos
$resultado = mysqli_query($conexao, "SELECT * FROM jogos WHERE id = $jogo_id");
$jogo = mysqli_fetch_array($resultado);

// Verifica se o usuário atual é um dos jogadores do jogo
if ($_SESSION['usuario_id'] != $jogo['usuario1_id'] && $_SESSION['usuario_id'] != $jogo['usuario2_id']) {
    echo 'Você não está autorizado a acessar este jogo.';
    exit;
}

// Obtém o nome do jogador 1
$resultado_jogador1 = mysqli_query($conexao, "SELECT nome FROM usuarios WHERE id = {$jogo['usuario1_id']}");
$jogador1 = mysqli_fetch_array($resultado_jogador1);

// Obtém o nome do jogador 2 (se houver)
if ($jogo['usuario2_id']) {
    $resultado_jogador2 = mysqli_query($conexao, "SELECT nome FROM usuarios WHERE id = {$jogo['usuario2_id']}");
    $jogador2 = mysqli_fetch_array($resultado_jogador2);
}

// Obtém o estado atual do jogo
$estado = json_decode($jogo['estado']);

// Verifica se é a vez do jogador atual jogar
if ($_SESSION['usuario_id'] == $jogo['usuario1_id'] && $jogo['usuario2_id'] || $_SESSION['usuario_id'] == $jogo['usuario2_id'] && !$jogo['usuario2_id']) {
    $sua_vez = true;
} else {
    $sua_vez = false;
}

// Verifica se o jogo terminou
if ($jogo['vencedor_id']) {
    $resultado_vencedor = mysqli_query($conexao, "SELECT nome FROM usuarios WHERE id = {$jogo['vencedor_id']}");
    $vencedor = mysqli_fetch_array($resultado_vencedor);
    $mensagem = "O jogo terminou! O vencedor é: " . $vencedor['nome'];
} elseif (tabuleiro_cheio($estado)) {
    $mensagem = "O jogo terminou empatado!";
} elseif (!$sua_vez) {
    $mensagem = "Aguarde sua vez de jogar.";
} else {
    $mensagem = "É a sua vez de jogar!";
}

// Função para verificar se o tabuleiro está cheio
function tabuleiro_cheio($estado)
{
    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            if ($estado[$i][$j] == 0) {
                return false;
            }
        }
    }
    return true;
}

// Função para verificar se um jogador venceu o jogo
function jogador_venceu($estado, $jogador)
{
    // Verifica as linhas
    for ($i = 0; $i < 3; $i++) {
        if ($estado[$i][0] == $jogador && $estado[$i][1] == $jogador && $estado[$i][2] == $jogador) {
            return true;
        }
    }
    // Verifica as colunas
    for ($j = 0; $j < 3; $j++) {
        if ($estado[0][$j] == $jogador && $estado[1][$j] == $jogador && $estado[2][$j] == $jogador) {
            return true;
        }
    }
    // Verifica as diagonais
    if ($estado[0][0] == $jogador && $estado[1][1] == $jogador && $estado[2][2] == $jogador) {
        return true;
    }
    if ($estado[0][2] == $jogador && $estado[1][1] == $jogador && $estado[2][0] == $jogador) {
        return true;
    }
    // Se não houver vencedor, retorna false
    return false;
}

// Verifica se o jogador atual venceu o jogo
if (jogador_venceu($estado, $_SESSION['usuario_id'])) {
    mysqli_query($conexao, "UPDATE jogos SET vencedor_id = {$_SESSION['usuario_id']} WHERE id = $jogo_id");
    $mensagem = "Parabéns! Você venceu o jogo.";
}

// Verifica se o jogo terminou após o movimento do jogador atual
if ($jogo['vencedor_id']) {
    $resultado_vencedor = mysqli_query($conexao, "SELECT nome FROM usuarios WHERE id = {$jogo['vencedor_id']}");
    $vencedor = mysqli_fetch_array($resultado_vencedor);
    $mensagem = "O jogo terminou! O vencedor é: " . $vencedor['nome'];
} elseif (tabuleiro_cheio($estado)) {
    $mensagem = "O jogo terminou empatado!";
} elseif (!$sua_vez) {
    $mensagem = "Aguarde sua vez de jogar.";
} else {
    $mensagem = "É a sua vez de jogar!";
}

// Processa o movimento do jogador atual
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $sua_vez && !$jogo['vencedor_id']) {
    $posicao = $_POST['posicao'];
    $estado[$posicao[0]][$posicao[1]] = $_SESSION['usuario_id'];
    mysqli_query($conexao, "UPDATE jogos SET estado = '" . json_encode($estado) . "' WHERE id = $jogo_id");
    header('Location: jogo.php?id=' . $jogo_id);
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Jogo da velha</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jogo.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>Jogo da velha</h2>
                <p><?php echo $mensagem; ?></p>
                <table class="table table-bordered">
                    <?php for ($i = 0; $i < 3; $i++) : ?>
                        <tr>
                            <?php for ($j = 0; $j < 3; $j++) : ?>
                                <td>
                                    <?php if ($sua_vez && !$jogo['vencedor_id'] && $estado[$i][$j] == 0) : ?>
                                        <form method="POST">
                                            <input type="hidden" name="posicao[]" value="<?php echo $i; ?>,<?php echo $j; ?>">
                                            <button type="submit" class="btn btn-primary"></button>
                                        </form>
                                    <?php elseif ($estado[$i][$j] == $jogo['usuario1_id']) : ?>
                                        X
                                    <?php elseif ($estado[$i][$j] == $jogo['usuario2_id']) : ?>
                                        O
                                    <?php endif; ?>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php endfor; ?>
                </table>
            </div>
            <div class="col-md-6">
                <h2>Jogadores</h2>
                <ul>
                    <li><?php echo $jogador1['nome']; ?> (X)</li>
                    <?php if ($jogo['usuario2_id']) : ?>
                        <li><?php echo $jogador2['nome']; ?> (O)</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</body>

</html>