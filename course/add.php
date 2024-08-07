<?php
// course/add.php
require '../essentials/db_access.php';
require '../essentials/access_check.php';
require '../essentials/admin_access.php';

$id = (int)$_GET['id'];

// Fetch institute
$stmt = $pdo->prepare("SELECT name FROM institute WHERE id = ?");
$stmt->execute([$id]);
$institute = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if institute exists
if (!$institute) {
    header('Location: ../home/');
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $level = $_POST['level'];
    $institute_id = $id; // Use the institute ID from the URL

    // Prepare and execute the insert statement
    $stmt = $pdo->prepare("INSERT INTO course (name, level, institute_id) VALUES (:name, :level, :institute_id)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':level', $level);
    $stmt->bindParam(':institute_id', $institute_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $lastId = $pdo->lastInsertId();
        header('Location: /course/' . $lastId);
        exit();
    } else {
        $message = "Error: Could not add course.";
    }
}

$title = 'Add Course';
$banner = false;
$form = true;
$editor = false;
$edit_options = '';
$right_sidebar_options = '';
$left_sidebar_options = '';

function main_article() {
    global $message, $institute;
    echo '
    <form class="main" method="post" action="">
        <div class="default">
            <h3>Add Course to '.$institute['name'].'</h3>
        </div>
        <div class="default">
            ' . htmlspecialchars($message, ENT_QUOTES) . '
        </div>
        <div class="default">
            <input type="text" id="name" maxlength="255" name="name" pattern="[A-Za-z0-9\- ]+" required>
            <label for="name">Course Name:</label>
        </div>
        <div class="default">
            <select name="level" id="level" required>
                <option value="A-Level">A-Level</option>
                <option value="Bachelor" selected>Bachelor</option>
                <option value="Master">Master</option>
                <option value="Phd">Phd</option>
            </select>
            <label for="code">Level:</label>
        </div>
        <div class="default">
            <button type="submit">Save</button>
        </div>
    </form>';
}

require '../essentials/default.php';
