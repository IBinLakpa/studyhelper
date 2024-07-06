<?php
// institute/edit/XX
require '../essentials/db_access.php';
require '../essentials/access_check.php';
require '../essentials/admin_access.php';

$message = "";

$id = $_GET['id'];

// Fetch institute
$stmt = $pdo->prepare("SELECT name, cover_url FROM institute WHERE id = ?");
$stmt->execute([$id]);
$institute = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if institute exists
if (!$institute) {
    header('Location: ../home/');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $cover = trim($_POST['cover_url']);
    
    // Prepare and execute the update statement
    $stmt = $pdo->prepare("UPDATE institute SET name = :name, cover_url = :cover WHERE id = :id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':cover', $cover);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        header("Location: /institute/$id");
        exit();
    } else {
        $message = "Error: Could not update Institute.";
    }
}

$title = 'Edit Institute';
$banner = false;
$form = true;
$editor = false;
$edit_options = '';
$right_sidebar_options='';
$left_sidebar_options='';

function main_article() {
    global $institute; // Access the global variable within the function
    echo '
        <form class="main" method="post" action="">
            <div class="default">
                <h3>Edit Institute</h3>
            </div>
            <div class="default">
                <input type="text" id="name" maxlength="255" name="name" pattern="[A-Za-z0-9\- ]+" value="' . htmlspecialchars($institute['name'], ENT_QUOTES) . '" required>
                <label for="name">Institute Name:</label>
            </div>
            <div class="default">
                <input type="text" id="cover" maxlength="255" name="cover_url" value="' . htmlspecialchars($institute['cover_url'], ENT_QUOTES) . '" required>
                <label for="cover_url">Cover URL:</label>
            </div>
            <div class="default">
                <button type="submit">Save</button>
            </div>
        </form>
    ';
}

require '../essentials/default.php';
?>
