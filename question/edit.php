<?php
// question/edit or question/edit.php
require '../essentials/db_access.php';
require '../essentials/access_check.php';
require '../essentials/edit_access.php';

$message = "";

$id = (int)$_GET['id']; // Ensure ID is an integer for security

// Fetch question details
$stmt = $pdo->prepare("SELECT q, a FROM question WHERE id = ?");
$stmt->execute([$id]);
$question = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if question exists
if (!$question) {
    header('Location: ../home/');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $q = trim($_POST['q']);
    $a = trim($_POST['a']);

    // Prepare and execute the update statement
    $stmt = $pdo->prepare("UPDATE question SET q=:q, a=:a WHERE id=:id");
    $stmt->bindParam(':q', $q);
    $stmt->bindParam(':a', $a);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header('Location: /question/' . $id);
        exit();
    } else {
        $message = "Error: Could not add chapter.";
    }
}

$title = 'Edit Question';
$banner = false;
$form = true;
$editor = true;
$right_sidebar_options = '';
$left_sidebar_options = '';

function main_article() {
    global $message, $question;
    echo '
    <form class="main" method="post" action="">
        <div class="default">
            <h3>Edit Question</h3>
        </div>
        <div class="default">
            ' . htmlspecialchars($message, ENT_QUOTES) . '
        </div>
        <div class="editor">
            <label for="q">Question:</label>
            <textarea id="q" name="q" required>
                ' . htmlspecialchars($question['q'], ENT_QUOTES) . '
            </textarea>
        </div>
        <div class="editor">
            <label for="a">Answer:</label>
            <textarea id="a" name="a" required>
                ' . htmlspecialchars($question['a'], ENT_QUOTES) . '
            </textarea>
        </div>
        <div class="default">
            <button type="submit">Save</button>
        </div>
    </form>';
}

require '../essentials/default.php';
?>
