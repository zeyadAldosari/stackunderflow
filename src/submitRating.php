<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'], $_POST['rating'], $_POST['answer_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters.']);
    header("Location: readMore.php?question_id=" . $_POST['question_id']);
    exit;
}

$userId = $_SESSION['user_id'];
$rating = (int)$_POST['rating'];
$answerId = (int)$_POST['answer_id'];

if ($rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Rating must be between 1 and 5.']);
    exit;
}

$query = "SELECT * FROM Rating WHERE user_id = :user_id AND answer_id = :answer_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $userId);
$stmt->bindParam(':answer_id', $answerId);
$stmt->execute();
$existingRating = $stmt->fetch();


if ($existingRating) {
    $updateQuery = "UPDATE Rating SET rating = :rating WHERE user_id = :user_id AND answer_id = :answer_id";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':rating', $rating);
    $updateStmt->bindParam(':user_id', $userId);
    $updateStmt->bindParam(':answer_id', $answerId);
} else {
    $insertQuery = "INSERT INTO Rating (user_id, answer_id, rating) VALUES (:user_id, :answer_id, :rating)";
    $updateStmt = $pdo->prepare($insertQuery);
    $updateStmt->bindParam(':rating', $rating);
    $updateStmt->bindParam(':user_id', $userId);
    $updateStmt->bindParam(':answer_id', $answerId);
}

if ($updateStmt->execute()) {
    $_SESSION['rating_success'] = "Rating submitted successfully!";
    header("Location: readMore.php?question_id=" . $_POST['question_id']);
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update rating.']);
}
