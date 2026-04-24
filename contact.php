<?php
require __DIR__ . '/data.php';
require __DIR__ . '/includes/functions.php';

$success = false;
$error   = '';
$posted  = ['name' => '', 'email' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/server/connection.php';

    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $message = trim($_POST['message'] ?? '');
    $posted  = compact('name', 'email', 'message');

    if ($name === '' || $email === '' || $message === '') {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $stmt = $con->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);
        $stmt->execute();
        $stmt->close();
        $success = true;
        $posted  = ['name' => '', 'email' => '', 'message' => ''];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | <?= htmlspecialchars($site['brand']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/includes/header.php'; ?>

    <main>
        <section class="contact-section section-space" id="contact">
            <div class="container">
                <div class="contact-layout">

                    <!-- Contact Info -->
                    <div class="contact-info-col">
                        <span class="eyebrow">Get in touch</span>
                        <h2>Contact Us</h2>
                        <p class="contact-intro">We'd love to hear from you. Send us a message and we'll get back to you as soon as possible.</p>

                        <div class="contact-detail">
                            <div class="contact-icon"><i class="fa-solid fa-phone"></i></div>
                            <div>
                                <p class="contact-label">Phone</p>
                                <p class="contact-value"><?= htmlspecialchars($site['contact_phone']) ?></p>
                            </div>
                        </div>

                        <div class="contact-detail">
                            <div class="contact-icon"><i class="fa-solid fa-envelope"></i></div>
                            <div>
                                <p class="contact-label">Email</p>
                                <p class="contact-value"><?= htmlspecialchars($site['contact_email']) ?></p>
                            </div>
                        </div>

                        <div class="contact-detail">
                            <div class="contact-icon"><i class="fa-solid fa-location-dot"></i></div>
                            <div>
                                <p class="contact-label">Address</p>
                                <p class="contact-value"><?= htmlspecialchars($site['contact_address']) ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="contact-form-col">
                        <?php if ($success): ?>
                            <div class="notice success">
                                <i class="fa-solid fa-circle-check"></i>
                                Message sent! We'll get back to you soon.
                            </div>
                        <?php endif; ?>

                        <?php if ($error): ?>
                            <div class="notice error"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <form action="contact.php" method="post" class="contact-form">
                            <div class="form-group">
                                <label for="contact-name">Your Name</label>
                                <input type="text" class="field-control" id="contact-name" name="name"
                                       placeholder="John Doe"
                                       value="<?= htmlspecialchars($posted['name']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="contact-email">Email Address</label>
                                <input type="email" class="field-control" id="contact-email" name="email"
                                       placeholder="you@example.com"
                                       value="<?= htmlspecialchars($posted['email']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="contact-message">Message</label>
                                <textarea class="field-control" id="contact-message" name="message"
                                          rows="5" placeholder="How can we help you?"
                                          required><?= htmlspecialchars($posted['message']) ?></textarea>
                            </div>
                            <button type="submit" class="primary-btn">
                                <i class="fa-solid fa-paper-plane"></i> Send Message
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
