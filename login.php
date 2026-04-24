<?php
require __DIR__ . '/data.php';
require __DIR__ . '/includes/functions.php';

// Already logged in?
if (isLoggedIn()) {
    header('Location: account.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/server/connection.php';
    require __DIR__ . '/server/auth.php';

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $result = authLogin($con, $email, $password);

    if ($result['success']) {
        $_SESSION['user_id']   = $result['user']['user_id'];
        $_SESSION['user_name'] = $result['user']['user_name'];

        $redirect = $_GET['redirect'] ?? 'account.php';
        header('Location: ' . $redirect);
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
    <title>Login | <?= htmlspecialchars($site['brand']) ?></title>
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
                <h2 class="auth-title">Welcome back</h2>
                <p class="auth-subtitle">Sign in to your account</p>

                <?php if ($error): ?>
                    <div class="notice error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form id="login-form" action="login.php<?= isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : '' ?>" method="post">
                    <div class="form-group">
                        <label for="login-email">Email</label>
                        <input type="email" class="field-control" id="login-email" name="email"
                               placeholder="you@example.com"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <input type="password" class="field-control" id="login-password" name="password"
                               placeholder="Your password" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="primary-btn full-btn">Sign In</button>
                    </div>
                    <div class="form-group text-center">
                        <a href="register.php" class="auth-link">Don't have an account? <strong>Register</strong></a>
                    </div>
                </form>

                <div class="auth-demo-hint">
                    <p>Demo: <code>alice@example.com</code> / <code>password123</code></p>
                </div>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
