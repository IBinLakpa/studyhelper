<?php
    // institute/index.php
    //or institute/all
    //or institute/
    require '../essentials/db_access.php';
    // Fetch all institutes
    $stmt = $pdo->query("SELECT * FROM institute ORDER BY name");
    $institutes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Institutes</title>
    <base href="/">
    <link rel="stylesheet" href="styles/global.css"/>    
    <script src="scripts/global.js" defer></script>
</head>
<body>
    <?php
        require '../essentials/header.php';
    ?>
    <div>
        <?php foreach ($institutes as $institute): ?>
            <div class="banner">
                <img src="<?php echo $institute['cover_url']; ?> " loading="lazy">
                <a href="<?php echo $institute['id']; ?>" rel="noopener noreferrer">
                    <?php echo $institute['name']; ?>
                </a>
            </div>
        <?php endforeach; ?>

    </div>
    <?php
        include '../essentials/footer.php';
    ?>
</body>
</html>