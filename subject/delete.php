<?php
// subject/delete.php
require '../essentials/db.php';
require '../essentials/editor_access.php';

$message = "";

// Fetch all categories
$stmt = $pdo->query("SELECT id, name FROM Category ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Check if the subject is referenced in another subject as a prerequisite
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Subject WHERE prerequisite_id = ?");
    $stmt->execute([$id]);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        // Delete the subject
        $stmt = $pdo->prepare("DELETE FROM Subject WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            $message = "Subject deleted successfully.";
        } else {
            $message = "Error: Could not delete subject.";
        }
    } else {
        $message = "Error: Cannot delete subject as it is referenced by another subject.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Subject</title>
    <script src="../essentials/script.js?554"></script>
    <script src="script.js"></script>
    <link rel="stylesheet" href="../css/normalize.css"> 
    <link rel="stylesheet" href="../css/skeleton.css"> 
</head>
<body>
    <?php
        require '../essentials/header.php';
    ?>
    <form method="post" action="delete.php">
        <h3>Delete Subject</h3>
        <label for="category_id">Select Category:</label>
        <select id="category_id" name="category_id" onchange="getSubjectList('subject_id', this.value)">
            <option value="" selected disabled hidden>Select Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['id']); ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="subject_id">Select Subject:</label>
        <select id="subject_id" name="id">
            <option value="" selected disabled hidden>Select Subject</option>
            <!-- This option will be populated dynamically using JavaScript -->
        </select>
        <br>
        
        <button type="submit" onclick="return confirmDelete()">Delete Subject</button>
        <?php echo $message; ?>
    </form>
    <?php
        require '../essentials/footer.php';
    ?>
</body>
</html>
