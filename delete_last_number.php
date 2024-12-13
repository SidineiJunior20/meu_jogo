<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Apagar o último número adicionado
    $conn->query("DELETE FROM history ORDER BY id DESC LIMIT 1");

    header("Location: index.php");
    exit();
}
?>
