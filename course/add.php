<?php
// course/add.php?id=XX
// or course/XX/add

require '../essentials/db_access.php';
require '../essentials/access_check.php';
require '../essentials/admin_access.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch institute
$stmt = $pdo->prepare("SELECT name, id FROM institute WHERE id = ?");
$stmt->execute([$id]);
$institute = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if institute exists
if (!$institute) {
    header('Location: ../home/errorhere');
    exit();
}
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $level = trim($_POST['level']);
    
    // Prepare and execute the insert statement
    $stmt = $pdo->prepare("INSERT INTO course (name, level, instituteId) VALUES (:name, :level, :instituteId)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':level', $level);
    $stmt->bindParam(':instituteId', $id);
    
    if ($stmt->execute()) {
        $lastId = $pdo->lastInsertId();
        header('Location: /course/' . $lastId);
        exit();
    } else {
        $message = "Error: Could not add Course.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Course</title>
    <link rel="stylesheet" href="../styles/global.css"/>    
    <script src="../scripts/global.js" defer></script>
</head>
<body>
    <?php
        require '../essentials/header.php';
    ?>
    <form method="post" action="">
        <h3>Add Course to <?php echo htmlspecialchars($institute['name']); ?></h3>
        <label for="name">Course Name:</label>
        <input type="text" id="name" maxlength="255" name="name" pattern="[A-Za-z0-9\- ]+" required>
        <label for="level">Education Level:</label>
        <select name="level" required>
            <option value="" selected disabled hidden>Select Level</option>
            <option value="A-Level">A-Level or Equivalent</option>
            <option value="Bachelor">Bachelor or Equivalent</option>
            <option value="Master">Master or Equivalent</option>
        </select>
        <button type="submit">Save Course</button>
        <?php 
            if (!empty($message)) {
                echo '<p>' . htmlspecialchars($message) . '</p>'; 
            }
        ?>
    </form>
    <?php
        include '../essentials/footer.php';
    ?>
</body>
</html>
