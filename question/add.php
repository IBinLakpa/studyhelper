<?php
// question/add or question/add.php
require '../essentials/db_access.php';
require '../essentials/access_check.php';
require '../essentials/edit_access.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $q = trim($_POST['q']);
    $a = trim($_POST['a']);

    // Prepare and execute the insert statement
    $stmt = $pdo->prepare("INSERT INTO question (q, a) VALUES (:q, :a)");
    $stmt->bindParam(':q', $q);
    $stmt->bindParam(':a', $a);

    if ($stmt->execute()) {
        $lastId = $pdo->lastInsertId();
        header('Location: /question/' . $lastId);
        exit();
    } else {
        $message = "Error: Could not add chapter.";
    }
}

$title = 'Add Question';
$banner = false;
$form = true;
$editor = true;
$right_sidebar_options = '';
$left_sidebar_options = '';

function main_article() {
    global $message;
    echo '
    <form class="main" method="post" action="">
        <div class="default">
            <h3>Add Question</h3>
        </div>
        <div class="default">
            ' . htmlspecialchars($message, ENT_QUOTES) . '
        </div>
        <div class="editor">
            <label for="q">Question:</label>
            <textarea id="q" name="q" required></textarea>
        </div>
        <div class="editor">
            <label for="a">Answer:</label>
            <textarea id="a" name="a" required></textarea>
        </div>
        <div class="default">
            <button type="submit">Save</button>
        </div>
    </form>';
}

require '../essentials/default.php';
?>
