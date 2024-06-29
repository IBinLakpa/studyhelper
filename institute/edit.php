<?php
// institute/edit.php?id=XX
// or institute/XX/edit

require '../essentials/db_access.php';
require '../essentials/access_check.php';
require '../essentials/admin_access.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch institute
$stmt = $pdo->prepare("SELECT * FROM institute WHERE id = ?");
$stmt->execute([$id]);
$institute = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if institute exists
if (!$institute) {
    header('Location: ../home/');
    exit();
}
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $cover = trim($_POST['cover']);
    
    // Prepare and execute the update statement
    $stmt = $pdo->prepare("UPDATE institute SET name = :name, cover_url = :cover WHERE id = :id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':cover', $cover);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        header("Location: /institute/$id");
        exit();
    } else {
        $message = "Error: Could not update Institute.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit <?php echo htmlspecialchars($institute['name']); ?></title>
    <base href="/">
    <link rel="stylesheet" href="styles/global.css"/>    
    <script src="scripts/global.js" defer></script>
</head>
<body>
    <?php
        require '../essentials/header.php';
    ?>
    <form method="post" action="">
        <h3>Edit <?php echo htmlspecialchars($institute['name']); ?></h3>
        <label for="name">Institute Name:</label>
        <input type="text" id="name" maxlength="255" name="name" pattern="[A-Za-z0-9\- ]+" value="<?php echo htmlspecialchars($institute['name']); ?>" required>
        <label for="cover">Institute Logo Url:</label>
        <input type="text" id="cover" maxlength="255" name="cover" value="<?php echo htmlspecialchars($institute['cover_url']); ?>" required>
        <button type="submit">Save Changes?</button>
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
