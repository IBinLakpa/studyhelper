<?php
    // category/select.php
    //or category/XX

    require '../essentials/db_access.php';
    require '../essentials/access_check.php';

    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    // Fetch category
    $stmt = $pdo->prepare("SELECT name FROM category WHERE id = ?");
    $stmt->execute([$id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if category exists
    if (!$category) {
        header('Location: ../home/');
        exit();
    }

    // Fetch courses
    $stmt = $pdo->prepare("SELECT * FROM subject WHERE categoryId = ?");
    $stmt->execute([$id]);
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($category['name']); ?></title>
    <?php
        @include '../essentials/global_style.php';
        @include '../essentials/global_script.php';
    ?>
</head>
<body>
    <?php
        require '../essentials/header.php';
    ?>
    <div>
        <a href="all">All Categories</a>
        <hr>
        <h1><?php echo htmlspecialchars($category['name']); ?></h1>
        <?php if ($admin_access): ?>
            <a href="<?php echo htmlspecialchars($id); ?>/edit">Edit Category?</a>
            <a href="<?php echo htmlspecialchars($id); ?>/delete">Delete Category?</a>
        <?php endif; ?>
        <?php if ($subjects): ?>
            <?php foreach ($subjects as $subject): ?>
                <div class="banner">
                    <a href="<?php echo htmlspecialchars($subject['id']); ?>" rel="noopener noreferrer">
                        <?php echo htmlspecialchars($subject['name']); ?>
                    </a>
                    Level: <?php echo htmlspecialchars($subject['level']); ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No courses exist for this institute.</p>
        <?php endif;
            if ($edit_access): ?>
            <a href="../subject/<?php echo htmlspecialchars($id); ?>/add">Add Subject?</a>
        <?php endif; ?>
    </div>
    <?php
        include '../essentials/footer.php';
    ?>
</body>
</html>
