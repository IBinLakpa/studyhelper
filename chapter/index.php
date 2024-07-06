<?php
// chapter/XX or chapter/?id=XX
require '../essentials/db_access.php';
require '../essentials/access_check.php';

$id = (int)$_GET['id'];

// Fetch chapter details
$stmt = $pdo->prepare("SELECT * FROM chapter_subject WHERE id = ?");
$stmt->execute([$id]);
$chapter = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if chapter exists
if (!$chapter) {
    header('Location: ../home/');
    exit();
}

// Fetch other chapters of the subject
$stmt = $pdo->prepare("SELECT id, name FROM chapter WHERE subject_id = ?");
$stmt->execute([$chapter['subject_id']]);
$chapters = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch sections for the chapter
$stmt = $pdo->prepare("SELECT * FROM section WHERE chapter_id = ? ORDER BY order_no");
$stmt->execute([$id]);
$sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize variables for navigation
$current_position = -1;
$prev_chapter = null;
$next_chapter = null;

// Left sidebar options
$left_sidebar_options = '
    <aside class="sidebar-left main">
        <h4>Table of Contents:</h4>
';

if ($chapters) {
    $left_sidebar_options .= '<ol>';
    foreach ($chapters as $index => $chap) {
        if ($chap['id'] == $id) {
            $current_position = $index;
        } 
        $left_sidebar_options .= '
            <li>
                <a href="chapter/' . $chap['id'] . '">' . htmlspecialchars($chap['name']) . '</a>
            </li>
        ';
    }
    $left_sidebar_options .= '</ol>';

    // Determine previous and next chapters
    if ($current_position > 0) {
        $prev_chapter = $chapters[$current_position - 1];
    }
    if ($current_position < count($chapters) - 1) {
        $next_chapter = $chapters[$current_position + 1];
    }
}

$left_sidebar_options .= '</aside>';

// Right sidebar options (admin access)
$right_sidebar_options = '';
if ($admin_access) {
    $right_sidebar_options = '
        <aside class="sidebar-right main">
            <a href="section/add/' . $id . '" title="Add Section to this Chapter">
                <i class="fa-solid fa-circle-plus"></i>
            </a>
            <a href="chapter/edit/' . $id . '" title="Edit Chapter">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <a href="chapter/delete/' . $id . '" title="Delete Chapter">
                <i class="fa-solid fa-trash"></i>
            </a>
        </aside>';
}

$title = htmlspecialchars($chapter['name']);
$form = false;
$editor = false;
$banner = true;

// Main article function
function main_article() {
    global $chapter, $sections, $id, $prev_chapter, $next_chapter, $current_position;

    $main = '
        <article class="main">
            <div class="navigation-links">';
    
    if ($prev_chapter) {
        $main .= '<a href="chapter/' . htmlspecialchars($prev_chapter['id']) . '" class="previous" title="' . htmlspecialchars($prev_chapter['name']) . '">&laquo;Previous</a>';
    }

    if ($next_chapter) {
        $main .= '<a href="chapter/' . htmlspecialchars($next_chapter['id']) . '" class="next" title="' . htmlspecialchars($next_chapter['name']) . '">Next&raquo;</a>';
    }

    $main .= '
            </div>
            <hr>
            <a href="category/' . htmlspecialchars($chapter['category_id']) . '">' . htmlspecialchars($chapter['category_name']) . '</a>
            >>
            <a href="subject/' . htmlspecialchars($chapter['subject_id']) . '">' . htmlspecialchars($chapter['subject_name']) . '</a>
            >>
            <a href="chapter/' . htmlspecialchars($id) . '">' . htmlspecialchars($chapter['name']) . '</a>
            <hr>
            <div class="banner">
                <img src="' . htmlspecialchars($chapter['cover_url']) . '" alt="' . htmlspecialchars($chapter['category_name']) . '">
                <div class="banner_text">
                    <h2>Chapter ' . ($current_position+1) .': '. htmlspecialchars($chapter['name']) . '</h2>
                    <span class="side_content">Credit Hours: ' . htmlspecialchars($chapter['credit_hour']) . '</span>
                    <span class="side_content"># ' . htmlspecialchars($id) . '</span>
                </div>
            </div>
            <hr>
            <h3>Introduction:</h3>
            <div class="content">' . htmlspecialchars($chapter['intro']) . '</div>
            <hr>';

    if ($sections) {
        $main .= '
            <br/>';

        foreach ($sections as $index =>  $section) {
            $main .= '
                <details class="section" id="' . htmlspecialchars($section['id']) . '">
                    <summary><h4>'.($current_position+1).'.'. ($index+1) .': '. htmlspecialchars($section['name']) . '</h4></summary>
                    <div class="content">
                        ' . htmlspecialchars($section['body']) . '
                        <span class="side_content">
                            <a href="section/edit/' . $id . '" title="Edit Section">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <span>
                                # ' . htmlspecialchars($section['id']) . '
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
