<?php
// chapter/edit.php
require '../essentials/db.php';
require '../essentials/editor_access.php';

$message = "";

// Fetch all categories
$stmt = $pdo->query("SELECT id, name FROM Category ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize chapter details
$chapter = [
    'id' => '',
    'name' => '',
    'creditHour' => '',
    'order_no' => '',
    'intro' => '',
    'subject_id' => ''
];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $chapter['id'] = $_POST['chapter_id'];
    $chapter['name'] = $_POST['name'];
    $chapter['creditHour'] = $_POST['creditHour'];
    $chapter['order_no'] = $_POST['order_no'];
    $chapter['intro'] = $_POST['intro'];
    $chapter['subject_id'] = $_POST['new_subject_id'];

    // Update chapter details in the database
    $stmt = $pdo->prepare("UPDATE Chapter SET name = :name, creditHour = :creditHour, order_no = :order_no, intro = :intro, subject_id = :subject_id, updated = CURDATE() WHERE id = :id");
    $stmt->execute($chapter);

    $message = "Chapter updated successfully.";
}

// Fetch chapter details if chapter is selected
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM Chapter WHERE id = ?");
    $stmt->execute([$id]);
    $chapter = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Chapter</title>
    <script src="../essentials/script.js?554"></script>
    <script src="script.js"></script>
    <link rel="stylesheet" href="../css/normalize.css"> 
    <link rel="stylesheet" href="../css/skeleton.css"> 
</head>
<body>
    <?php
        require '../essentials/header.php';
    ?>
    <form method="post" action="edit.php">
        <h3>Edit Chapter</h3>
        <label for="category_id">Select Category:</label>
        <select id="category_id" name="category_id" onchange="getSubjectList('subject_id',this.value)">
            <option value="" selected disabled hidden>Select Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label for="subject_id">Select Subject:</label>
        <select id="subject_id" name="subject_id" onchange="getChapterList('chapter_id',this.value)">
            <option value="" selected disabled hidden>Select Subject</option>
        </select>
        <label for="chapter_id">Select Chapter:</label>
        <select id="chapter_id" name="chapter_id" onchange="getChapterDetails(this.value)">
            <option value="" selected disabled hidden>Select Chapter</option>
        </select>
        <label for="new_category_id">New Chapter Category:</label>
        <select id="new_category_id" name="new_category_id" onchange="getSubjectList('new_subject_id',this.value)">
            <option value="" selected disabled hidden>Select Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label for="new_subject_id">New Chapter Subject:</label>
        <select id="new_subject_id" name="new_subject_id" onchange="getChapterList('new_chapter_id',this.value)">
            <option value="" selected disabled hidden>Select Subject</option>
        </select>
        <label for="name">New Chapter Name:</label>
        <input type="text" id="name" name="name" maxlength="255" value="<?php echo htmlspecialchars($chapter['name']); ?>">
        <label for="creditHours">Credit Hours:</label>
        <input type="number" id="creditHours" step="1" max="5" min="0" name="creditHour" value="<?php echo htmlspecialchars($chapter['creditHour']); ?>">
        <label for="order_no">Order No:</label>
        <input type="number" id="order_no" step="1" max="500" min="0" name="order_no" value="<?php echo htmlspecialchars($chapter['order_no']); ?>">
        
        <label for="intro">Intro:</label>
        <input type="text" id="intro" name="intro" maxlength="255" value="<?php echo htmlspecialchars($chapter['intro']); ?>">
        
        <button type="submit">Update Chapter</button>
        <?php echo $message; ?>
    </form>
    <?php
        require '../essentials/footer.php';
    ?>
</body>
</html>
