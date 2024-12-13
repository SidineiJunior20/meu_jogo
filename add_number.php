<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $number = intval($_POST['number']);

    // Obter o último número para criar a relação
    $result = $conn->query("SELECT number FROM history ORDER BY id DESC LIMIT 1");
    $last_number = $result->fetch_assoc();
    $previous_number = $last_number ? $last_number['number'] : null;

    $stmt = $conn->prepare("INSERT INTO history (number, previous_number) VALUES (?, ?)");
    $stmt->bind_param("ii", $number, $previous_number);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit();
}
?>
