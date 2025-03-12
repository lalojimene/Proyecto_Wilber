<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$host = "localhost";
$user = "root";
$password = "";
$dbname = "gerardo_db";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
