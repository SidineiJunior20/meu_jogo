<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Limpar todo o histórico
    $conn->query("DELETE FROM history");

    header("Location: index.php");
    exit();
}
?>
