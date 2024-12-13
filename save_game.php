<?php
require 'db.php';

// Obter todos os números do histórico
$result = $conn->query("SELECT * FROM history ORDER BY id");

$game_data = [];

while ($row = $result->fetch_assoc()) {
    $game_data[] = $row;
}

$conn->close();

// Gerar nome único para o arquivo
$filename = 'saved_games/game_' . date('Ymd_His') . '.json';

// Salvar dados do jogo no arquivo JSON
file_put_contents($filename, json_encode($game_data));

echo "Jogo salvo com sucesso!";

// Redirecionar de volta para a página inicial
header("Location: index.php");
exit();
?>
