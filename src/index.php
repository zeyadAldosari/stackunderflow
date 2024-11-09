<?php
session_start();
include 'db.php';

$items_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
$offset = ($current_page - 1) * $items_per_page;

$base_query = "SELECT q.question_id, q.title, q.content, q.user_id, u.username, q.date_time, 
               COALESCE(AVG(r.rating), 'no rate') as avg_rating,
               (SELECT COUNT(*) FROM Answer a WHERE a.question_id = q.question_id) as answer_count
               FROM Question q
               LEFT JOIN User u ON q.user_id = u.user_id
               LEFT JOIN Rating r ON q.question_id = r.answer_id
               WHERE q.title LIKE :search OR q.content LIKE :search
               GROUP BY q.question_id";

$recent_query = $base_query . " ORDER BY q.date_time DESC LIMIT :limit OFFSET :offset";
$highest_query = $base_query . " ORDER BY (SELECT COUNT(*) FROM Answer a WHERE a.question_id = q.question_id) DESC, q.date_time DESC LIMIT :limit OFFSET :offset";
$recent_stmt = $pdo->prepare($recent_query);
$recent_stmt->bindParam(':search', $search);
$recent_stmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
$recent_stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$recent_stmt->execute();
$recent_questions = $recent_stmt->fetchAll(PDO::FETCH_ASSOC);
$highest_stmt = $pdo->prepare($highest_query);
$highest_stmt->bindParam(':search', $search);
$highest_stmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
$highest_stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$highest_stmt->execute();
$highest_questions = $highest_stmt->fetchAll(PDO::FETCH_ASSOC);

$count_query = "SELECT COUNT(*) FROM Question q WHERE q.title LIKE :search OR q.content LIKE :search";
$count_stmt = $pdo->prepare($count_query);
$count_stmt->bindParam(':search', $search);
$count_stmt->execute();
$total_questions = $count_stmt->fetchColumn();
$total_pages = max(ceil($total_questions / $items_per_page), 1);
function textLimit($text, $max_chars = 150)
{
    if (strlen($text) > $max_chars) {
        $text = substr($text, 0, $max_chars) . '...';
    }
    return $text;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Questions</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="./output.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: "Inter", sans-serif;
        }

        .main-color {
            color: #313131;
        }

        .btn-primary {
            background-color: #4a90e2;
            color: white;
        }

        .btn-primary:hover {
            background-color: #357ABD;
        }
    </style>
</head>

<body class="bg-gray-200">
    <?php
    include './components/nav.php'
    ?>
    <?php
    include './components/sideBar.php'
    ?>

    <main class="p-14 ml-64">
        <div class="flex justify-between items-center mb-4">
        </div>
        <div class="flex items-start justify-between my-20">
            <h1 class="text-gray-800 font-bold text-5xl">All Questions</h1>
        </div>
        <form class="flex space-x-4" method="GET">
            <input type="text" name="search" placeholder="Search questions..." class="px-4 py-2 border rounded-2xl" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button type="submit" class="border border-gray-800 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-800 hover:text-white">Search</button>
            <a href="index.php" class="border border-gray-800 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-800 hover:text-white">Reset</a>
        </form>


        <div class="pt-10 flex flex-col gap-y-2">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Recent Questions</h2>
            <?php foreach ($recent_questions as $row) : ?>
                <div class="w-full p-6 bg-white border border-gray-200 rounded-3xl shadow">
                    <div class="flex justify-between mb-5">
                        <div>
                            <a href="readMore.php?question_id=<?= $row['question_id'] ?>">
                                <h5 class="text-2xl font-semibold tracking-tight text-gray-800"><?= htmlspecialchars($row['title']) ?></h5>
                            </a>
                            <p class="mb-3 text-base text-gray-500"><?= nl2br(htmlspecialchars(textLimit($row['content']))) ?></p>
                            <p class="text-sm text-gray-500">Asked by: <?= htmlspecialchars($row['username']) ?></p>
                            <p class="text-sm text-gray-500">Asked on: <?= date('M d, Y, h:i A', strtotime($row['date_time'])) ?></p>
                            <p class="text-xl text-gray-800 mt-2">Answers: <?= $row['answer_count'] ?></p>
                        </div>
                        <div class="flex items-center gap-2">
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']) : ?>
                                <a href="editQuestion.php?question_id=<?= $row['question_id'] ?>" class="flex items-center justify-center">
                                    <svg class="w-8 h-16 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                    </svg>
                                </a>
                            <?php endif; ?>
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']) : ?>
                                <a href="deleteQuestion.php?question_id=<?= $row['question_id'] ?>">
                                    <button>
                                        <svg class="w-8 h-16 text-red-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                        </svg>
                                    </button>
                                </a>
                            <?php endif; ?>
                            <a href="readMore.php?question_id=<?= $row['question_id'] ?>" class="py-2 px-4 rounded underline whitespace-nowrap ">Read more -></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="pt-10 flex flex-col gap-y-2">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Questions with Most Answers</h2>
            <?php foreach ($highest_questions as $row) : ?>
                <div class="w-full p-6 bg-white border border-gray-200 rounded-3xl shadow">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <a href="readMore.php?question_id=<?= $row['question_id'] ?>">
                                <h5 class="text-2xl font-semibold tracking-tight text-gray-800"><?= htmlspecialchars($row['title']) ?></h5>
                            </a>
                            <p class="mb-3 text-base text-gray-500"><?= nl2br(htmlspecialchars(textLimit($row['content']))) ?></p>
                            <p class="text-sm text-gray-500">Asked by: <?= htmlspecialchars($row['username']) ?></p>
                            <p class="text-sm text-gray-500">Asked on: <?= date('M d, Y, h:i A', strtotime($row['date_time'])) ?></p>
                            <p class="text-xl text-gray-800 mt-2">Answers: <?= $row['answer_count'] ?></p>
                        </div>
                        <div class="flex gap-2 items-center">
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']) : ?>
                                <a href="editQuestion.php?question_id=<?= $row['question_id'] ?>" class="flex items-center justify-center">
                                    <svg class="w-8 h-16 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                    </svg>
                                </a>
                            <?php endif; ?>
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']) : ?>
                                <form action="deleteQuestion.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this question?');" class="flex items-center justify-center">
                                    <input type="hidden" name="question_id" value="<?= $row['question_id'] ?>">
                                    <button type="submit" class="flex items-center justify-center">
                                        <svg class="w-8 h-16 text-red-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                        </svg>
                                    </button>
                                </form>
                            <?php endif; ?>
                            <a href="readMore.php?question_id=<?= $row['question_id'] ?>" class="py-2 px-4 rounded underline  whitespace-nowrap">Read more -></a>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
        <div class="flex justify-between items-center mt-4 w-full">
            <?php if ($current_page > 1) : ?>
                <a href="?page=<?= $current_page - 1 ?>" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Previous
                </a>
            <?php else : ?>
                <div></div>
            <?php endif; ?>

            <?php if ($current_page < $total_pages) : ?>
                <a href="?page=<?= $current_page + 1 ?>" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Next
                </a>
            <?php else : ?>
                <div></div>
            <?php endif; ?>
        </div>

        <div class="text-center">
            Page <?= $current_page ?> of <?= $total_pages ?>
        </div>
    </main>
</body>

</html>