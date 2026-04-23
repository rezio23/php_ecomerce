<?php
require __DIR__ . '/data.php';
require __DIR__ . '/includes/functions.php';
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
            <div class="container" style="text-align:center; margin-top: 3rem; padding-top: 3rem; padding-bottom: 3rem;">
                <h3>Contact Us</h3>
                <hr style="max-width: 60px; margin: 1rem auto;">

                <p style="max-width: 50%; margin: 0 auto 1.5rem auto; line-height: 1.8;">
                    <strong>Phone:</strong><br>
                    <span style="color: #e67e22;"><?= htmlspecialchars($site['contact_phone']) ?></span>
                </p>

                <p style="max-width: 50%; margin: 0 auto 1.5rem auto; line-height: 1.8;">
                    <strong>Email:</strong><br>
                    <span style="color: #e67e22;"><?= htmlspecialchars($site['contact_email']) ?></span>
                </p>

                <p style="max-width: 50%; margin: 0 auto 1.5rem auto; line-height: 1.8;">
                    <strong>Address:</strong><br>
                    <span style="color: #e67e22;"><?= htmlspecialchars($site['contact_address']) ?></span>
                </p>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
