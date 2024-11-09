<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die('You must be logged in to view this page.');
}

$answerId = $_GET['answer_id'] ?? null;
if (!$answerId) {
    header('Location: myAnswers.php'); 
    exit();
}

$stmt = $pdo->prepare("SELECT content FROM Answer WHERE answer_id = :answerId AND user_id = :userId");
$stmt->bindParam(':answerId', $answerId, PDO::PARAM_INT);
$stmt->bindParam(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$answer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$answer) {
    die('Answer not found or you do not have permission to edit it.'); 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'] ?? '';

    if ($content) {
        $updateStmt = $pdo->prepare("UPDATE Answer SET content = :content WHERE answer_id = :answerId");
        $updateStmt->bindParam(':content', $content);
        $updateStmt->bindParam(':answerId', $answerId);
        $updateStmt->execute();

        header('Location: myAnswers.php'); 
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
    <title>Edit Answer</title>
    <link href="./output.css" rel="stylesheet" />
</head>
<body class="h-screen flex justify-center items-center">
    <main class="bg-gray-200 rounded-lg p-4 w-full m-20">
        <h1 class="text-gray-800 font-bold text-3xl mb-4">Edit Answer</h1>
        <form action="editAnswer.php?answer_id=<?= htmlspecialchars($answerId) ?>" method="POST">
            <label for="content" class="block mb-2 text-lg font-medium text-gray-900">Content</label>
            <textarea id="content" name="content" class="bg-gray-50 border border-gray-300 text-gray-900 text-xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" rows="10" required><?= htmlspecialchars($answer['content']) ?></textarea>

            <button type="submit" class="mt-4 bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded">Update</button>
            <a href="myAnswers.php" class="mt-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</a>
        </form>
    </main>
</body>
</html>
