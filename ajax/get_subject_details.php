<?php
// get_subject_details.php
require '../essentials/db.php';

header('Content-Type: application/json');

if (isset($_GET['subject_id'])) {
    $subject_id = $_GET['subject_id'];

    // Fetch subject details based on the selected subject ID
    $stmt = $pdo->prepare("SELECT Subject.*, Category.id AS category_id, Category.name AS category_name, Prerequisite.id AS prerequisite_id, Prerequisite.category_id AS prerequisite_category_id FROM Subject 
                            LEFT JOIN Category ON Category.id = Subject.category_id 
                            LEFT JOIN Subject AS Prerequisite ON Prerequisite.id = Subject.prerequisite_id 
                            WHERE Subject.id = ?");
    $stmt->execute([$subject_id]);
    $subject = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return subject details as JSON
    echo json_encode($subject);
} else {
    echo json_encode([]);
}
?>
