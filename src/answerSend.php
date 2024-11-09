<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit(); 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answer_content'], $_POST['question_id'])) {
    $answer_content = $_POST['answer_content'];
    $question_id = (int)$_POST['question_id'];
    $user_id = $_SESSION['user_id'];

    $insert_answer_query = "INSERT INTO Answer (question_id, user_id, content) VALUES (:question_id, :user_id, :content)";
    $insert_answer_stmt = $pdo->prepare($insert_answer_query);
    $insert_answer_stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
    $insert_answer_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $insert_answer_stmt->bindParam(':content', $answer_content, PDO::PARAM_STR);
    
    if ($insert_answer_stmt->execute()) {
        header("Location: readMore.php?question_id=" . $question_id);
        exit();
    } else {
        die('Failed to post your answer. Please try again.');
    }
} else {
    $_SESSION['error_message'] = 'Invalid request. Please provide all required fields.';
    header('Location: somePage.php');  
    exit();
}
?>

