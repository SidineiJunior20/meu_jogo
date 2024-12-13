<?php
require 'db.php';

// Obter o último número lançado
$result = $conn->query("SELECT * FROM history ORDER BY id DESC LIMIT 10");
$last_numbers = $result->fetch_all(MYSQLI_ASSOC);

// Obter todas as relações entre números do histórico
$relations = [];
$result = $conn->query("SELECT * FROM history ORDER BY id");
while ($row = $result->fetch_assoc()) {
    $number = $row['number'];
    $previous_number = $row['previous_number'];

    if ($previous_number !== null) {
        if (!isset($relations[$previous_number])) {
            $relations[$previous_number] = [];
        }
        if (!isset($relations[$previous_number][$number])) {
            $relations[$previous_number][$number] = 0;
        }
        $relations[$previous_number][$number]++;
    }
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

function getDozen($number) {
    if ($number >= 1 && $number <= 12) return 1;
    if ($number >= 13 && $number <= 24) return 2;
    if ($number >= 25 && $number <= 36) return 3;
    return 0;
}

function getColumn($number) {
    $column1 = [1, 4, 7, 10, 13, 16, 19, 22, 25, 28, 31, 34];
    $column2 = [2, 5, 8, 11, 14, 17, 20, 23, 26, 29, 32, 35];
    $column3 = [3, 6, 9, 12, 15, 18, 21, 24, 27, 30, 33, 36];

    if (in_array($number, $column1)) return 1;
    if (in_array($number, $column2)) return 2;
    if (in_array($number, $column3)) return 3;
    return 0;
}

function getSection($number) {
    $tiers = [33, 16, 24, 5, 10, 23, 8, 30, 11, 36, 13, 27];
    $orphelins = [17, 34, 6, 9, 31, 14, 20, 1];
    $voisins = [25, 2, 21, 4, 19, 15, 32, 0, 26, 3, 35, 12, 28, 7, 29, 18, 22];
    $zero = [15, 32, 0, 26, 3, 35, 12];

    if (in_array($number, $tiers)) return 'Tiers';
    if (in_array($number, $orphelins)) return 'Orphelins';
    if (in_array($number, $voisins)) return 'Voisins';
    if (in_array($number, $zero)) return 'Zero';
    return 'Indefinido';
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Números da Roleta</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Registro de Números da Roleta</h1>

        <form action="add_number.php" method="post">
            <label for="number">Número:</label>
            <input type="number" id="number" name="number" min="0" max="36" required>
            <button type="submit">Adicionar Número</button>
        </form>

        <!-- Mostrar os últimos números lançados -->
        <h2>Últimos Números Lançados:</h2>
        <p>
            <?php
            foreach ($last_numbers as $last_number) {
                echo "<span class='" . getColorClass($last_number['number']) . "'>" . $last_number['number'] . "</span>, ";
            }
            ?>
        </p>

        <!-- Botão para apagar o último número adicionado -->
        <form action="delete_last_number.php" method="post" onsubmit="return confirm('Tem certeza que deseja apagar o último número?')">
            <button type="submit">Apagar Último Número Adicionado</button>
        </form>

        <!-- Botão para limpar todo o histórico -->
        <form action="clear_history.php" method="post" onsubmit="return confirm('Tem certeza que deseja limpar todo o histórico?')">
            <button type="submit">Limpar Todo o Histórico</button>
        </form>

        <h2>Relação entre Números</h2>
        <table class="relation-table">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Relações</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0; $i <= 36; $i++): ?>
                    <tr>
                        <td class="<?= getColorClass($i) ?>"><?= $i ?></td>
                        <td>
                            <?php if (isset($relations[$i])): ?>
                                <?php foreach ($relations[$i] as $related_number => $count): ?>
                                    ➜ Número <?= $related_number ?>, Dúzia <?= getDozen($related_number) ?>, Coluna <?= getColumn($related_number) ?>, <?= $count ?> vezes, <?= getSection($related_number) ?><br>
                                <?php endforeach; ?>
                            <?php else: ?>
                                Nenhuma relação encontrada
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>

        <a href="dashboard.php">Ver Frequência dos Números</a>
    </div>
</body>
</html>
