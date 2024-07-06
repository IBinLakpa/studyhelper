<?php
// subject/add.php
require '../essentials/db_access.php';
require '../essentials/access_check.php';
require '../essentials/admin_access.php';

$id = (int)$_GET['id'];

// Fetch category
$stmt = $pdo->prepare("SELECT name FROM category WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if category exists
if (!$category) {
    header('Location: ../home/');
    exit();
}

$message = "";

// Fetch subjects from category
$stmt = $pdo->prepare("SELECT id, name FROM subject WHERE category_id = ?");
$stmt->execute([$id]);
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generating options for the prerequisite subject
$subject_options = '';
if ($subjects) {
    $subject_options.= '<option value="">None</option>';
    foreach ($subjects as $subject) {
        $subject_options .= "
        <option value='" . htmlspecialchars($subject['id'], ENT_QUOTES) . "'>
            " . htmlspecialchars($subject['name'], ENT_QUOTES) . "
        </option>";
    }
} else {
    $subject_options.= '<option value="" selected disabled hidden>No subjects available</option>';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $credits = (int)$_POST['credits'];
    $code = trim($_POST['code']);
    $category_id = $id; // Use the category ID from the URL
    $prerequisite_id = !empty($_POST['prerequisite_id']) ? (int)$_POST['prerequisite_id'] : null;

    // Prepare and execute the insert statement
    $stmt = $pdo->prepare("INSERT INTO subject (name, credits, code, category_id, prerequisite_id) VALUES (:name, :credits, :code, :category_id, :prerequisite_id)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':credits', $credits, PDO::PARAM_INT);
    $stmt->bindParam(':code', $code);
    $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    $stmt->bindParam(':prerequisite_id', $prerequisite_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $lastId = $pdo->lastInsertId();
        header('Location: /subject/' . $lastId);
        exit();
    } else {
        $message = "Error: Could not add subject.";
    }
}

$title = 'Add Subject';
$banner = false;
$form = true;
$editor = false;
$edit_options = '';
$right_sidebar_options = '';
$left_sidebar_options = '';

function main_article() {
    global $message, $subject_options, $category;
    echo '
    <form class="main" method="post" action="">
        <div class="default">
            <h3>Add Subject to '.$category['name'].'</h3>
        </div>
        <div class="default">
            ' . htmlspecialchars($message, ENT_QUOTES) . '
        </div>
        <div class="default">
            <input type="text" id="name" maxlength="255" name="name" pattern="[A-Za-z0-9\- ]+" required>
            <label for="name">Subject Name:</label>
        </div>
        <div class="default">
            <input type="number" id="credits" name="credits" min="0" max="255" step="1" pattern="^[1-4]$" required>
            <label for="credits">Credits:</label>
        </div>
        <div class="default">
            <input type="text" id="code" maxlength="6" name="code" pattern="^[A-Z]{3}[0-9]{3}$" required>
            <label for="code">Code:</label>
        </div>
        <div class="default">
            <select name="prerequisite_id" id="prerequisite_id">
                ' . $subject_options . '
            </select>
            <label for="prerequisite_id">Prerequisite Subject (Optional):</label>
        </div>
        <div class="default">
            <button type="submit">Save</button>
        </div>
    </form>';
}

require '../essentials/default.php';
