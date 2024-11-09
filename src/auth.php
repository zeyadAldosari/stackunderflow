<?php
session_start();

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    try {
        $stmt = $pdo->prepare("SELECT user_id, password FROM User WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $username;

                header("Location: index.php");
                exit();
            } else {
                $_SESSION['error_message'] = "Invalid username or password.";
                header("Location: login.php");
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Invalid username or password.";
            header("Location: login.php");
            exit();
        }
    } catch (PDOException $e) {
        die("ERROR: Could not execute query: " . $e->getMessage());
    }
}
