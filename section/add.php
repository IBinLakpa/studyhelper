<?php
// section/add.php?id=XX or section/add/XX
require '../essentials/db_access.php';
require '../essentials/access_check.php';
require '../essentials/edit_access.php';

$id = (int)$_GET['id'];

// Fetch chapter
$stmt = $pdo->prepare("SELECT name FROM chapter WHERE id = ?");
$stmt->execute([$id]);
$chapter = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if chapter exists
if (!$chapter) {
    header('Location: ../home/');
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $order_no = (int)$_POST['order_no'];
    $body = trim($_POST['body']);

    // Prepare and execute the insert statement
    $stmt = $pdo->prepare("INSERT INTO section (name, order_no, chapter_id, body) VALUES (:name, :order_no, :chapter_id, :body)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':body', $body);
    $stmt->bindParam(':order_no', $order_no, PDO::PARAM_INT);
    $stmt->bindParam(':chapter_id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header('Location: /chapter/' . $id);
        exit();
    } else {
        $message = "Error: Could not add section.";
    }
}

$title = 'Add Section';
$banner = false;
$form = true;
$editor = true;
$edit_options = '';
$right_sidebar_options = '';
$left_sidebar_options = '';

function main_article() {
    global $message, $chapter;
    echo '
    <form class="main" method="post" action="">
        <div class="default">
            <h3>Add Section to ' . htmlspecialchars($chapter['name'], ENT_QUOTES) . '</h3>
        </div>
        <div class="default">
            ' . htmlspecialchars($message, ENT_QUOTES) . '
        </div>
        <div class="default">
            <input type="text" id="name" maxlength="255" name="name" pattern="[A-Za-z0-9\- ]+" required>
            <label for="name">Section Name:</label>
        </div>
        <div class="default">
            <input type="number" id="order_no" name="order_no" min="1" max="255" step="1" required>
            <label for="order_no">Order Number:</label>
        </div>
        <div class="editor">
            <label for="body">Main Body:</label>
            <textarea id="body" name="body" required></textarea>
        </div>
        <div class="default">
            <button type="submit">Save</button>
        </div>
    </form>';
}

require '../essentials/default.php';