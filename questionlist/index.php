<?php
// questions/index.php
require '../essentials/db.php';
require '../essentials/editor_access.php';

// Fetch all questions
$stmt = $pdo->query("SELECT id, question FROM questions ORDER BY id");
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questions</title>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/skeleton.css">
    <script src="../essentials/view.js"></script>
</head>
<body>
    <?php require '../essentials/header.php'; ?>
    <div class="content">
        <h1>Questions</h1>
        <a href="add.php" class="button">Add New Question</a>
        <table class="u-full-width">
            <tbody>
                <tr>
                    <th>ID</th>
                    <th>Question</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($questions as $question): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($question['id']); ?></td>
                        <td class="bb"><?php echo htmlspecialchars($question['question']); ?></td>
                        <td>
                            <a href="../question/?id=<?php echo $question['id']; ?>">
                                <img src="../images/arrow.svg" class="toggle" alt="Go">
                            </a>
                            <a href="../question/edit.php?id=<?php echo $question['id']; ?>">
                                <img src="../images/edit.svg" class="toggle" alt="Edit">
                            </a>
                            <a href="../question/delete.php?id=<?php echo $question['id']; ?>" onclick="return confirm('Are you sure you want to delete this question?');">
                                <img src="../images/delete.svg" class="toggle" alt="Delete">
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php require '../essentials/footer.php'; ?>
</body>
</html>
