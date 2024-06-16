<?php
// category/delete.php
require '../essentials/db.php';
require '../essentials/editor_access.php';

$message = "";

// Handle delete request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Check if the category is referenced in the subject table
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Subject WHERE category_id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        // Prepare and execute the delete statement
        $stmt = $pdo->prepare("DELETE FROM Category WHERE id = :id");
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            $message = "Category deleted successfully.";
        } else {
            $message = "Error: Could not delete category.";
        }
    } else {
        $message = "Error: Cannot delete category because it is referenced in the subject table.";
    }
}

// Fetch all categories
$stmt = $pdo->query("SELECT id, name FROM Category ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Category</title>
    <script src="../essentials/script.js"></script>
    <link rel="stylesheet" href="../css/normalize.css"> 
    <link rel="stylesheet" href="../css/skeleton.css"> 
</head>
<body>
    <?php
        require '../essentials/header.php';
    ?>
    <h3>Delete Category</h3>
    <form method="post" action="delete.php" onsubmit="return confirmDelete()">
        <label for="id">Select Category:</label>
        <select id="id" name="id" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['id']); ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Delete Category</button>
        <?php echo $message; ?>
    </form>
    <?php
        include '../essentials/footer.php';
    ?>
</body>
</html>
