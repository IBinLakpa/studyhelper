<?php
    // course/select.php
    //or course/XX

    require '../essentials/db_access.php';
    require '../essentials/access_check.php';
    
    $id = (int)$_GET['id'];

    // Fetch course
    $stmt = $pdo->prepare("SELECT * FROM course WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if course exists
    if (!$course) {
        header('Location: ../home/');
        exit();
    }

    // Fetch institue
    $stmt = $pdo->prepare("SELECT name FROM institute WHERE id = ?");
    $stmt->execute([$course['instituteId']]);
    $institute = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($course['name']); ?></title>
    <link rel="stylesheet" href="../styles/global.css"/>    
    <script src="../scripts/global.js" defer></script>
</head>
<body>
    <?php
        require '../essentials/header.php';
    ?>
    <div>
        <a href="../institute/<?php echo htmlspecialchars($course['instituteId']); ?>">
            <?php echo htmlspecialchars($institute['name']); ?>
        </a>
        <hr>
        <h1><?php echo htmlspecialchars($course['name']); ?></h1>
        <?php if ($admin_access): ?>
            <a href="<?php echo htmlspecialchars($_GET['id']); ?>/edit">Edit Course?</a>
            <a href="<?php echo htmlspecialchars($_GET['id']); ?>/delete">Delete Course?</a>
        <?php endif; ?>
    </div>
    <?php
        include '../essentials/footer.php';
    ?>
</body>
</html>
