<?php
require 'db.php';

$frequencies = [];
$result = $conn->query("SELECT number, COUNT(*) as count FROM history GROUP BY number ORDER BY number");
while ($row = $result->fetch_assoc()) {
    $frequencies[$row['number']] = $row['count'];
}

function getColorClass($number) {
    $red_numbers = [1, 3, 5, 7, 9, 12, 14, 16, 18, 19, 21, 23, 25, 27, 30, 32, 34, 36];
    $black_numbers = [2, 4, 6, 8, 10, 11, 13, 15, 17, 20, 22, 24, 26, 28, 29, 31, 33, 35];

    if (in_array($number, $red_numbers)) {
        return 'red';
    } elseif (in_array($number, $black_numbers)) {
        return 'black';
    } else {
        return 'green';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Frequência dos Números</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Dashboard de Frequência dos Números</h1>
        <table>
            <tr>
                <th>Número</th>
                <th>Frequência</th>
            </tr>
            <?php foreach ($frequencies as $number => $count): ?>
                <tr class="<?= getColorClass($number) ?>">
                    <td><?= $number ?></td>
                    <td><?= $count ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="index.php">Voltar ao Registro</a>
    </div>
</body>
</html>
