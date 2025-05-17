<?php
session_start();

include_once(__DIR__ . '/../includes/database.php');

$username = $email = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $passwordConf = $_POST['passwordConf'];

    if (empty($username)) $errors[] = "Username is required";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
    if (empty($password)) $errors[] = "Password is required";
    if ($password !== $passwordConf) $errors[] = "Passwords do not match";

    // Check if username or email exists
    $stmt = $connect->prepare("SELECT id FROM users WHERE username=? OR email=?");
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) $errors[] = "Username or Email already exists";
    $stmt->close();

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $connect->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $username, $email, $hashed);
        if ($stmt->execute()) {
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit;
        } else {
            $errors[] = "Error registering user";
        }
        $stmt->close();
    }
}
?>