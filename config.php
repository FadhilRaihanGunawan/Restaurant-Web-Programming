<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "recipe_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* Add columns if they don't exist yet — silently ignore if already present */
try { $conn->query("ALTER TABLE recipes ADD COLUMN image_path VARCHAR(255) DEFAULT NULL"); }
catch (mysqli_sql_exception $e) {}

try { $conn->query("ALTER TABLE recipes ADD COLUMN is_public TINYINT(1) NOT NULL DEFAULT 1"); }
catch (mysqli_sql_exception $e) {}
?>