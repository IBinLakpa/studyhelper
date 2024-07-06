<?php
// login.php
require '../essentials/db_access.php';
require '../essentials/access_check.php';

if ($user_id) {
    header('Location: /home');
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Fetch user by email
    $stmt = $pdo->prepare("SELECT * FROM user_access_view WHERE user_email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['user_pwd_hash'])) {
        // Set session or cookie to mark the user as logged in
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['access']=$user['editor_access']?($user['admin_access']?3:2):1;
        header('Location: /home');
        exit();
    } else {
        $message = "Invalid email or password.";
    }
}

$title = 'Login';
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
            <h3>Login</h3>
        </div>
        ' . htmlspecialchars($message, ENT_QUOTES) . '
        <div class="default">
            <input type="email" id="email" maxlength="255" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
            <label for="email">Email:</label>
        </div>
        <div class="default">
            <input type="password" id="password" maxlength="255" name="password" required>
            <label for="password">Password:</label>
        </div>
        <div class="default">
            <button type="submit">Login</button>
        </div>
        <div>
            Dont have an account? <a href="user/signup">Sign Up</a>
        </div>
    </form>';
}

require '../essentials/default.php';
?>
