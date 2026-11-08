<?php
require 'connect.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$errors   = [];
$fullname = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (!csrf_valid()) {
        $errors[] = 'Your session expired. Please try again.';
    }

    if (mb_strlen($fullname) < 2 || mb_strlen($fullname) > 100) {
        $errors[] = 'Full name must be 2 to 100 characters.';
    }
    if ($username === '' || mb_strlen($username) > 50) {
    $errors[] = 'Please enter a username (up to 50 characters).';
    
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    
    if (!$errors) {
        $stmt = $conn->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = 'That username is already taken. Please choose another.';
        }
        $stmt->close();
    }

    
    $newFileName = null;
    $tmpPath     = null;
    if (!$errors) {
        $file = $_FILES['profile_image'] ?? null;

        if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) {
            $errors[] = 'Please choose a profile picture.';
        } elseif ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
            $errors[] = 'The image is too large. The maximum size is 2 MB.';
        } elseif ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'The image could not be uploaded. Please try again.';
        } elseif ($file['size'] > 2 * 1024 * 1024) {
            $errors[] = 'The image is too large. The maximum size is 2 MB.';
        } else {
            $finfo   = new finfo(FILEINFO_MIME_TYPE);
            $mime    = $finfo->file($file['tmp_name']);
            $allowed = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/gif'  => 'gif',
                'image/webp' => 'webp',
            ];
            if (!isset($allowed[$mime]) || @getimagesize($file['tmp_name']) === false) {
                $errors[] = 'Invalid file type. Please upload a JPG, PNG, GIF, or WEBP image.';
            } else {
                $newFileName = bin2hex(random_bytes(10)) . '.' . $allowed[$mime];
                $tmpPath     = $file['tmp_name'];
            }
        }
    }

    
    if (!$errors) {
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (!move_uploaded_file($tmpPath, $uploadDir . $newFileName)) {
            $errors[] = 'The image could not be saved. Check that the uploads folder is writable.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            try {
                $stmt = $conn->prepare(
                    'INSERT INTO users (fullname, username, password, profile_image) VALUES (?, ?, ?, ?)'
                );
                $stmt->bind_param('ssss', $fullname, $username, $hash, $newFileName);
                $stmt->execute();
                $stmt->close();

                header('Location: signin.php?registered=1');
                exit;
            } catch (mysqli_sql_exception $ex) {
                @unlink($uploadDir . $newFileName);
                $errors[] = ($ex->getCode() === 1062)
                    ? 'That username is already taken. Please choose another.'
                    : 'Something went wrong while creating your account. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | Portfolio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
    <main class="auth-card">
        <h1 class="auth-title">Create Account</h1>
        <p class="auth-sub">Join us today! Fill in the details below to get started.</p>

        <?php if ($errors): ?>
            <div class="alert alert-error" role="alert">
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?= e($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

            <label for="fullname">Full Name</label>
            <input type="text" id="fullname" name="fullname" value="<?= e($fullname) ?>" placeholder="Enter your full name" maxlength="100" required>

            <label for="username">Email address</label>
            <input type="text" id="username" name="username" value="<?= e($username) ?>" placeholder="Enter your email" maxlength="50" autocomplete="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Create a password" autocomplete="new-password" required>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" autocomplete="new-password" required>

            <label for="profile_image">Profile Picture</label>
            <input type="file" id="profile_image" name="profile_image" accept="image/jpeg,image/png,image/gif,image/webp" required>

            <button type="submit" class="auth-btn">Sign Up</button>
        </form>

        <p class="auth-switch">Already have an account? <a href="signin.php">Sign in</a></p>
    </main>
</body>
</html>