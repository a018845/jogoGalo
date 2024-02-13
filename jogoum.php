<?php
// Inicializa a sessão
session_start();

// Verifica se o usuário não está logado, se sim, redireciona para a página de login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Jogo da Velha</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/seu-css-personalizado.css">

    <link rel="stylesheet" href="css/jogoum.css">
</head>

<body>
    <div class="d-flex flex-column min-vh-100">
        <h1>Jogo da Velha</h1>
        <main class="flex-fill">
        <div id="board">
            <div class="row">
                <div class="cell" id="cell00" data-row="0" data-col="0"></div>
                <div class="cell" id="cell01" data-row="0" data-col="1"></div>
                <div class="cell" id="cell02" data-row="0" data-col="2"></div>
            </div>
            <div class="row">
                <div class="cell" id="cell10" data-row="1" data-col="0"></div>
                <div class="cell" id="cell11" data-row="1" data-col="1"></div>
                <div class="cell" id="cell12" data-row="1" data-col="2"></div>
            </div>
            <div class="row">
                <div class="cell" id="cell20" data-row="2" data-col="0"></div>
                <div class="cell" id="cell21" data-row="2" data-col="1"></div>
                <div class="cell" id="cell22" data-row="2" data-col="2"></div>
            </div>

        </div>
        <div class="container">
            <div class="row justify-content-center">
                <button class="btn btn-primary btn-voltar" id="voltar">Voltar para o início</button>
            </div>
        </div>
        </main>
        <!-- rodapé -->
        <footer class="bg-dark text-light text-center">
            <p>Site produzido por Hugo Braga </p>
        </footer>
    </div>  
    <script src="js/jogoum.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>

    <script>
        // adiciona um evento de clique ao botão "voltar"
        $("#voltar").click(function() {
            // redireciona o usuário para o index.php
            window.location.href = "index.php";
        });
    </script>
</body>

</html>