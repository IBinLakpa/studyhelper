<?php
// course/edit.php
require '../essentials/db_access.php';
require '../essentials/access_check.php';
require '../essentials/admin_access.php';

$id = (int)$_GET['id'];

// Fetch course
$stmt = $pdo->prepare("SELECT name,level FROM course WHERE id = ?");
$stmt->execute([$id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if course exists
if (!$course) {
    header('Location: ../home/');
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $level = $_POST['level'];

    // Prepare and execute the insert statement
    $stmt = $pdo->prepare("UPDATE course SET 
                            name=:name, 
                            level=:level
                            WHERE id = :id
                        ");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':level', $level);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header('Location: /course/' . $id);
        exit();
    } else {
        $message = "Error: Could not edit course.";
    }
}

$title = 'Edit Course';
$banner = false;
$form = true;
$editor = false;
$edit_options = '';
$right_sidebar_options = '';
$left_sidebar_options = '';

function main_article() {
    global $message, $course;
    echo '
    <form class="main" method="post" action="">
        <div class="default">
            <h3>Edit Course '.$course['name'].'</h3>
        </div>
        <div class="default">
            ' . htmlspecialchars($message, ENT_QUOTES) . '
        </div>
        <div class="default">
            <input type="text" id="name" maxlength="255" name="name" pattern="[A-Za-z0-9\- ]+" value="' . htmlspecialchars($course['name'], ENT_QUOTES) . '" required>
            <label for="name">Course Name:</label>
        </div>
        <div class="default">
            <select name="level" id="level" required>
                <option value="A-Level" ' . ($course['level'] == "A-Level" ? "selected" : "") . '>A-Level</option>
                <option value="Bachelor" ' . ($course['level'] == "Bachelor" ? "selected" : "") . '>Bachelor</option>
                <option value="Master" ' . ($course['level'] == "Master" ? "selected" : "") . '>Master</option>
                <option value="Phd" ' . ($course['level'] == "Phd" ? "selected" : "") . '>Phd</option>
            </select>
            <label for="code">Level:</label>
        </div>
        <div class="default">
            <button type="submit">Save</button>
        </div>
    </form>';
}

require '../essentials/default.php';
