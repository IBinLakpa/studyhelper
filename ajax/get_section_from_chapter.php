<?php
require '../essentials/db.php';

header('Content-Type: application/json');

if (isset($_GET['chapter_id'])) {
    $chapter_id = $_GET['chapter_id'];

    $stmt = $pdo->prepare("SELECT id, name FROM Section WHERE chapter_id = ? ORDER BY order_no");
    $stmt->execute([$chapter_id]);

    $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($sections);
} else {
    echo json_encode([]);
}
?>
