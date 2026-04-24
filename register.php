<?php
require __DIR__ . '/data.php';
require __DIR__ . '/includes/functions.php';

if (isLoggedIn()) {
    header('Location: account.php');
    exit;
}

$error   = '';
$success = '';
$posted  = ['name' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/server/connection.php';
    require __DIR__ . '/server/auth.php';

    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirmPassword'] ?? '';
    $posted   = ['name' => $name, 'email' => $email];

    $result = authRegister($con, $name, $email, $password, $confirm);

    if ($result['success']) {
        $_SESSION['user_id']   = $result['user_id'];
        $_SESSION['user_name'] = $result['user_name'];
        header('Location: account.php');
        exit;
    } else {
        $error = $result['error'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | <?= htmlspecialchars($site['brand']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/includes/header.php'; ?>

    <main>
        <section class="auth-section">
            <div class="auth-card">
                <h2 class="auth-title">Create account</h2>
                <p class="auth-subtitle">Join us and start shopping</p>

                <?php if ($error): ?>
                    <div class="notice error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form id="register-form" action="register.php" method="post">
                    <div class="form-group">
                        <label for="register-name">Full Name</label>
                        <input type="text" class="field-control" id="register-name" name="name"
                               placeholder="Your name"
                               value="<?= htmlspecialchars($posted['name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="register-email">Email</label>
                        <input type="email" class="field-control" id="register-email" name="email"
                               placeholder="you@example.com"
                               value="<?= htmlspecialchars($posted['email']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="register-password">Password</label>
                        <input type="password" class="field-control" id="register-password" name="password"
                               placeholder="Min. 6 characters" required>
                    </div>
                    <div class="form-group">
                        <label for="register-confirm-password">Confirm Password</label>
                        <input type="password" class="field-control" id="register-confirm-password"
                               name="confirmPassword" placeholder="Repeat password" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="primary-btn full-btn">Create Account</button>
                    </div>
                    <div class="form-group text-center">
                        <a href="login.php" class="auth-link">Already have an account? <strong>Sign In</strong></a>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
