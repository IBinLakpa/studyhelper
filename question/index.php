<?php
// questions/index.php
require '../essentials/db.php';
require '../essentials/editor_access.php';

if (!isset($_GET['id'])) {
    header("Location: ../essentials/404.php");
    exit();
}

$id = $_GET['id'];

// Fetch the specific question
$stmt = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
$stmt->execute([$id]);
$question = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$question) {
    header("Location: ../essentials/404.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question</title>
    <link rel="stylesheet" href="../css/normalize.css?45">
    <link rel="stylesheet" href="../css/skeleton.css">
    <script src="../essentials/view.js"></script>
</head>
<body>
    <?php require '../essentials/header.php'; ?>
    <div class="content">
        <h3>
            <?php echo htmlspecialchars($question['question']); ?>
            <?php if (1 == 1):?>
                <span style="align-self:right; width:auto">
                    <a href="edit.php?id=<?php echo $question['id']; ?>">
                        <img src="../images/edit.svg" class="toggle" alt="Edit">
                    </a>
                    <a href="delete.php?id=<?php echo $question['id']; ?>" onclick="return confirm('Are you sure you want to delete this question?');">
                        <img src="../images/delete.svg" class="toggle" alt="Delete">
                    </a>
                </span>
            <?php endif; ?>
        </h3>
        <span style="text-align:right;font-size:smaller;display:block;margin:none;">#<?php echo htmlspecialchars($question['id']); ?></span>
        
        <hr>
        <h3>
            <img src="../images/arrow.svg" class="toggle" onclick="toggleDisplay('answer', this)">
            Answer
        </h3>
        <div id="answer" class="spoiler">
            <p class="bbcode">
                <?php echo htmlspecialchars($question['answer']); ?>
            </p>
        </div>
        <hr>
    </div>
    <?php require '../essentials/footer.php'; ?>
</body>
</html>
