<?php
// section/add.php
require '../essentials/db.php';
require '../essentials/editor_access.php';

$message = "";
$categories = [];

// Fetch all categories
$stmt = $pdo->query("SELECT id, name FROM Category ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $section = [
        'name' => $_POST['name'],
        'order_no' => $_POST['order_no'],
        'body' => $_POST['body'],
        'chapter_id' => $_POST['chapter_id']
    ];

    // Insert section details into the database
    $stmt = $pdo->prepare("INSERT INTO Section (name, order_no, body, chapter_id, created) VALUES (:name, :order_no, :body, :chapter_id, CURDATE())");
    $stmt->execute($section);

    $message = "Section added successfully.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Section</title>
    <link rel="stylesheet" href="../css/normalize.css"> 
    <link rel="stylesheet" href="../css/skeleton.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
</head>
<body>
    <?php
        require '../essentials/header.php';
    ?>
    <form method="post" action="add.php">
        <h3>Add Section</h3>
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
        <select id="chapter_id" name="chapter_id">
            <option value="" selected disabled hidden>Select Chapter</option>
        </select>
        <label for="name">Section Name:</label>
        <input type="text" id="name" name="name" maxlength="255" required>
        <label for="order_no">Order No:</label>
        <input type="number" id="order_no" step="1" max="500" min="0" name="order_no" required>
        <label for="body">Body:</label>
        <textarea id="editor" name="body" required></textarea>
        <button type="submit">Add Section</button>
        <?php echo $message; ?>
    </form>
    <?php
        require '../essentials/footer.php';
    ?>
</body>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<script src="../essentials/script.js"></script>
<script>
    const quill = new Quill('#editor', {
        modules: {
    toolbar: true,
  },
  placeholder: 'Write a subchapter...',
  theme: 'snow', // or 'bubble'
});
</script>
</html>
