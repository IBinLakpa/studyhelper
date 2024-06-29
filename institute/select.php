<?php
    // institute/select.php
    //or institute/XX

    require '../essentials/db_access.php';
    require '../essentials/access_check.php';

    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    // Fetch institute
    $stmt = $pdo->prepare("SELECT name FROM institute WHERE id = ?");
    $stmt->execute([$id]);
    $institute = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if institute exists
    if (!$institute) {
        header('Location: ../home/');
        exit();
    }

    // Fetch courses
    $stmt = $pdo->prepare("SELECT * FROM course WHERE instituteId = ?");
    $stmt->execute([$id]);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($institute['name']); ?></title>
    <link rel="stylesheet" href="../styles/global.css"/>    
    <script src="../scripts/global.js" defer></script>
</head>
<body>
    <?php
        require '../essentials/header.php';
    ?>
    <div>
        <a href="all">All Institutes</a>
        <hr>
        <h1><?php echo htmlspecialchars($institute['name']); ?></h1>
        <?php if ($admin_access): ?>
            <a href="<?php echo htmlspecialchars($id); ?>/edit">Edit Institute?</a>
            <a href="<?php echo htmlspecialchars($id); ?>/delete">Delete Institute?</a>
        <?php endif; ?>
        <?php if ($courses): ?>
            <?php foreach ($courses as $course): ?>
                <div class="banner">
                    <a href="../course/<?php echo htmlspecialchars($course['id']); ?>" rel="noopener noreferrer">
                        <?php echo htmlspecialchars($course['name']); ?>
                    </a>
                    Level: <?php echo htmlspecialchars($course['level']); ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No courses exist for this institute.</p>
        <?php endif;
            if ($edit_access): ?>
            <a href="../course/<?php echo htmlspecialchars($id); ?>/add">Add Courses?</a>
        <?php endif; ?>
    </div>
    <?php
        include '../essentials/footer.php';
    ?>
</body>
</html>
