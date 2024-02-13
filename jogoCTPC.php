<?php
// Inclua o arquivo de configuração da conexão com o banco de dados
require_once 'config.php';

// Inicializa a sessão
session_start();

// Verifica se o usuário não está logado, se sim, redireciona para a página de login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Obtém o nome do usuário para exibir na página
$usuario_nome = $_SESSION['usuario_nome'];
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Jogo da Velha</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="js/jogo_da_velha.js"></script>
</head>

<body class="body-container">
    <div class="container-row">
        <h1>Jogo da Velha</h1>
        <label for="dificuldade">Escolha a dificuldade:</label>
        <select id="dificuldade">
            <option value="facil">Fácil</option>
            <option value="medio">Médio</option>
            <option value="dificil">Difícil</option>
        </select>
        <div id="tabuleiro">
            <!-- Conteúdo do tabuleiro será gerado pelo JavaScript -->
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <button class="btn btn-primary btn-voltar" id="voltar">Voltar para o início</button>
            </div>
        </div>
    </div>
    
    <script>
        // adiciona um evento de clique ao botão "voltar"
        $("#voltar").click(function() {
            // redireciona o usuário para o index.php
            window.location.href = "index.php";
        });
    </script>

</body>

</html>