<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$question_id = isset($_POST['question_id']) ? (int)$_POST['question_id'] : null;
$answer_id = isset($_POST['answer_id']) ? (int)$_POST['answer_id'] : null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = trim($_POST['comment']);
    $user_id = $_SESSION['user_id']; 

    $insert_query = "INSERT INTO Comments (content, user_id, question_id, answer_id) VALUES (:content, :user_id, :question_id, :answer_id)";
    $stmt = $pdo->prepare($insert_query);
    $stmt->bindParam(':content', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
    $stmt->bindParam(':answer_id', $answer_id, PDO::PARAM_INT);


    if ($stmt->execute()) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        echo "There was an error adding the comment.";
    }
} else {
    die('Invalid request.');
}
