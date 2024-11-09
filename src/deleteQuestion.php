<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die('You must be logged in to perform this action.');
}

if (isset($_GET['question_id'])) {
    $questionId = $_GET['question_id'];
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        $stmt = $pdo->prepare("DELETE FROM Question WHERE question_id = :questionId");
        $stmt->bindParam(':questionId', $questionId, PDO::PARAM_INT);
        $stmt->execute();

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

    <title>Delete Question</title>
    <link href="./output.css" rel="stylesheet" />
</head>

<body class="h-screen flex justify-center items-center">
    <main class=" bg-gray-200 rounded-lg p-4">
        <h1 class="text-gray-800 font-bold text-3xl mb-4">Confirm Deletion</h1>
        <form action="deleteQuestion.php?question_id=<?= htmlspecialchars($questionId) ?>" method="POST">
            <p>Are you sure you want to delete this question?</p>
            <button type="submit" name="confirm" value="yes" class="bg-red-500 text-white p-2 rounded">Yes</button>
            <a href="myQuestions.php" class="bg-gray-500 text-white p-2 rounded">Cancel</a>
        </form>
    </main>
</body>

</html>