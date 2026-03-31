<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $ingredients = $_POST['ingredients'];
    $instructions = $_POST['instructions'];
    $cooking_time = $_POST['cooking_time'];
    $difficulty_level = $_POST['difficulty_level'];
    $category = $_POST['category'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO recipes (title, ingredients, instructions, cooking_time, difficulty_level, category, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssissi", $title, $ingredients, $instructions, $cooking_time, $difficulty_level, $category, $user_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="add_recipe.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Recipe</title>
    <link rel="stylesheet" href="cooking-recipe-CSS.css">
</head>
<body>
    <div class="container">
        <h1>Add Recipe</h1>
        <form action="add_recipe.php" method="POST">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            <label for="ingredients">Ingredients:</label>
            <textarea id="ingredients" name="ingredients" rows="5" required></textarea>
            <label for="instructions">Instructions:</label>
            <textarea id="instructions" name="instructions" rows="5" required></textarea>
            <label for="cooking_time">Cooking Time (mins):</label>
            <input type="number" id="cooking_time" name="cooking_time" required>
            <label for="difficulty_level">Difficulty Level:</label>
            <select id="difficulty_level" name="difficulty_level" required>
                <option>Easy</option>
                <option>Medium</option>
                <option>Hard</option>
            </select>
            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option>Breakfast</option>
                <option>Lunch</option>
                <option>Dinner</option>
                <option>Snack</option>
            </select>
            <button type="submit">Add Recipe</button>
        </form>
    </div>
</body>
</html>
