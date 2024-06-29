<?php
// category/edit.php?id=XX
// or category/XX/edit

require '../essentials/db_access.php';
require '../essentials/access_check.php';
require '../essentials/admin_access.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch category
$stmt = $pdo->prepare("SELECT * FROM category WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if category exists
if (!$category) {
    header('Location: ../home/');
    exit();
}
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $cover = trim($_POST['cover']);
    
    // Prepare and execute the update statement
    $stmt = $pdo->prepare("UPDATE category SET name = :name, cover_url = :cover WHERE id = :id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':cover', $cover);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        header("Location: /category/$id");
        exit();
    } else {
        $message = "Error: Could not update Category.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit <?php echo htmlspecialchars($category['name']); ?></title>
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
        <h3>Edit <?php echo htmlspecialchars($category['name']); ?></h3>
        <label for="name">Category Name:</label>
        <input type="text" id="name" maxlength="255" name="name" pattern="[A-Za-z0-9\- ]+" value="<?php echo htmlspecialchars($category['name']); ?>" required>
        <label for="cover">Category Logo Url:</label>
        <input type="text" id="cover" maxlength="255" name="cover" value="<?php echo htmlspecialchars($category['cover_url']); ?>" required>
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
