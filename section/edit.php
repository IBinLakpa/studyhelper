<?php
// section/edit.php
require '../essentials/db.php';
require '../essentials/editor_access.php';

$message = "";
$categories = [];

// Fetch all categories
$stmt = $pdo->query("SELECT id, name FROM Category ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize section details
$section = [
    'id' => '',
    'name' => '',
    'order_no' => '',
    'body' => '',
    'chapter_id' => ''
];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $section['id'] = $_POST['section_id'];
    $section['name'] = $_POST['name'];
    $section['order_no'] = $_POST['order_no'];
    $section['body'] = $_POST['body'];
    $section['chapter_id'] = isset($_POST['new_chapter_id']) ? $_POST['new_chapter_id'] : $_POST['chapter_id'];

    // Update section details in the database
    try {
        $stmt = $pdo->prepare("UPDATE Section SET name = :name, order_no = :order_no, body = :body, chapter_id = :chapter_id, updated = CURDATE() WHERE id = :id");
        $stmt->execute($section);
        $message = "Section updated successfully.";
    } catch (PDOException $e) {
        $message = "Error updating section: " . $e->getMessage();
    }
}

// Fetch section details if section is selected
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM Section WHERE id = ?");
    $stmt->execute([$id]);
    $section = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Section</title>
    <script src="../essentials/script.js?554"></script>
    <script src="script.js"></script>
    <link rel="stylesheet" href="../css/normalize.css"> 
    <link rel="stylesheet" href="../css/skeleton.css"> 
</head>
<body>
    <?php require '../essentials/header.php'; ?>
    <form method="post" action="edit.php">
        <h3>Edit Section</h3>
        <label for="category_id">Select Category:</label>
        <select id="category_id" name="category_id" onchange="getSubjectList('subject_id',this.value)" required>
            <option value="" selected disabled hidden>Select Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label for="subject_id">Select Subject:</label>
        <select id="subject_id" name="subject_id" onchange="getChapterList('chapter_id',this.value)" required>
            <option value="" selected disabled hidden>Select Subject</option>
        </select>
        <label for="chapter_id">Select Chapter:</label>
        <select id="chapter_id" name="chapter_id" onchange="getSectionList('section_id',this.value)"required>
            <option value="" selected disabled hidden>Select Chapter</option>
        </select>
        <label for="section_id">Select Section:</label>
        <select id="section_id" name="section_id" onchange="getSectionDetails(this.value)" required>
            <option value="" selected disabled hidden>Select Section</option>
        </select>
        <label for="new_category_id">Select New Category:</label>
        <select id="new_category_id" name="new_category_id" onchange="getSubjectList('new_subject_id',this.value)"required>
            <option value="" selected disabled hidden>Select Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label for="new_subject_id">Select New Subject:</label>
        <select id="new_subject_id" name="new_subject_id" onchange="getChapterList('new_chapter_id',this.value)"required>
            <option value="" selected disabled hidden>Select Subject</option>
        </select>
        <label for="new_chapter_id">Select New Chapter:</label>
        <select id="new_chapter_id" name="new_chapter_id"required>
            <option value="" selected disabled hidden>Select Chapter</option>
        </select>
        <label for="name">Section Name:</label>
        <input type="text" id="name" name="name" maxlength="255" value="<?php echo htmlspecialchars($section['name']); ?>" required>
        <label for="order_no">Order No:</label>
        <input type="number" id="order_no" step="1" max="500" min="0" name="order_no" value="<?php echo htmlspecialchars($section['order_no']); ?>" required>
        <label for="body">Body:</label>
        <textarea id="body" name="body"required><?php echo htmlspecialchars($section['body']); ?></textarea>
        
        <button type="submit">Update Section</button>
        <?php echo $message; ?>
    </form>
    <?php require '../essentials/footer.php'; ?>
</body>
</html>
