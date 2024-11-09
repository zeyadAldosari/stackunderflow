<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $stmt = $pdo->prepare("INSERT INTO User (username, password) VALUES (:username, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashed_password);

    try {
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;

            header("Location: index.php");
            exit();
        } else {
            echo "Error: Unable to create user.";
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION['error'] = 'username already exists.';
        } else {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
        header("Location: signup.php");
        exit();
    }
}
