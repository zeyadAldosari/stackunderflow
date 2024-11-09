<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die('You must be logged in to view this page.');
}

$questionId = $_GET['question_id'] ?? null;
if (!$questionId) {
    header('Location: myQuestions.php');
    exit();
}

$stmt = $pdo->prepare("SELECT title, content FROM Question WHERE question_id = :questionId AND user_id = :userId");
$stmt->bindParam(':questionId', $questionId, PDO::PARAM_INT);
$stmt->bindParam(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$question = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$question) {
    die('Question not found or you do not have permission to edit it.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    if ($title && $content) {
        $updateStmt = $pdo->prepare("UPDATE Question SET title = :title, content = :content WHERE question_id = :questionId");
        $updateStmt->bindParam(':title', $title);
        $updateStmt->bindParam(':content', $content);
        $updateStmt->bindParam(':questionId', $questionId);
        $updateStmt->execute();

        header('Location: myQuestions.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Edit Question</title>
    <link href="./output.css" rel="stylesheet" />
</head>

<body class="h-screen flex justify-center items-center">
    <main class="bg-gray-200 rounded-lg p-4 w-full m-20">
        <h1 class="text-gray-800 font-bold text-3xl mb-4">Edit Question</h1>
        <form action="editQuestion.php?question_id=<?= htmlspecialchars($questionId) ?>" method="POST">
            <label for="title" class="block mb-2 text-lg font-medium text-gray-900">Title</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($question['title']) ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-2xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>

            <label for="content" class="block mb-2 text-lg font-medium text-gray-900">Content</label>
            <textarea id="content" name="content" rows="10" class="bg-gray-50 border border-gray-300 text-gray-900 text-xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" rows="4" required><?= htmlspecialchars($question['content']) ?></textarea>

            <button type="submit" class="mt-4 bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded">Update</button>
            <a href="myQuestions.php" class="mt-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</a>
        </form>
    </main>
</body>

</html>