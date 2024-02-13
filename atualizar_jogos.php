<?php
// Inclui o arquivo de configuração da conexão com o banco de dados
require_once 'config1.php';

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