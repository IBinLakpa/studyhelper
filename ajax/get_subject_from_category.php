<?php
// get_subjects_from_categories.php
require '../essentials/db.php';

header('Content-Type: application/json');

if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    // Fetch subjects based on the selected category
    $stmt = $pdo->prepare("SELECT id, name FROM Subject WHERE category_id = ?");
    $stmt->execute([$category_id]);
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return subjects as JSON
    echo json_encode($subjects);
} else {
    echo json_encode([]);
}
?>
