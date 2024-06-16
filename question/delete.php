<?php
// questions/delete.php
require '../essentials/db.php';
require '../essentials/editor_access.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the question from the database
    $stmt = $pdo->prepare("DELETE FROM questions WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: ../questionlist");
    exit();
} else {
    // Redirect to index if no id is provided
    header("Location: index.php");
    exit();
}
?>
