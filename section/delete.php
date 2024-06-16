<?php
// section/delete.php
require '../essentials/db.php';
require '../essentials/editor_access.php';

$message = "";
$categories = [];

// Fetch all categories
$stmt = $pdo->query("SELECT id, name FROM Category ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['section_id'])) {
    $section_id = $_POST['section_id'];

    // Delete section from the database
    $stmt = $pdo->prepare("DELETE FROM Section WHERE id = ?");
    $stmt->execute([$section_id]);

    $message = "Section deleted successfully.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Section</title>
    <script src="../essentials/script.js"></script>
    <script src="script.js"></script>
    <link rel="stylesheet" href="../css/normalize.css"> 
    <link rel="stylesheet" href="../css/skeleton.css"> 
</head>
<body>
    <?php
        require '../essentials/header.php';
    ?>
    <form method="post" action="delete.php">
        <h3>Delete Section</h3>
        <label for="category_id">Select Category:</label>
        <select id="category_id" name="category_id" onchange="getSubjectList('subject_id', this.value)">
            <option value="" selected disabled hidden>Select Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label for="subject_id">Select Subject:</label>
        <select id="subject_id" name="subject_id" onchange="getChapterList('chapter_id', this.value)">
            <option value="" selected disabled hidden>Select Subject</option>
        </select>
        <label for="chapter_id">Select Chapter:</label>
        <select id="chapter_id" name="chapter_id" onchange="getSectionList('section_id', this.value)">
            <option value="" selected disabled hidden>Select Chapter</option>
        </select>
        <label for="section_id">Select Section:</label>
        <select id="section_id" name="section_id">
            <option value="" selected disabled hidden>Select Section</option>
        </select>
        <button type="submit">Delete Section</button>
        <?php echo $message; ?>
    </form>
    <?php
        require '../essentials/footer.php';
    ?>
</body>
</body>
</html>
