<?php
// institute/add.php
require '../essentials/db_access.php';
require '../essentials/access_check.php';
require '../essentials/admin_access.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $cover = trim($_POST['cover']);

    // Prepare and execute the insert statement
    $stmt = $pdo->prepare("INSERT INTO institute (name, cover_url) VALUES (:name, :cover)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':cover', $cover);

    if ($stmt->execute()) {
        $lastId = $pdo->lastInsertId();
        header('Location: /institute/'.$lastId);
    } else {
        $message = "Error: Could not add Institute.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Institute</title>
    <base href="/">
    <link rel="stylesheet" href="styles/global.css"/>    
    <script src="scripts/global.js" defer></script>
</head>
<body>
    <?php
        require '../essentials/header.php';
    ?>
    <form method="post" action="">
        <h3>Add Institute</h3>
        <label for="name">Institute Name:</label>
        <input type="text" id="name" maxlength="255" name="name" pattern="[A-Za-z0-9\- ]+" required>
        <label for="cover">Institute Logo Url:</label>
        <input type="text" id="cover" maxlength="255" name="cover" required>
        <button type="submit">Add Institute</button>
        <?php 
            if (!empty($message)) {
                echo '<p>'.$message.'</p>'; 
            }
        ?>
    </form>
    <?php
        include '../essentials/footer.php';
    ?>
</body>
</html>
