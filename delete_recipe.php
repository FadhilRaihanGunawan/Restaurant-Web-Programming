<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id'])) {
    header("Location: dashboard.php");
    exit();
}

require 'config.php';

$recipe_id = (int) $_POST['id'];
$user_id   = $_SESSION['user_id'];

/* Fetch the recipe — only if it belongs to the logged-in user */
$stmt = $conn->prepare("SELECT image_path FROM recipes WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $recipe_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $recipe = $result->fetch_assoc();
    $stmt->close();

    /* Delete the image file if one was uploaded */
    if (!empty($recipe['image_path']) && file_exists($recipe['image_path'])) {
        unlink($recipe['image_path']);
    }

    /* Delete the DB row */
    $del = $conn->prepare("DELETE FROM recipes WHERE id = ? AND user_id = ?");
    $del->bind_param("ii", $recipe_id, $user_id);
    $del->execute();
    $del->close();
} else {
    $stmt->close();
}

$conn->close();
header("Location: dashboard.php");
exit();
