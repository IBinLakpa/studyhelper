<?php
// relatedquestion/XX or relatedquestion/?id=XX
require '../essentials/db_access.php';
require '../essentials/access_check.php';

$id = (int)$_GET['id'];

// Fetch section details
$stmt = $pdo->prepare("SELECT name,body,chapter_id FROM section WHERE id = ?");
$stmt->execute([$id]);
$section = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if section exists
if (!$section) {
    header('Location: ../home/');
    exit();
}

//question initialization
$page=isset($_GET['page'])?(int)$_GET['id']:1;
$limit=10;
$offset = ($page - 1) * $limit;

// Fetch relatedquestion
$stmt = $pdo->prepare("SELECT question_id, q, a, created, updated FROM section_questions WHERE section_id = ? ");
$stmt->execute([$id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch other sections of the subject
$stmt = $pdo->prepare("SELECT id, name FROM section WHERE chapter_id = ? AND id != ?");
$stmt->execute([$section['chapter_id'], $id]);
$sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch chapter details
$stmt = $pdo->prepare("SELECT * FROM chapter_subject WHERE id = ?");
$stmt->execute([$section['chapter_id']]);
$chapter = $stmt->fetch(PDO::FETCH_ASSOC);

// Left sidebar options
$left_sidebar_options = '
    <aside class="sidebar-left main">
        <h4>Similar Sections:</h4>
';

if ($sections) {
    $left_sidebar_options .= '
        <ul>
    ';
    foreach ($sections as $index => $sect) {
        $left_sidebar_options .= '
            <li>
                <a href="chapter/' . $sect['id'] . '">' . htmlspecialchars($sect['name']) . '</a>
            </li>
        ';
    }
    $left_sidebar_options .= '
        </ul>
    ';
}

$left_sidebar_options .= '
    </aside>
';

// Right sidebar options (admin access)
$right_sidebar_options = '';
if ($admin_access) {
    $right_sidebar_options = '
        <aside class="sidebar-right main">
            <a href="relatedquestion/add/' . $id . '" title="Add More Related Questions">
                <i class="fa-solid fa-circle-plus"></i>
            </a>
        </aside>';
}

$title = htmlspecialchars($section['name']);
$form = false;
$editor = false;
$banner = true;

// Main article function
function main_article() {
    global $section, $questions, $id, $chapter;

    $main = '
        <article class="main">';
    

    $main .= '
            <a href="category/' . htmlspecialchars($chapter['category_id']) . '">' . htmlspecialchars($chapter['category_name']) . '</a>
            >>
            <a href="subject/' . htmlspecialchars($chapter['subject_id']) . '">' . htmlspecialchars($chapter['subject_name']) . '</a>
            >>
            <a href="chapter/' . htmlspecialchars($chapter['id']) . '">' . htmlspecialchars($chapter['name']) . '</a>
            >>
            <a href="chapter/' . htmlspecialchars($chapter['id']) .'#'. htmlspecialchars($id) . '">' . htmlspecialchars($section['name']) . '</a>
            <hr>
            <h3>' . htmlspecialchars($section['name']) . '</h3>
            <div class="content">' . htmlspecialchars($section['body']) . '</div>
            <hr>';

    if ($questions) {
        $main .= '
            <br/>';

        foreach ($questions as $index =>  $question) {
            $main .= '
                <details class="section">
                    <summary><h4>'. ($index+1) .': '. htmlspecialchars($question['q']) . '</h4></summary>
                    <div class="content">
                        ' . htmlspecialchars($question['a']) . '
                        <span class="side_content">
                            <a href="section/edit/' . $id . '" title="Edit Section">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <span>
                                # ' . htmlspecialchars($question['question_id']) . '
                            </span>
                        </span>
                    </div>
                    <span class="side_content">
                        <a href="relatedquestion/' . $id . '" title="Edit Section">Related Questions</a>
                    </span>
                </details>';
        }

    } else {
        $main .= '<p>No sections in this chapter</p>';
    }

    $main .= '</article>';

    return $main;
}

require '../essentials/default.php';
?>
