<?php
// category/edit.php
require '../essentials/db.php';
require '../essentials/editor_access.php';

// Fetch all categories
$stmt = $pdo->query("SELECT id, name FROM Category ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = trim($_POST['name']);

    if (!empty($name) && preg_match("/^[a-zA-Z0-9\s]+$/", $name)) {
        $stmt = $pdo->prepare("UPDATE Category SET name = :name, updated = CURRENT_DATE WHERE id = :id");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            $message = "Category updated successfully.";
        } else {
            $message = "Error: Could not update category.";
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
    <title>Edit Category</title>
    <script src="../essentials/script.js"></script>
    <script src="script.js?55"></script>
    <link rel="stylesheet" href="../css/normalize.css"> 
    <link rel="stylesheet" href="../css/skeleton.css"> 
</head>
<body>
    <?php
    require '../essentials/header.php';
    ?>
    <form method="post" action="edit.php" onsubmit="return validateForm('name', 'category')">
        <h3>Edit Category</h3>
        <label for="id">Select Category:</label>
        <select id="id" name="id" onchange="populateName()" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['id']); ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label for="name">New Category Name:</label>
        <input type="text" id="name" name="name" required>
        <button type="submit">Update Category</button>
        <?php echo $message; ?>
    </form>
    <?php
        include '../essentials/footer.php';
    ?>
</body>
</html>
