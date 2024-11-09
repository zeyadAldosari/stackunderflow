<?php
session_start();
include 'db.php';


if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

$userId = $_SESSION['user_id'];
$items_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = $_GET['search'] ?? '';
$offset = ($current_page - 1) * $items_per_page;

$query = "SELECT question_id, title, content FROM Question WHERE user_id = :userId AND (title LIKE :search OR content LIKE :search) LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($query);
$searchTerm = '%' . $search . '%';
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
$stmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$count_query = "SELECT COUNT(*) FROM Question WHERE user_id = :userId AND (title LIKE :search OR content LIKE :search)";
$count_stmt = $pdo->prepare($count_query);
$count_stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$count_stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
$count_stmt->execute();
$total_questions = $count_stmt->fetchColumn();
$total_pages = max(ceil($total_questions / $items_per_page), 1);

if ($current_page > $total_pages || $total_questions == 0) {
  $current_page = max($total_pages, 1);
}

$page_display = $total_questions > 0 ? "Page $current_page of $total_pages" : "Page 0 of 0";
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My question</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="./output.css" rel="stylesheet" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet" />
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
  <?php
  include './components/nav.php'
  ?>

  <?php include './components/sideBar.php'; ?>

  <main class="p-14 ml-64">
    <div class="flex items-start justify-between my-20">
      <h1 class="text-gray-800 font-bold text-5xl">My questions</h1>
      <form class="flex space-x-4" method="GET">
        <div class="relative flex items-center w-full">
          <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
            </svg>
          </div>
          <input type="search" name="search" id="search" class="block w-full pl-10 pr-3 py-2 text-sm text-gray-800 border rounded-2xl bg-gray-100 focus:ring-blue-500 focus:border-blue-500" placeholder="Search questions" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </div>
        <button type="submit" class="flex-shrink-0 border border-gray-800 text-gray-800 text-sm font-bold py-2 px-4 rounded hover:bg-gray-800 hover:text-white">Search</button>
        <a href="myQuestions.php" class="flex-shrink-0 border border-gray-800 text-gray-800  text-sm font-bold py-2 px-4 rounded hover:bg-gray-800 hover:text-white">Reset</a>
      </form>
    </div>

    <div class="pt-10 flex flex-col gap-y-2">
      <?php foreach ($result as $row) : ?>
        <div class="w-full p-6 bg-white border border-gray-200 rounded-3xl shadow">
          <div class="flex items-center justify-between mb-5">
            <div>
              <a href="readMore.php?question_id=<?= $row['question_id'] ?>">
                <h5 class="text-2xl font-semibold tracking-tight text-gray-800 underline"><?= htmlspecialchars($row['title']) ?></h5>
              </a>
              <p class="mb-3 text-base text-gray-500"><?= nl2br(htmlspecialchars(textLimit($row['content']))) ?></p>
            </div>
            <div class="flex gap-3">
              <a href="editQuestion.php?question_id=<?= $row['question_id'] ?>">
                <button>
                  <svg class="w-8 h-16 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                  </svg>
                </button>
              </a>
              <a href="deleteQuestion.php?question_id=<?= $row['question_id'] ?>">
                <button>
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
    </div>
    <div class="text-center">
      Page <?= $current_page ?> of <?= $total_pages ?>
    </div>


  </main>
</body>

</html>