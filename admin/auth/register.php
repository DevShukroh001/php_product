<?php
session_start();

include_once(__DIR__ . '/../includes/database.php');

$username = $email = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
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

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
<?php
include_once("../includes/header.php");
?>
<div class="container mt-5" style="max-width: 500px;">
    <h2 class="mb-4">Register</h2>

    <?php if ($errors): ?>
      <div class="alert alert-danger">
        <ul>
          <?php foreach($errors as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form action="register.php" method="post">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" class="form-control" required />
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" class="form-control" required />
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required />
        </div>
        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="passwordConf" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
        <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here</a></p>
    </form>
</div>

</body>
</html>
