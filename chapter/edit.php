<?php
// chapter/edit.php?id=XX or chapter/edit/XX
require '../essentials/db_access.php';
require '../essentials/access_check.php';
require '../essentials/edit_access.php';

$id = (int)$_GET['id'];

// Fetch chapter
$stmt = $pdo->prepare("SELECT name,intro,credit_hour,order_no FROM chapter WHERE id = ?");
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
    $intro = trim($_POST['intro']);
    $credit_hour = (int)$_POST['credit_hour'];
    $order_no = (int)$_POST['order_no'];
    $subject_id = $id;

    // Prepare and execute the insert statement
    $stmt = $pdo->prepare("UPDATE chapter 
                            SET 
                                name=:name,
                                intro=:intro,
                                credit_hour=:credit_hour,
                                order_no=:order_no
                            WHERE id = :id
                        ");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':intro', $intro);
    $stmt->bindParam(':credit_hour', $credit_hour, PDO::PARAM_INT);
    $stmt->bindParam(':order_no', $order_no, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header('Location: /chapter/' . $id);
        exit();
    } else {
        $message = "Error: Could not edit chapter.";
    }
}

$title = 'Edit Chapter';
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
            <h3>Edit Chapter ' . htmlspecialchars($chapter['name'], ENT_QUOTES) . '</h3>
        </div>
        <div class="default">
            ' . htmlspecialchars($message, ENT_QUOTES) . '
        </div>
        <div class="default">
            <input type="text" id="name" maxlength="255" name="name" pattern="[A-Za-z0-9\- ]+"  value="'.$chapter['name'].'" required>
            <label for="name">Chapter Name:</label>
        </div>
        <div class="default">
            <input type="number" id="credit_hour" name="credit_hour" min="1" max="255" step="1" pattern="^[1-4]$"  value="'.$chapter['credit_hour'].'" required>
            <label for="credit_hour">Credit Hour:</label>
        </div>
        <div class="default">
            <input type="number" id="order_no" name="order_no" min="1" max="255" step="1"  value="'.$chapter['order_no'].'" required>
            <label for="order_no">Order Number:</label>
        </div>
        <div class="editor">
            <label for="intro">Chapter Introduction:</label>
            <textarea id="intro" name="intro" required> '.$chapter['intro'].'</textarea>
        </div>
        <div class="default">
            <button type="submit">Save Changes?</button>
        </div>
    </form>';
}

require '../essentials/default.php';

