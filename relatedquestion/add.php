<?php
// relatedquestions/add.php
require '../essentials/db.php';
require '../essentials/editor_access.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $section_id = $_POST['section_id'];
    $question_id = $_POST['question_id'];

    // Insert into the question_relation table
    $stmt = $pdo->prepare("INSERT INTO question_relation (section_id, question_id) VALUES (?, ?)");
    $stmt->execute([$section_id, $question_id]);

    header("Location: index.php?id=$section_id");
    exit();
}

// Fetch categories for the form
$categories = $pdo->query("SELECT id, name FROM category")->fetchAll(PDO::FETCH_ASSOC);
$questions = $pdo->query("SELECT id, question FROM questions")->fetchAll(PDO::FETCH_ASSOC);

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
</head>
<body>
    <?php require '../essentials/header.php'; ?>
    <div class="container">
        <h2>Add Related Question</h2>
        <form method="post" action="">
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

            <label for="question_id">Select Question:</label>
            <select id="question_id" name="question_id">
                <option value="" selected disabled hidden>Select Question</option>
                <?php foreach ($questions as $question): ?>
                    <option value="<?php echo $question['id']; ?>">
                        <?php echo htmlspecialchars($question['id']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Add Related Question</button>
        </form>
    </div>
    <?php require '../essentials/footer.php'; ?>
</body>
</html>
