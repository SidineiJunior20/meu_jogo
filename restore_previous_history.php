<?php
require 'db.php';

// Limpar todo o histórico
$conn->query("TRUNCATE TABLE history");

$conn->close();

header("Location: index.php");
exit();
?>
