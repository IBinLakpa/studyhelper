<?php
require '../essentials/db.php';
require '../essentials/editor_access.php';

$message = "";

// Fetch all categories
$stmt = $pdo->query("SELECT id, name FROM Category ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize subject details
$subject = [
    'id' => '',
    'name' => '',
    'credit' => '',
    'category_id' => '',
    'prerequisite_id' => ''
];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject['id'] = $_POST['subject_id'];
    $subject['name'] = $_POST['name'];
    $subject['credit'] = $_POST['credit'];
    $subject['category_id'] = $_POST['new_category_id'];
    $subject['prerequisite_id'] = !empty($_POST['prerequisite_id'])? $_POST['prerequisite_id']:null;

    // Update subject details in the database
    $stmt = $pdo->prepare("UPDATE Subject SET name = :name, credit = :credit, category_id = :category_id, prerequisite_id = :prerequisite_id, updated = CURDATE() WHERE id = :id");
    $stmt->execute($subject);

    $message = "Subject updated successfully.";
}

// Fetch subject details if subject is selected
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM Subject WHERE id = ?");
    $stmt->execute([$id]);
    $subject = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Subject</title>
    <script src="../essentials/script.js?554"></script>
    <script src="script.js"></script>
    <link rel="stylesheet" href="../css/normalize.css"> 
    <link rel="stylesheet" href="../css/skeleton.css"> 
</head>
<body>
    <?php
        require '../essentials/header.php';
    ?>
    <form method="post" action="edit.php" onsubmit="return validateForm('name', 'category')">
        <h3>Edit Subject</h3>
        <label for="category_id">Select Category:</label>
        <select id="category_id" name="category_id" onchange="getSubjectList('subject_id',this.value)">
            <option value="" selected disabled hidden>Select Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>" <?php if ($subject['category_id'] == $category['id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label for="subject_id">Select Subject:</label>
        <select id="subject_id" name="subject_id" onchange="getSubjectDetails(this.value)">
            <option value="" selected disabled hidden>Select Subject</option>
            <?php if ($subject['id']): ?>
                <option value="<?php echo $subject['id']; ?>" selected>
                    <?php echo htmlspecialchars($subject['name']); ?>
                </option>
            <?php endif; ?>
        </select>
        <label for="name">Subject Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($subject['name']); ?>">
        <label for="new_category_id">Subject Category:</label>
        <select id="new_category_id" name="new_category_id"  required>
            <option value="" selected disabled hidden>Select Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['id']); ?>" <?php if ($subject['category_id'] == $category['id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label for="credit">Credits:</label>
        <input type="number" id="credit" name="credit" value="<?php echo htmlspecialchars($subject['credit']); ?>">
        <label for="prerequisite_category_id">Select Prerequisite Category:</label>
        <select id="prerequisite_category_id" name="prerequisite_category_id" onchange="getSubjectList('prerequisite_id',this.value)">
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
        <button type="submit">Update Subject</button>
        <?php echo $message; ?>
    </form>
    <?php
        require '../essentials/footer.php';
    ?>
</html>
