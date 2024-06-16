<?php
// relatedquestions/delete.php
require '../essentials/db.php';
require '../essentials/editor_access.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the section ID and question ID from the form submission
    $section_id = $_POST['section_id'];
    $question_id = $_POST['question_id'];

    // Prepare and execute the SQL query to delete the related question
    $stmt = $pdo->prepare("DELETE FROM question_relation WHERE section_id = ? AND question_id = ?");
    $stmt->execute([$section_id, $question_id]);

    // Redirect back to the referring page (or any other desired location)
    header("Location: index.php?id=$section_id");
    
}
$categories = $pdo->query("SELECT id, name FROM category")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Related Question</title>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/skeleton.css">
    <script src="../essentials/script.js"></script>
    <script src="script.js"></script>
</head>
<body>
    <?php require '../essentials/header.php'; ?>
    <div class="container">
        <form method="post" action="">
            <h3>Delete Related Question</h3>
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
    <select id="section_id" name="section_id" onchange="populateQuestions(this.value)">
        <option value="" selected disabled hidden>Select Section</option>
        <?php foreach ($sections as $section): ?>
            <option value="<?php echo $section['id']; ?>">
                <?php echo htmlspecialchars($section['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="question_id">Select Question:</label>
    <select id="question_id" name="question_id">
        <option value="" selected disabled hidden>Select Question</option>
    </select>
    <button type="submit">Delete Relation</button>
</form>

    </div>
    <?php require '../essentials/footer.php'; ?>
</body>
</html>
