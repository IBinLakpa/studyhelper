<?php
// questions/add.php
require '../essentials/db.php';
require '../essentials/editor_access.php';

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    // Insert new question into the database
    $stmt = $pdo->prepare("INSERT INTO questions (question, answer, created) VALUES (:question, :answer, CURDATE())");
    $stmt->execute(['question' => $question, 'answer' => $answer]);

    $message = "Question added successfully.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Question</title>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/skeleton.css">
</head>
<body>
    <?php require '../essentials/header.php'; ?>
    <div class="content">
        <h1>Add New Question</h1>
        <form method="post" action="add.php">
            <label for="question">Question:</label>
            <textarea id="question" name="question" required></textarea>
            <label for="answer">Answer:</label>
            <textarea id="answer" name="answer" required></textarea>
            <button type="submit">Add Question</button>
        </form>
        <?php if($message!=''):?>
            <p><?php echo $message; ?></p>
        <?php endif ?>
    </div>
    <?php require '../essentials/footer.php'; ?>
</body>
</html>
