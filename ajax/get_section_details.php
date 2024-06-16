<?php
require '../essentials/db.php';

header('Content-Type: application/json');

if (isset($_GET['section_id'])) {
    $section_id = $_GET['section_id'];

    $stmt = $pdo->prepare("SELECT s.*, c.id as category_id, sub.id as subject_id 
                           FROM Section s
                           JOIN Chapter ch ON s.chapter_id = ch.id
                           JOIN Subject sub ON ch.subject_id = sub.id
                           JOIN Category c ON sub.category_id = c.id
                           WHERE s.id = ?");
    $stmt->execute([$section_id]);

    $section = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($section);
} else {
    echo json_encode([]);
}
?>
