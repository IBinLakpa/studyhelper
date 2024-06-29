<?php
    // category/index.php
    //or category/all
    //or category/
    require '../essentials/db_access.php';
    // Fetch all categorys
    $stmt = $pdo->query("SELECT * FROM category ORDER BY name");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Categories</title>
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
        <?php foreach ($categories as $category): ?>
            <div class="banner">
                <img src="<?php echo $category['cover_url']; ?> " loading="lazy">
                <a href="<?php echo $category['id']; ?>" rel="noopener noreferrer">
                    <?php echo $category['name']; ?>
                </a>
            </div>
        <?php endforeach; ?>

    </div>
    <?php
        include '../essentials/footer.php';
    ?>
</body>
</html>