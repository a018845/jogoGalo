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

// Obtém o nome do usuário para exibir na página
$usuario_nome = $_SESSION['usuario_nome'];

$resultado = mysqli_query($conexao, "SELECT * FROM jogos WHERE usuario2_id IS NULL;");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Meu site de jogos da velha</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <div class="d-flex flex-column min-vh-100">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Meu site de jogos da velha</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Início</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownJogos" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Jogos da velha
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownJogos">
                            <a class="dropdown-item" href="jogoCTPC.php">Contra o PC</a>
                            <a class="dropdown-item" href="jogoum.php" id="jogoum-link">Um contra o outro</a>
                            <a class="dropdown-item" href="jogoEscolha.php">Multiplayer</a>
                        </div>
                    </li>
                    <?php if ($_SESSION['usuario_isAdmin']) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Configurações</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Sair</a>
                    </li>
                </ul>
            </div>
            <span class="navbar-text">
                Olá, <?php echo $usuario_nome; ?>!
            </span>
        </nav>
        <!-- conteúdo principal -->
        <main class="flex-fill">
            <div class="p-3 mb-2 bg-primary text-white">
                <h1>Jogos Disponíveis</h1>
                <div class="container mt-4 text-light" id="jogos-disponiveis">
                    <?php
                    // Função para atualizar o conteúdo da página
                    function atualizarConteudo($conexao)
                    {
                        // Consulta ao banco de dados
                        $query = "SELECT * FROM jogos WHERE usuario2_id IS NULL";
                        $resultado = mysqli_query($conexao, $query);

                        // Exibição dos jogos disponíveis
                        if (mysqli_num_rows($resultado) > 0) {
                            echo "<ul>";
                            while ($jogo = mysqli_fetch_array($resultado)) {
                                echo "<li>Jogo criado por " . $jogo['usuario1_id'] .
                                    " em " . $jogo['criado_em'] .
                                    ". Tipo: " . $jogo['tipo'] .
                                    ". <a href='jogomult.php?id=" . $jogo['id'] . "'>Jogar</a></li>";
                            }
                            echo "</ul>";
                        } else {
                            echo "Não há jogos disponíveis. Crie um novo jogo.";
                        }
                    }

                    // Exibe o conteúdo inicialmente
                    atualizarConteudo($conexao);
                    ?>
                </div>
            </div>
        </main>
        <!-- rodapé -->
        <footer class="bg-dark text-light">
            <p>Site produzido por Hugo Braga </p>
        </footer>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <script>
        // Atualiza o conteúdo a cada 5 segundos
        setInterval(function() {
            $('#jogos-disponiveis').load('atualizar_jogos.php');
        }, 5000);
    </script>


</body>

</html>