<?php
// category/edit/XX
require '../essentials/db_access.php';
require '../essentials/access_check.php';
require '../essentials/admin_access.php';

$message = "";

$id = $_GET['id'];

// Fetch category
$stmt = $pdo->prepare("SELECT name, cover_url FROM category WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if category exists
if (!$category) {
    header('Location: ../home/');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $cover = trim($_POST['cover_url']);
    
    // Prepare and execute the update statement
    $stmt = $pdo->prepare("UPDATE category SET name = :name, cover_url = :cover WHERE id = :id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':cover', $cover);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        header("Location: /category/$id");
        exit();
    } else {
        $message = "Error: Could not update Category.";
    }
}

$title = 'Edit Category';
$banner = false;
$form = true;
$editor = false;
$edit_options = '';
$right_sidebar_options='';
$left_sidebar_options='';

function main_article() {
    global $category; // Access the global variable within the function
    echo '
        <form class="main" method="post" action="">
            <div class="default">
                <h3>Edit Category</h3>
            </div>
            <div class="default">
                <input type="text" id="name" maxlength="255" name="name" pattern="[A-Za-z0-9\- ]+" value="' . htmlspecialchars($category['name'], ENT_QUOTES) . '" required>
                <label for="name">Category Name:</label>
            </div>
            <div class="default">
                <input type="text" id="cover" maxlength="255" name="cover_url" value="' . htmlspecialchars($category['cover_url'], ENT_QUOTES) . '" required>
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
