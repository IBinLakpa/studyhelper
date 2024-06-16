<?php
// chapter/delete.php
require '../essentials/db.php';
require '../essentials/editor_access.php';

$message = "";
$categories = [];

// Fetch all categories
$stmt = $pdo->query("SELECT id, name FROM Category ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $chapter_id = $_POST['chapter_id'];

    // Delete chapter from the database
    $stmt = $pdo->prepare("DELETE FROM Chapter WHERE id = :id");
    $stmt->bindParam(':id', $chapter_id);

    if ($stmt->execute()) {
        $message = "Chapter deleted successfully.";
    } else {
        $message = "Error: Could not delete chapter.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Chapter</title>
    <script src="../essentials/script.js?554"></script>
</head>
<body>
    <?php
        require '../essentials/header.php';
    ?>
    <form method="post" action="delete.php" onsubmit="return confirm('Are you sure you want to delete this chapter?');">
        <h3>Delete Chapter</h3>
        <label for="category_id">Select Category:</label>
        <select id="category_id" name="category_id" onchange="getSubjectList('subject_id',this.value)" required>
            <option value="" selected disabled hidden>Select Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['id']); ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label for="subject_id">Select Subject:</label>
        <select id="subject_id" name="subject_id" onchange="getChapterList('chapter_id',this.value)" required>
            <option value="" selected disabled hidden>Select Subject</option>
        </select>
        <label for="chapter_id">Select Chapter:</label>
        <select id="chapter_id" name="chapter_id" required>
            <option value="" selected disabled hidden>Select Chapter</option>
            <!-- This option will be populated dynamically using JavaScript -->
        </select>
        <button type="submit">Delete Chapter</button>
        <?php echo $message; ?>
    </form>
    <?php
        require '../essentials/footer.php';
    ?>
</body>
</html>
