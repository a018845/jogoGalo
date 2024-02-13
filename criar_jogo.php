<?php
// Inclui o arquivo de configuração da conexão com o banco de dados
require_once 'config1.php';

// Inicializa a sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário não está logado, se sim, redireciona para a página de login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Obtém o ID do usuário atual
$usuario_id = $_SESSION['usuario_id'];

// Verifica se o formulário de criação de jogo foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém o ID do usuário adversário
    $adversario_id = $_POST['adversario_id'];

    // Verifica se o usuário já está em algum jogo como jogador 1
    $resultado_verificar_jogador1 = mysqli_query($conexao, "SELECT * FROM jogos WHERE usuario1_id = $usuario_id AND vencedor_id IS NULL");
    if (mysqli_num_rows($resultado_verificar_jogador1) > 0) {
        $mensagem = "Você já está em um jogo como jogador 1.";
    } else {
        // Verifica se o usuário já está em algum jogo como jogador 2
        $resultado_verificar_jogador2 = mysqli_query($conexao, "SELECT * FROM jogos WHERE usuario2_id = $usuario_id AND vencedor_id IS NULL");
        if (mysqli_num_rows($resultado_verificar_jogador2) > 0) {
            $mensagem = "Você já está em um jogo como jogador 2.";
        } else {
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
    }
}

// Obtém a lista de usuários conectados (último acesso nos últimos 5 minutos)
$tempo_maximo_inativo = 5 * 60; // 5 minutos em segundos
$resultado_usuarios_conectados = mysqli_query($conexao, "SELECT id, nome FROM usuarios WHERE id != $usuario_id AND TIMESTAMPDIFF(SECOND, ultimo_acesso, NOW()) <= $tempo_maximo_inativo");

// Obtém a lista de jogos disponíveis sem adversário
$resultado_jogos_disponiveis = mysqli_query($conexao, "SELECT * FROM jogos WHERE usuario2_id IS NULL");

// Obtém o jogador 1 e 2 do jogo atual (se existir)
$jogador1 = null;
$jogador2 = null;
$jogo = null;
if (isset($_GET['id'])) {
    $jogo_id = $_GET['id'];
    $resultado_jogo = mysqli_query($conexao, "SELECT * FROM jogos WHERE id = $jogo_id");
    $jogo = mysqli_fetch_array($resultado_jogo);
    if ($jogo['usuario1_id']) {
        $resultado_jogador1 = mysqli_query($conexao, "SELECT * FROM usuarios WHERE id = " . $jogo['usuario1_id']);
        $jogador1 = mysqli_fetch_array($resultado_jogador1);
    }
    if ($jogo['usuario2_id']) {
        $resultado_jogador2 = mysqli_query($conexao, "SELECT * FROM usuarios WHERE id = " . $jogo['usuario2_id']);
        $jogador2 = mysqli_fetch_array($resultado_jogador2);
    }
}

// Verifica se o usuário já está em algum jogo como jogador 1
$resultado_verificar_jogador1 = mysqli_query($conexao, "SELECT * FROM jogos WHERE usuario1_id = $usuario_id AND vencedor_id IS NULL");
if (mysqli_num_rows($resultado_verificar_jogador1) > 0) {
    $mensagem = "Você já está em um jogo como jogador 1.";
} else {
    // Verifica se o usuário já está em algum jogo como jogador 2
    $resultado_verificar_jogador2 = mysqli_query($conexao, "SELECT * FROM jogos WHERE usuario2_id = $usuario_id AND vencedor_id IS NULL");
    if (mysqli_num_rows($resultado_verificar_jogador2) > 0) {
        $mensagem = "Você já está em um jogo como jogador 2.";
    }
}

// Inclui o arquivo da página de criação de jogo
include 'criar_jogo.php';
