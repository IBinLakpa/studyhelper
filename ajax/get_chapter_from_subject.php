<?php
require '../essentials/db.php';

$subject_id = isset($_GET['subject_id']) ? (int)$_GET['subject_id'] : 0;

if ($subject_id > 0) {
    $stmt = $pdo->prepare("SELECT id, name FROM Chapter WHERE subject_id = ? ORDER BY order_no");
    $stmt->execute([$subject_id]);
    $chapters = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($chapters);
} else {
    echo json_encode([]);
}
?>
