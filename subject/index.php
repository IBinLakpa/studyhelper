<?php
// index.php
require '../essentials/db.php';
require '../essentials/editor_access.php';

if (!isset($_GET['id'])) {
    header("Location: ../essentials/404.php");
    exit();
}

$subject_id = $_GET['id'];

// Fetch the current subject details
$stmt = $pdo->prepare("SELECT * FROM subject WHERE id = ?");
$stmt->execute([$subject_id]);
$subject = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$subject) {
    header("Location: ../essentials/404.php");
    exit();
}

// Fetch the chapters for this subject, ordered by order_no
$stmt = $pdo->prepare("SELECT * FROM chapter WHERE subject_id = ? ORDER BY order_no");
$stmt->execute([$subject_id]);
$chapters = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all subjects for the sidebar
$stmt = $pdo->prepare("SELECT id, name, category_id FROM subject ORDER BY name");
$stmt->execute();
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch category details for breadcrumb
$stmt = $pdo->prepare("SELECT id, name FROM category WHERE id = ?");
$stmt->execute([$subject['category_id']]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject <?php echo htmlspecialchars($subject['name']); ?></title>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/skeleton.css">
    <script src="../essentials/view.js"></script>
</head>
<body>
    <?php require '../essentials/header.php'; ?>
    <div class="content">
        <a href="../category/index.php?id=<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></a> >>
        <?php echo htmlspecialchars($subject['name']); ?>
        <h1>Subject: <?php echo htmlspecialchars($subject['name']); ?></h1>
        <hr>
        <span style="text-align:right;display: block;">Credit: <?php echo htmlspecialchars($subject['credit']); ?></span>
        <h3>Table of Content:</h3>
        <div class="toc">
            <ul>
                <?php foreach ($chapters as $chapter): ?>
                    <li>
                        <a href="#chapter_<?php echo $chapter['id']; ?>">
                            <?php echo htmlspecialchars($chapter['order_no']); ?>. <?php echo htmlspecialchars($chapter['name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php if (count($chapters) > 0): ?>
            <?php foreach ($chapters as $chapter): ?>
                <div class="chapter" id="chapter_<?php echo $chapter['id']; ?>">
                    <h3>
                        <img src="../images/arrow.png" class="toggle" onclick="toggleDisplay('<?php echo 'chapter_intro_'.htmlspecialchars($chapter['id']); ?>',this)">
                        <a href="../c/<?php echo htmlspecialchars($chapter['id']); ?>">
                            <?php echo htmlspecialchars($chapter['order_no']); ?>. <?php echo htmlspecialchars($chapter['name']); ?>
                        </a>
                    </h3>
                    <div class="spoiler spoiler_content" id="<?php echo 'chapter_intro_'.htmlspecialchars($chapter['id']); ?>">
                        <div><?php echo htmlspecialchars($chapter['intro']); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No chapters available for this subject.</p>
        <?php endif; ?>
    </div>
    <?php require '../essentials/footer.php'; ?>
</body>
</html>
