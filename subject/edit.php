<?php
// subject/edit.php
require '../essentials/db_access.php';
require '../essentials/access_check.php';
require '../essentials/admin_access.php';

$id = (int)$_GET['id'];



$message = "";

// Fetch subject
$stmt = $pdo->prepare("SELECT * FROM subject WHERE id = ?");
$stmt->execute([$id]);
$subject = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch subjects from category
$stmt = $pdo->prepare("SELECT id, name FROM subject WHERE category_id = ? AND id != ?");
$stmt->execute([$subject['category_id'], $id]);
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generating options for the prerequisite subject dropdown
$subject_options = '';
if ($subjects) {
    foreach ($subjects as $subject_1) {
        $selected = ($subject_1['id'] === $subject['prerequisite_id']) ? "selected" : "";
        $subject_options .= "<option value='" . htmlspecialchars($subject_1['id'], ENT_QUOTES) . "' $selected>" . htmlspecialchars($subject_1['name'], ENT_QUOTES) . "</option>";
    }
} else {
    $subject_options = '<option value="" disabled>No subjects available</option>';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $credits = (int)$_POST['credits'];
    $code = trim($_POST['code']);
    $prerequisite_id = !empty($_POST['prerequisite_id']) ? (int)$_POST['prerequisite_id'] : null;

    // Prepare and execute the update statement
    $stmt = $pdo->prepare("UPDATE subject 
                           SET name = :name, 
                               credits = :credits, 
                               code = :code, 
                               prerequisite_id = :prerequisite_id 
                           WHERE id = :id");

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':credits', $credits, PDO::PARAM_INT);
    $stmt->bindParam(':code', $code);
    $stmt->bindParam(':prerequisite_id', $prerequisite_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header('Location: /subject/' . $id);
        exit();
    } else {
        $message = "Error: Could not update subject.";
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
    global $message, $subject_options, $subject;
    echo '
    <form class="main" method="post" action="">
        <div class="default">
            <h3>Edit '.$subject['name'].'</h3>
        </div>
        <div class="default">
            ' . htmlspecialchars($message, ENT_QUOTES) . '
        </div>
        <div class="default">
            <input type="text" id="name" maxlength="255" name="name" pattern="[A-Za-z0-9\- ]+" value="'.$subject['name'].'" required>
            <label for="name">Subject Name:</label>
        </div>
        <div class="default">
            <input type="number" id="credits" name="credits" min="0" max="6" step="1" pattern="^[1-4]$" value="'.$subject['credits'].'" required>
            <label for="credits">Credits:</label>
        </div>
        <div class="default">
            <input type="text" id="code" maxlength="6" name="code" pattern="^[A-Z]{3}[0-9]{3}$" value="'.$subject['code'].'" required>
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
