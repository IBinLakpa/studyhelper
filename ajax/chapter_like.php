<?php
// essentials/chapter_like.php
require '../essentials/db_access.php';

header('Content-Type: application/json');

$chapter_id = $_POST['chapter_id'];

if ($chapter_id > 0) {
    $stmt = $pdo->prepare("SELECT id, name FROM subject WHERE chapter_id = ?");
    $stmt->execute([$chapter_id]);
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($subjects);
} else {
    echo json_encode([]);
}
