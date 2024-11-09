<?php
session_start();
include 'db.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$items_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
$offset = ($current_page - 1) * $items_per_page;

$query = "SELECT a.answer_id, a.content as answer_content, a.answer_date, q.question_id, q.title as question_title
          FROM Answer a
          JOIN Question q ON a.question_id = q.question_id
          WHERE a.user_id = :user_id AND (a.content LIKE :search OR q.title LIKE :search)
          ORDER BY a.answer_id DESC
          LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->bindParam(':search', $search);
$stmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$answers = $stmt->fetchAll(PDO::FETCH_ASSOC);


$count_query = "SELECT COUNT(*) 
                FROM Answer a
                JOIN Question q ON a.question_id = q.question_id
                WHERE a.user_id = :user_id AND (a.content LIKE :search OR q.title LIKE :search)";
$count_stmt = $pdo->prepare($count_query);
$count_stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$count_stmt->bindParam(':search', $search);
$count_stmt->execute();
$total_answers = $count_stmt->fetchColumn();
$total_pages = max(ceil($total_answers / $items_per_page),1);
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
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="./output.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <style>
        * {
            font-family: "Inter", sans-serif;
        }

        .main-color {
            color: #313131;
        }
    </style>
</head>

<body class="bg-gray-200">
    <?php include './components/nav.php'; ?>

    <?php include './components/sideBar.php'; ?>

    <main class="p-14 ml-64">

        <div class="flex items-start justify-between my-20">
            <h1 class="text-gray-800 font-bold text-5xl">My Answers</h1>
            <form class="flex space-x-4" method="GET">
                <div class="relative flex items-center w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="search" name="search" id="search" class="block w-full pl-10 pr-3 py-2 text-sm text-gray-800 border border-gray-400 rounded-2xl bg-gray-100 focus:ring-gray-500 focus:border-gray-500" placeholder="Search my answers" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                </div>
                <button type="submit" class="flex-shrink-0 border border-gray-800 text-gray-800 text-sm font-bold py-2 px-4 rounded hover:bg-gray-800 hover:text-white">Search</button>
                <a href="myAnswers.php" class="flex-shrink-0 border border-gray-800 text-gray-800 text-sm font-bold py-2 px-4 rounded hover:bg-gray-800 hover:text-white">Reset</a>
            </form>
        </div>


        <div class="pt-10 flex flex-col gap-y-2">
            <?php foreach ($answers as $answer) : ?>
                <div class="w-full p-6 bg-white border border-gray-200 rounded-3xl shadow">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <a href="readMore.php?question_id=<?= $answer['question_id'] ?>">
                                <h5 class="text-2xl font-semibold tracking-tight text-gray-800 underline"><?= htmlspecialchars($answer['question_title']) ?></h5>
                            </a>
                            <h5 class="text-base font-semibold tracking-tight text-gray-500"><?= nl2br(htmlspecialchars(textLimit($answer['answer_content']))) ?></h5>
                            <p class="text-sm text-gray-500 mt-4">Answered on: <?= isset($answer['answer_date']) ? date('M d, Y, h:i A', strtotime($answer['answer_date'])) : 'Date not available' ?></p>

                        </div>
                        <div class="flex gap-3">
                            <a href="editAnswer.php?answer_id=<?= $answer['answer_id'] ?>">              
                                <button class="text-gray-800 hover:text-gray-600">
                                    <svg class="w-8 h-16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                    </svg>
                                </button>
                            </a>
                            <a href="deleteAnswer.php?answer_id=<?= $answer['answer_id'] ?>">
                           
                                <button class="text-red-500 hover:text-red-700">
                                    <svg class="w-8 h-16 text-red-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                    </svg>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="flex justify-between items-center mt-4 w-full">
            <?php if ($current_page > 1) : ?>
                <a href="?page=<?= $current_page - 1 ?>" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Previous</a>
            <?php endif; ?>
            <?php if ($current_page < $total_pages) : ?>
                <a href="?page=<?= $current_page + 1 ?>" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Next</a>
            <?php endif; ?>
        </div>
        <div class="text-center">
            Page <?= $current_page ?> of <?= $total_pages ?>
        </div>

    </main>
</body>

</html>