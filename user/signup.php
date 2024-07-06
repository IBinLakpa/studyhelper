<?php
// user/signup
require '../essentials/db_access.php';
require '../essentials/access_check.php';

if ($user_id) {
    header('Location: /home');
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $pwd_hash = password_hash($password, PASSWORD_BCRYPT);

    // Prepare and execute the insert statement
    $stmt = $pdo->prepare("INSERT INTO user (name, email, pwd_hash) VALUES (:name, :email, :pwd_hash)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':pwd_hash', $pwd_hash);

    if ($stmt->execute()) {
        $lastId = $pdo->lastInsertId();
        header('Location: /user/login');
        exit();
    } else {
        $message = "Error: Could not register.";
    }
}

$title = 'Sign Up';
$banner = false;
$form = true;
$editor = false;
$edit_options = '';
$right_sidebar_options = '';
$left_sidebar_options = '';

function main_article() {
    global $message;
    echo '
    <form class="main" method="post" action="">
        <div class="default">
            <h3>Sign Up</h3>
        </div>
        ' . htmlspecialchars($message, ENT_QUOTES) . '
        <div class="default">
            <input type="text" id="name" maxlength="255" name="name" pattern="[A-Za-z0-9\- ]+" required>
            <label for="name">Username:</label>
        </div>
        <div class="default">
            <input type="email" id="email" maxlength="255" name="email" required>
            <label for="email">Email:</label>
        </div>
        <div class="default">
            <input type="password" id="password" maxlength="255" name="password" required>
            <label for="password">Password:</label>
        </div>
        <div class="default">
            <button type="submit">Sign Up</button>
        </div>
        <div>
            Have an account? <a href="user/login">Login</a>
        </div>
    </form>';
}

require '../essentials/default.php';
?>
