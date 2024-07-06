<?php
// essentials/get_subjects.php
require '../essentials/db_access.php';

header('Content-Type: application/json');

$category_id = $_POST['category_id'];

if ($category_id > 0) {
    $stmt = $pdo->prepare("SELECT id, name FROM subject WHERE category_id = ?");
    $stmt->execute([$category_id]);
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($subjects);
} else {
    echo json_encode([]);
}
