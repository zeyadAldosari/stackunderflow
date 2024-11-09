<?php
include 'db.php';
include 'authCheck.php';
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST['title'] ?? null;
    $content = $_POST['content'] ?? null;
    $userId = $_SESSION['user_id'];

    if (empty($title) || empty($content)) {
        $_SESSION['error_message'] = "Please fill in all fields.";
        header("Location: createQuestion.html");
        exit();
    } else {
        $sql = "INSERT INTO Question (title, content, user_id, date_time) VALUES (:title, :content, :user_id, NOW())";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':user_id', $userId);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Question posted successfully!";
            } else {
                $_SESSION['error_message'] = "Failed to post question.";
            }
            header("Location: createQuestion.php");
            exit();
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "ERROR: Could not execute. " . $e->getMessage();
            header("Location: createQuestion.php");
            exit();
        }
    }
}
