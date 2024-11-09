<?php
session_start();
include 'db.php';

if (!isset($_GET['question_id'])) {
    die('Question ID not specified.');
}

$question_id = (int)$_GET['question_id'];

$question_query = "SELECT q.title, q.content, u.username, q.date_time
                   FROM Question q
                   JOIN User u ON q.user_id = u.user_id
                   WHERE q.question_id = :question_id";
$question_stmt = $pdo->prepare($question_query);
$question_stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
$question_stmt->execute();
$question = $question_stmt->fetch(PDO::FETCH_ASSOC);

if (!$question) {
    die('Question not found.');
}

$answers_query = "SELECT a.answer_id, a.content, u.username, a.answer_date,
                  COALESCE(AVG(r.rating), 0) AS avg_rating,
                  (SELECT r.rating FROM Rating r WHERE r.answer_id = a.answer_id AND r.user_id = :user_id) AS user_rating
                  FROM Answer a
                  JOIN User u ON a.user_id = u.user_id
                  LEFT JOIN Rating r ON a.answer_id = r.answer_id
                  WHERE a.question_id = :question_id
                  GROUP BY a.answer_id
                  ORDER BY avg_rating DESC";
$answers_stmt = $pdo->prepare($answers_query);
$answers_stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
$answers_stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$answers_stmt->execute();
$answers = $answers_stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Details</title>
    <link rel="stylesheet" href="https://cdn.tailwindcss.com">
    <link href="./output.css" rel="stylesheet">
    <style>
        * {
            font-family: "Inter", sans-serif;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body class="bg-gray-200">
    <?php include './components/nav.php'; ?>
    <?php include './components/sideBar.php'; ?>

    <main class="p-14 ml-64 mt-8">
        <div class="flex flex-col gap-y-2">
            <h1 class="text-gray-800 font-bold text-5xl mb-6"><?= htmlspecialchars($question['title']) ?></h1>
            <p class="text-xl text-gray-800 mb-3"><?= nl2br(htmlspecialchars($question['content'])) ?></p>
            <p class="text-sm text-gray-500">Asked by: <?= htmlspecialchars($question['username']) ?></p>
            <p class="text-sm text-gray-500">Asked on: <?= date('M d, Y, h:i A', strtotime($question['date_time'])) ?></p>

            <div class="comments-section">
                <?php

                $comments_query = "SELECT c.comment_id, c.content, u.username
                       FROM Comments c
                       JOIN User u ON c.user_id = u.user_id
                       WHERE c.question_id = :question_id AND c.answer_id IS NULL";
                $comments_stmt = $pdo->prepare($comments_query);
                $comments_stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
                $comments_stmt->execute();
                $comments = $comments_stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($comments as $comment) :
                ?>
                    <div class="comment-item mt-4">
                        <p><?= htmlspecialchars($comment['content']) ?> - <em><?= htmlspecialchars($comment['username']) ?></em></p>

                    </div>
                <?php endforeach; ?>
            </div>
            <div class="add-comment-form hidden">
                <form action="addComment.php" method="POST">
                    <input type="hidden" name="question_id" value="<?= $question_id ?>">

                    <textarea name="comment" rows="5" cols="50" class="bg-white mt-4" required></textarea>
                    <p>
                        <button type="submit" class="border border-gray-800 rounded-xl p-2 text-sm">Add Comment</button>
                    </p>
                </form>
            </div>
            <button class="toggle-comment-form mt-4 self-start">+ comment</button>
        </div>
        <?php if (isset($_SESSION['rating_success'])) : ?>
            <div class="text-green-400 bg-green-200 p-2 rounded-md mt-4" onclick="this.classList.toggle('hidden')">
                <div>
                    <?= $_SESSION['rating_success']; ?>
                    <?php unset($_SESSION['rating_success']); ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="pt-10 flex flex-col gap-y-2">
            <?php foreach ($answers as $answer) : ?>
                <div class="w-full p-6 bg-white border border-gray-200 rounded-3xl shadow mb-2">
                    <h5 class="text-2xl font-semibold tracking-tight text-gray-800"><?= nl2br(htmlspecialchars($answer['content'])) ?></h5>
                    <p class="mb-3 text-sm text-gray-500">Answered by: <?= htmlspecialchars($answer['username']) ?></p>
                    <p class="mb-3 text-sm text-gray-500">Answered on: <?= isset($answer['answer_date']) ? date('M d, Y, h:i A', strtotime($answer['answer_date'])) : 'Date not available' ?></p>
                    <div class="mb-4">
                        Average Rating: <span class="font-semibold"><?= round($answer['avg_rating'], 1) ?></span>
                    </div>
                    <form action="submitRating.php" method="POST" class="flex items-center">
                        <input type="hidden" name="answer_id" value="<?= $answer['answer_id'] ?>">
                        <input type="hidden" name="question_id" value="<?= $question_id ?>">
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <label class="cursor-pointer">
                                <input type="radio" name="rating" class="hidden" value="<?= $i ?>" />
                                <span class="rating-number cursor-pointer rounded-lg p-4 mr-2 border border-solid border-gray-800 hover:bg-gray-200"><?= $i ?></span>
                            </label>
                        <?php endfor; ?>
                        <button type="submit" class="border border-gray-800 rounded-xl p-2 text-sm mt-2 hover:bg-gray-200">Submit Rating</button>
                    </form>
                    <div class="comments-section">
                        <?php

                        $comments_query = "SELECT c.comment_id, c.content, u.username
                                           FROM Comments c
                                           JOIN User u ON c.user_id = u.user_id
                                           WHERE c.answer_id = :answer_id";
                        $comments_stmt = $pdo->prepare($comments_query);
                        $comments_stmt->bindParam(':answer_id', $answer['answer_id'], PDO::PARAM_INT);
                        $comments_stmt->execute();
                        $comments = $comments_stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($comments as $comment) :
                        ?>
                            <div class="comment-item mt-4">
                                <p><?= htmlspecialchars($comment['content']) ?> - <em><?= htmlspecialchars($comment['username']) ?></em></p>
                                <!-- <?php if ($comment['user_id'] === $_SESSION['user_id']) : ?>
                                <?php endif; ?> -->
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="add-comment-form hidden">
                        <form action="addComment.php" method="POST">
                            <input type="hidden" name="answer_id" value="<?= $answer['answer_id'] ?>">
                            <input type="hidden" name="question_id" value="<?= $question_id ?>">
                            <textarea name="comment" rows="5" cols="50" class="bg-gray-200 mt-4" required></textarea>
                            <p>
                                <button type="submit" class="border border-gray-800 rounded-xl p-2 text-sm">Add Comment</button>
                            </p>
                        </form>
                    </div>
                    <button class="toggle-comment-form mt-4">+ comment</button>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="pt-10">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Your Answer</h2>
            <form method="POST" action="answerSend.php">
                <textarea name="answer_content" rows="5" class="w-full p-4 border border-gray-200 rounded-3xl mb-4" placeholder="Write your answer here..." required></textarea>
                <input type="hidden" name="question_id" value="<?= $question_id ?>">
                <button type="submit" class="border border-gray-800 rounded-md py-2 px-4 hover:bg-gray-200">Submit Answer</button>
            </form>
        </div>
    </main>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ratings = document.querySelectorAll('.rating-number');
            let selectedRating = 0;

            ratings.forEach(rating => {
                rating.addEventListener('click', function() {
                    ratings.forEach(num => num.classList.remove('bg-yellow-300'));
                    this.classList.add('bg-yellow-300');
                    selectedRating = this.getAttribute('data-rating');
                });
            });
        });
    </script>

</body>
<script>
    $(document).ready(function() {
        $('.toggle-comment-form').on('click', function() {
            $(this).siblings('.add-comment-form').toggle();
        });

        $('.delete-comment').on('click', function() {
            var commentId = $(this).data('comment-id');

            $.post('deleteComment.php', {
                comment_id: commentId
            }, function(response) {

            });
        });
    });
</script>


</html>