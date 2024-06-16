<?php
// subject/add.php
require '../essentials/db.php';
require '../essentials/editor_access.php';

$message = "";
$categories = [];

// Fetch all categories
$stmt = $pdo->query("SELECT id, name FROM Category ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $credit = $_POST['credit'];
    $category_id = $_POST['category_id'];
    $prerequisite_id = !empty($_POST['prerequisite_id']) ? $_POST['prerequisite_id'] : null;

    // Insert new subject into the database
    $stmt = $pdo->prepare("INSERT INTO Subject (name, credit, category_id, prerequisite_id,  updated) VALUES (:name, :credit, :category_id, :prerequisite_id, CURDATE())");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':credit', $credit);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':prerequisite_id', $prerequisite_id);

    if ($stmt->execute()) {
        $message = "Subject added successfully.";
    } else {
        $message = "Error: Could not add subject.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Subject</title>
    <script src="../essentials/script.js?554"></script>
    <script src="script.js"></script>
    <link rel="stylesheet" href="../css/normalize.css"> 
    <link rel="stylesheet" href="../css/skeleton.css"> 
</head>
<body>
    <?php
        require '../essentials/header.php';
    ?>
    <form method="post" action="add.php" onsubmit="return validateForm('name', 'subject')">
        <h3>Add Subject</h3>
        <label for="category_id">Select Category:</label>
        <select id="category_id" name="category_id"  required>
            <option value="" selected disabled hidden>Select Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['id']); ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label for="name">Subject Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="credit">Credits:</label>
        <input type="number" step="1" min="1" max="5" id="credit" name="credit" required>
        <label for="prerequisite_category_id">Select Prerequisite Category:</label>
        <select id="prerequisite_category_id" name="prerequisite_category_id" onchange="getSubjectList('prerequisite_id', this.value)">
            <option value="" selected disabled hidden>Select a category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['id']); ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label for="prerequisite_id">Select Prerequisite Subject:</label>
        <select id="prerequisite_id" name="prerequisite_id">
            <option value="" selected disabled hidden>Select Subject</option>
            <!-- This option will be populated dynamically using JavaScript -->
            </select>        
        <button type="submit">Add Subject</button>
        <?php echo $message; ?>
    </form>
    <?php
        require '../essentials/footer.php';
    ?>
</body>
</html>
