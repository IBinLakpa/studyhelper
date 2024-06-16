<?php
// chapter/add.php
require '../essentials/db.php';
require '../essentials/editor_access.php';

$message = "";
$categories = [];

// Fetch all categories
$stmt = $pdo->query("SELECT id, name FROM Category ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $creditHour = $_POST['creditHour'];
    $order_no = $_POST['order_no'];
    $intro = $_POST['intro'];
    $subject_id = $_POST['subject_id'];

    // Insert new chapter into the database
    $stmt = $pdo->prepare("INSERT INTO Chapter (name, creditHour, order_no, intro, subject_id, created, updated) VALUES (:name, :creditHour, :order_no, :intro, :subject_id, CURDATE(), CURDATE())");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':creditHour', $creditHour);
    $stmt->bindParam(':order_no', $order_no);
    $stmt->bindParam(':intro', $intro);
    $stmt->bindParam(':subject_id', $subject_id);

    if ($stmt->execute()) {
        $message = "Chapter added successfully.";
    } else {
        $message = "Error: Could not add chapter.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Chapter</title>
    <script src="../essentials/script.js?554"></script>
    <script src="script.js"></script>
    <link rel="stylesheet" href="../css/normalize.css"> 
    <link rel="stylesheet" href="../css/skeleton.css"> 
</head>
<body>
    <?php
        require '../essentials/header.php';
    ?>
    <form method="post" action="add.php" onsubmit="return validateForm('name', 'chapter')">
        <h3>Add Chapter</h3>
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
        <select id="subject_id" name="subject_id" required>
            <option value="" selected disabled hidden>Select Subject</option>
        </select>
        <label for="name">Chapter Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="creditHour">Credit Hours:</label>
        <input type="number" id="creditHour" name="creditHour" required>
        <label for="order_no">Order No:</label>
        <input type="number" id="order_no" name="order_no" required>
        <label for="intro">Intro:</label>
        <input type="text" id="intro" name="intro" required>
        <button type="submit">Add Chapter</button>
        <?php echo $message; ?>
    </form>
    <?php
        require '../essentials/footer.php';
    ?>
</body>
</html>
