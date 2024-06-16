<?php
// section/index.php
require '../essentials/db.php';

if (!isset($_GET['id'])) {
    header("Location: ../essentials/404.php");
    exit();
}

$chapter_id = $_GET['id'];

// Fetch the current chapter details
$stmt = $pdo->prepare("SELECT * FROM chapter WHERE id = ?");
$stmt->execute([$chapter_id]);
$chapter = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$chapter) {
    header("Location: ../essentials/404.php");
    exit();
}

// Fetch the sections for this chapter, ordered by order_no
$stmt = $pdo->prepare("SELECT * FROM section WHERE chapter_id = ? ORDER BY order_no");
$stmt->execute([$chapter_id]);
$sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all chapters for the sidebar
$stmt = $pdo->prepare("SELECT id, name, order_no FROM chapter WHERE subject_id = ? ORDER BY order_no");
$stmt->execute([$chapter['subject_id']]);
$chapters = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch subject details for breadcrumb
$stmt = $pdo->prepare("SELECT id, name, category_id FROM subject WHERE id = ?");
$stmt->execute([$chapter['subject_id']]);
$subject = $stmt->fetch(PDO::FETCH_ASSOC);

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
    <title>Chapter <?php echo htmlspecialchars($chapter['order_no']); ?>: <?php echo htmlspecialchars($chapter['name']); ?></title>
    <link rel="stylesheet" href="../css/normalize.css?2">
    <link rel="stylesheet" href="../css/skeleton.css?33">
    <script src="https://cdn.jsdelivr.net/gh/jbowyersmith/bbcode.js/bbcode.js"></script>
    <script src="../essentials/view.js"></script>
    <script id="MathJax-script" async src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

</head>
<body>
    <?php require '../essentials/header.php'; ?>
    <div class="content">
        <a href="../category/index.php?id=<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></a> >>
        <a href="../subject/index.php?id=<?php echo $subject['id']; ?>"><?php echo htmlspecialchars($subject['name']); ?></a> >>
        <?php echo htmlspecialchars($chapter['name']); ?>
        <hr>
        <h1>Chapter <?php echo htmlspecialchars($chapter['order_no']); ?>: <?php echo htmlspecialchars($chapter['name']); ?></h1>
        <hr>
        <span style="text-align:right;display: block;">Credit Hour: <?php echo htmlspecialchars($chapter['creditHour']); ?></span>
        <div>
            <h3>Introduction:</h3>
            <p class="bbcode">
                <?php echo htmlspecialchars($chapter['intro']); ?>
            </p>
        </div>
        <div class="toc">
            <h3>Table of Contents</h3>
            <ul>
                <?php foreach ($sections as $section): ?>
                    <li>
                        <a href="#section_<?php echo $section['id']; ?>">
                            <?php echo htmlspecialchars($chapter['order_no']) . '.' . htmlspecialchars($section['order_no']); ?> <?php echo htmlspecialchars($section['name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php if (count($sections) > 0): ?>
            <?php foreach ($sections as $section): ?>
                <div class="section" id="section_<?php echo $section['id']; ?>">
                    <hr>
                    <h3>
                        <img src="../images/arrow.png" class="toggle" onclick="toggleDisplay('<?php echo 'Section_body'.htmlspecialchars($section['id']); ?>',this)">
                        <?php echo htmlspecialchars($chapter['order_no']) . '.' . htmlspecialchars($section['order_no']); ?> <?php echo htmlspecialchars($section['name']); ?>
                    </h3>
                    
                    <div id="<?php echo 'Section_body'.htmlspecialchars($section['id']); ?>" class="spoiler">
                        <p class="bbcode">
                            <?php echo htmlspecialchars($section['body']); ?>
                        </p>
                        <a style="text-align:right;display: block;"href="../relatedquestion/?id=<?php echo $section['id']; ?>">Related Questions</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No sections available for this chapter.</p>
        <?php endif; ?>
    </div>
    <?php require '../essentials/footer.php'; ?>
</body>
</html>
