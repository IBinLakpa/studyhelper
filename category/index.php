<?php
// category/index.php
require '../essentials/db.php';
require '../essentials/editor_access.php';

// Initialize variables
$categories = [];
$subjects = [];
$category_name = "";

// Check if category ID is provided
if (isset($_GET['id'])) {
    $category_id = $_GET['id'];

    // Fetch category name
    $stmt = $pdo->prepare("SELECT name FROM category WHERE id = ?");
    $stmt->execute([$category_id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($category) {
        $category_name = $category['name'];
        
        // Fetch subjects under the category
        $stmt = $pdo->prepare("SELECT id, name FROM subject WHERE category_id = ? ORDER BY name");
        $stmt->execute([$category_id]);
        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Redirect to 404 page if category not found
        header("Location: ../essentials/404.php");
        exit();
    }
} else {
    // Fetch all categories
    $stmt = $pdo->query("SELECT id, name FROM category ORDER BY name");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $category_name ? htmlspecialchars($category_name) : 'Categories'; ?></title>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/skeleton.css">
    <style>
        .content {
            display: block;
            background-color: white;
            width: auto;
            padding: 2rem;
            margin: 3rem;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php require '../essentials/header.php'; ?>
    <div class="content">
        <?php if ($category_name): ?>
            <h1>Subjects in <?php echo htmlspecialchars($category_name); ?></h1>
            <ul>
                <?php foreach ($subjects as $subject): ?>
                    <li>
                        <a href="../subject/index.php?id=<?php echo $subject['id']; ?>">
                            <?php echo htmlspecialchars($subject['name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <h1>Categories</h1>
            <ul>
                <?php foreach ($categories as $category): ?>
                    <li>
                        <a href="index.php?id=<?php echo $category['id']; ?>">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <?php require '../essentials/footer.php'; ?>
</body>
</html>
