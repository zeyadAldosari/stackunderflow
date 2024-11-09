<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die('You must be logged in to perform this action.');
}

$answerId = $_GET['answer_id'] ?? null;
if (!$answerId) {
    header('Location: myAnswers.php'); 
    exit();
}

if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
    $stmt = $pdo->prepare("DELETE FROM Answer WHERE answer_id = :answerId AND user_id = :userId");
    $stmt->bindParam(':answerId', $answerId, PDO::PARAM_INT);
    $stmt->bindParam(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    header('Location: myAnswers.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Delete Answer</title>
    <link href="./output.css" rel="stylesheet" />
</head>
<body class="h-screen flex justify-center items-center">
    <main class="bg-gray-200 rounded-lg p-4">
        <h1 class="text-gray-800 font-bold text-3xl mb-4">Confirm Deletion</h1>
        <form action="deleteAnswer.php?answer_id=<?= htmlspecialchars($answerId) ?>" method="POST">
            <p>Are you sure you want to delete this answer?</p>
            <button type="submit" name="confirm" value="yes" class="bg-red-500 text-white p-2 rounded">Yes</button>
            <a href="myAnswers.php" class="bg-gray-500 text-white p-2 rounded">Cancel</a>
        </form>
    </main>
</body>
</html>
