<?php
// question/XX or question/?id=XX
require '../essentials/db_access.php';
require '../essentials/access_check.php';

$id = (int)$_GET['id']; // Ensure ID is an integer for security

// Fetch question details
$stmt = $pdo->prepare("SELECT q, a, total_likes, created, updated FROM question WHERE id = ?");
$stmt->execute([$id]);
$question = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if question exists
if (!$question) {
    header('Location: ../home/');
    exit();
}
$left_sidebar_options='
    <aside class="sidebar-left main">
        something
    </aside>
';

// Right sidebar options (admin access)
$right_sidebar_options = '';
if ($admin_access) {
    $right_sidebar_options = '
        <aside class="sidebar-right main">
            <a href="question/edit/' . $id . '" title="Edit Question">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <a href="question/delete/' . $id . '" title="Delete Question">
                <i class="fa-solid fa-trash"></i>
            </a>
        </aside>';
}

$title = 'Question Details';
$form = false;
$editor = false;
$banner = true;

// Main article function
function main_article() {
    global $question, $id;
    $update='';
    if($question['updated']){
        $update='<span class="side_content">Updated: ' . htmlspecialchars($question['updated'], ENT_QUOTES) . '</span>';
    }

    $main = '
        <article class="main">
            <a href="question/all">All Questions</a>
            <hr>
            <div>
                <h2>Question</h2>
                <p class="content">' . nl2br(htmlspecialchars($question['q'], ENT_QUOTES)) . '</p>
                <h2>Answer</h2>
                <p class="content">' . nl2br(htmlspecialchars($question['a'], ENT_QUOTES)) . '</p>
                <span class="side_content">
                    <span class="side_content">
                        # : ' . htmlspecialchars($id, ENT_QUOTES) . '
                    </span>
                    <span class="side_content">
                        Created: ' . htmlspecialchars($question['created'], ENT_QUOTES) . '
                    </span>
                    '.$update.'
                </span>
            </div>
        </article>';

    return $main;
}

require '../essentials/default.php';
?>
