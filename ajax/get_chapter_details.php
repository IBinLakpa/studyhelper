<?php
require '../essentials/db.php';

$chapter_id = isset($_GET['chapter_id']) ? (int)$_GET['chapter_id'] : 0;

if ($chapter_id > 0) {
    $stmt = $pdo->prepare("SELECT name, creditHour, order_no, intro FROM Chapter WHERE id = ?");
    $stmt->execute([$chapter_id]);
    $chapter = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($chapter);
} else {
    echo json_encode([]);
}
