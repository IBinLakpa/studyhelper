<?php
// category/add.php
require '../essentials/db.php';
require '../essentials/admin_access.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);

    if (!empty($name) && preg_match("/^[a-zA-Z0-9\s]+$/", $name)) {
        // Prepare and execute the insert statement
        $stmt = $pdo->prepare("INSERT INTO Category (name, created) VALUES (:name, CURDATE())");
        $stmt->bindParam(':name', $name);

        if ($stmt->execute()) {
            $message = "Category added successfully.";
        } else {
            $message = "Error: Could not add category.";
        }
    } else {
        $message = "Please enter a valid category name (alphabetic characters, numbers, and spaces only).";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Category</title>
    <link rel="stylesheet" href="../css/normalize.css"> 
    <link rel="stylesheet" href="../css/skeleton.css"> 
</head>
<body>
    <?php
        require '../essentials/header.php';
    ?>
    <form method="post" action="add.php" onsubmit="return validateForm('name', 'category')">
        <h3>Add Category</h3>
        <label for="name">Category Name:</label>
        <input type="text" id="name" name="name" required>
        <button type="submit">Add Category</button>
        <?php echo $message; ?>
    </form>
    <?php
        include '../essentials/footer.php';
    ?>
</body>
</html>
