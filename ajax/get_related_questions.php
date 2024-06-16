<?php
// get_related_questions.php
require '../essentials/db.php';

// Check if section_id is provided in the query string
if(isset($_GET['section_id'])) {
    $section_id = $_GET['section_id'];

    // Prepare and execute the SQL query to fetch related questions based on section_id
    $stmt = $pdo->prepare("SELECT q.id FROM questions q INNER JOIN question_relation qr ON q.id = qr.question_id WHERE qr.section_id = ?");
    $stmt->execute([$section_id]);

    // Fetch the results as an associative array
    $related_questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the results as JSON
    header('Content-Type: application/json');
    echo json_encode($related_questions);
} else {
    // Return an error message if section_id is not provided
    echo "Error: section_id is not provided in the query string.";
}
?>