<?php
// relatedquestion/index.php
require '../essentials/db.php';
require '../essentials/editor_access.php';

if (!isset($_GET['id'])) {
    header("Location: ../essentials/404.php");
    exit();
}

$section_id = $_GET['id'];

// Fetch related questions
$stmt = $pdo->prepare("
    SELECT q.* 
    FROM question_relation qr
    JOIN questions q ON qr.question_id = q.id
    WHERE qr.section_id = ?
");
$stmt->execute([$section_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($questions)) {
    header("Location: ../essentials/404.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Related Questions</title>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/skeleton.css">
    <script src="../essentials/view.js"></script>
</head>
<body>
    <?php require '../essentials/header.php'; ?>
    <div class="content">
        <h1>Related Questions</h1>
            <?php foreach ($questions as $question): ?>
                <h3>
                    <a href="../question/index.php?id=<?php echo $question['id']; ?>" class="bb">
                        >>
                    </a>
                    <span class="bb">
                        <?php echo htmlspecialchars($question['question']); ?>
                    </span>
                    <?php if (1 == 1):?>
                        <span style="align-self:right; width:auto">
                            <a href="../question/edit.php?id=<?php echo $question['id']; ?>">
                                <img src="../images/edit.svg" class="toggle" alt="Edit">
                            </a>
                            <a href="../question/delete.php?id=<?php echo $question['id']; ?>" onclick="return confirm('Are you sure you want to delete this question?');">
                                <img src="../images/delete.svg" class="toggle" alt="Delete">
                            </a>
                        </span>
                    <?php endif; ?>
                </h3>
                    <div>
                        <img src="../images/arrow.svg" class="toggle" onclick="toggleDisplay('answer_<?php echo $question['id']?>', this)">
                        Answer:
                    </div>
                    <div id="answer_<?php echo $question['id']?>" class="spoiler">
                        <p class="bbcode">
                            <?php echo htmlspecialchars($question['answer']); ?>
                        </p>
                    </div>
                    <hr>
            <?php endforeach; ?>
    </div>
    <?php require '../essentials/footer.php'; ?>
</body>
</html>
