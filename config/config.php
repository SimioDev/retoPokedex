<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "pokemon_db";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
