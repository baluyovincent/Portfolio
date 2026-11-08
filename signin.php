<?php
require 'connect.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error    = '';
$username = '';
$notice   = isset($_GET['registered']) ? 'Account created! You can now sign in.' : '';
$notice   = isset($_GET['loggedout']) ? 'You have been logged out.' : $notice;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!csrf_valid()) {
        $error = 'Your session expired. Please try again.';
    } elseif ($username === '' || $password === '') {
        $error = 'Please enter your username and password.';
    } else {
        $stmt = $conn->prepare('SELECT id, password FROM users WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int) $user['id'];
            header('Location: index.php');
            exit;
        }
        $error = 'Incorrect username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | Portfolio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
    <main class="auth-card">
        <h1 class="auth-title">Welcome Back</h1>
        <p class="auth-sub">Please enter your details to sign in</p>

        <?php if ($notice): ?>
            <div class="alert alert-ok" role="status"><?= e($notice) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error" role="alert"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post" novalidate>
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

            <label for="username">Email address</label>
            <input type="text" id="username" name="username" value="<?= e($username) ?>" placeholder="Enter your email" autocomplete="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" required>

            <button type="submit" class="auth-btn">Log In</button>
        </form>

        <p class="auth-switch">Don't have an account? <a href="signup.php">Sign up</a></p>
    </main>
</body>
</html>