<?php
// subject/add.php?id=XX
// or subject/XX/add

require '../essentials/db_access.php';
require '../essentials/access_check.php';
require '../essentials/admin_access.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch category
$stmt = $pdo->prepare("SELECT name, id FROM category WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if category exists
if (!$category) {
    header('Location: ../home/errorhere');
    exit();
}
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $level = trim($_POST['level']);
    
    // Prepare and execute the insert statement
    $stmt = $pdo->prepare("INSERT INTO subject (name, level, categoryId) VALUES (:name, :level, :categoryId)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':level', $level);
    $stmt->bindParam(':categoryId', $id);
    
    if ($stmt->execute()) {
        $lastId = $pdo->lastInsertId();
        header('Location: /subject/' . $lastId);
        exit();
    } else {
        $message = "Error: Could not add Subject.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Subject</title>
    <style rel="stylesheet" href="../style/global.css"></style>
    <?php
        @include '../essentials/global_style.php';
        @include '../essentials/global_script.php';
    ?>
</head>
<body>
    <?php
        require '../essentials/header.php';
    ?>
    <form method="post" action="">
        <h3>Add Subject to <?php echo htmlspecialchars($category['name']); ?></h3>
        <label for="name">Subject Name:</label>
        <input type="text" id="name" maxlength="255" name="name" pattern="[A-Za-z0-9\- ]+" required>
        <label for="code">Subject Code:</label>
        <input type="text" id="code" maxlength="30" name="code" pattern="[A-Za-z0-9\- ]+" required>
        <label for="credit">Subject Credit:</label>
        <input type="num" id="credit" max="5" min="1" name="credit" pattern="[A-Za-z0-9\- ]+" required>
        
        <button type="submit">Save Subject</button>
        <?php 
            if (!empty($message)) {
                echo '<p>' . htmlspecialchars($message) . '</p>'; 
            }
        ?>
    </form>
    <?php
        include '../essentials/footer.php';
    ?>
</body>
</html>
