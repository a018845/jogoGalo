<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jogodogalo";

// Conecta ao banco de dados
$conexao = mysqli_connect($servername, $username, $password, $dbname);

// Verifica se a conexão foi bem-sucedida
if (!$conexao) {
    die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
}
?>