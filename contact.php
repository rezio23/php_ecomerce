<?php
require __DIR__ . '/data.php';
require __DIR__ . '/includes/functions.php';

$success = false;
$errors  = [];
$posted  = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];

// Pre-fill if logged in
if (isLoggedIn()) {
    require __DIR__ . '/server/connection.php';
    require __DIR__ . '/server/auth.php';
    $user = getCurrentUser($con);
    $posted['name']  = $user['user_name']  ?? '';
    $posted['email'] = $user['user_email'] ?? '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($con)) require __DIR__ . '/server/connection.php';

    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $posted  = compact('name', 'email', 'subject', 'message');

    if ($name    === '') $errors[] = 'Name is required.';
    if ($email   === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if ($subject === '') $errors[] = 'Subject is required.';
    if (strlen($message) < 10) $errors[] = 'Message must be at least 10 characters.';

    if (empty($errors)) {
        // Save to DB (subject stored in name field — extend table if needed)
        $fullMsg = "[Subject: $subject]\n\n$message";
        $stmt = $con->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $fullMsg);
        $stmt->execute();
        $stmt->close();
        $success = true;
        $posted  = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
    }
}

$faqs = [
    ['q' => 'How long does shipping take?',
     'a' => 'Standard shipping takes 3–5 business days within Cambodia. International orders typically arrive within 7–14 business days.'],
    ['q' => 'Can I return or exchange an item?',
     'a' => 'Yes. We accept returns within 14 days of delivery. Items must be unworn, unwashed, and in original packaging.'],
    ['q' => 'Do you offer size exchanges?',
     'a' => 'Absolutely. If your size doesn\'t fit, contact us within 14 days and we\'ll arrange an exchange at no extra charge.'],
    ['q' => 'How do I track my order?',
     'a' => 'Once your order ships, you\'ll receive a tracking number via email. You can also check your order status in your account dashboard.'],
    ['q' => 'Do you ship internationally?',
     'a' => 'Yes, we ship to most countries in Southeast Asia and beyond. Shipping costs are calculated at checkout.'],
];
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

    <!-- Page Hero -->
    <div class="page-hero">
        <div class="container">
            <div class="breadcrumb">
                <a href="index.php">Home</a><span>/</span><span>Contact</span>
            </div>
            <h1>Get in Touch</h1>
            <p>We're here to help. Send us a message and we'll get back to you within 24 hours.</p>
        </div>
    </div>

    <main>

        <!-- Info Cards Strip -->
        <section class="contact-cards-strip">
            <div class="container">
                <div class="contact-cards-grid">
                    <div class="contact-info-card">
                        <div class="contact-card-icon">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <h4>Call Us</h4>
                        <p><?= htmlspecialchars($site['contact_phone']) ?></p>
                        <span>Mon–Sat, 9am–6pm</span>
                    </div>
                    <div class="contact-info-card">
                        <div class="contact-card-icon">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <h4>Email Us</h4>
                        <p><?= htmlspecialchars($site['contact_email']) ?></p>
                        <span>Reply within 24 hours</span>
                    </div>
                    <div class="contact-info-card">
                        <div class="contact-card-icon">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <h4>Visit Us</h4>
                        <p><?= htmlspecialchars($site['contact_address']) ?></p>
                        <span>Mon–Sat, 10am–7pm</span>
                    </div>
                    <div class="contact-info-card">
                        <div class="contact-card-icon">
                            <i class="fa-brands fa-instagram"></i>
                        </div>
                        <h4>Follow Us</h4>
                        <p>@<?= strtolower(htmlspecialchars($site['brand'])) ?></p>
                        <span>DMs open daily</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Form + Map -->
        <section class="contact-main-section section-space">
            <div class="container">
                <div class="contact-layout">

                    <!-- Form -->
                    <div class="contact-form-col">
                        <h2 class="contact-form-title">Send a Message</h2>
                        <p class="contact-form-subtitle">Fill in the form below and we'll get back to you as soon as possible.</p>

                        <?php if ($success): ?>
                            <div class="notice success contact-success">
                                <i class="fa-solid fa-circle-check"></i>
                                <div>
                                    <strong>Message sent!</strong>
                                    <p>Thanks for reaching out. We'll get back to you within 24 hours.</p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($errors)): ?>
                            <div class="notice error">
                                <?php foreach ($errors as $e): ?><p><?= htmlspecialchars($e) ?></p><?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <form action="contact.php" method="post" class="contact-form">
                            <div class="contact-form-row">
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
                            </div>

                            <div class="form-group">
                                <label for="contact-subject">Subject</label>
                                <select class="field-control" id="contact-subject" name="subject" required>
                                    <option value="" disabled <?= $posted['subject'] === '' ? 'selected' : '' ?>>Select a subject…</option>
                                    <option value="Order Inquiry"      <?= $posted['subject'] === 'Order Inquiry' ? 'selected' : '' ?>>Order Inquiry</option>
                                    <option value="Return / Exchange"  <?= $posted['subject'] === 'Return / Exchange' ? 'selected' : '' ?>>Return / Exchange</option>
                                    <option value="Product Question"   <?= $posted['subject'] === 'Product Question' ? 'selected' : '' ?>>Product Question</option>
                                    <option value="Shipping"           <?= $posted['subject'] === 'Shipping' ? 'selected' : '' ?>>Shipping</option>
                                    <option value="Feedback"           <?= $posted['subject'] === 'Feedback' ? 'selected' : '' ?>>Feedback</option>
                                    <option value="Other"              <?= $posted['subject'] === 'Other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="contact-message">Message</label>
                                <textarea class="field-control" id="contact-message" name="message"
                                          rows="6" placeholder="How can we help you?"
                                          required><?= htmlspecialchars($posted['message']) ?></textarea>
                            </div>

                            <button type="submit" class="primary-btn">
                                <i class="fa-solid fa-paper-plane"></i> Send Message
                            </button>
                        </form>
                    </div>

                    <!-- Map + Hours -->
                    <div class="contact-right-col">
                        <div class="contact-map-block">
                            <h3>Find Us</h3>
                            <div class="contact-map-placeholder">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d125036.14657530284!2d104.83597865!3d11.5449103!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x310951a548db57cd%3A0xe934a259dd0d8a7a!2sPhnom%20Penh!5e0!3m2!1sen!2skh!4v1714000000000"
                                    width="100%" height="260" style="border:0; border-radius: 12px;"
                                    allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                        </div>

                        <div class="contact-hours-block">
                            <h3>Opening Hours</h3>
                            <ul class="hours-list">
                                <li><span>Monday – Friday</span><span>9:00 am – 6:00 pm</span></li>
                                <li><span>Saturday</span><span>10:00 am – 5:00 pm</span></li>
                                <li class="closed"><span>Sunday</span><span>Closed</span></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="faq-section section-space" style="background: var(--soft-bg);">
            <div class="container">
                <div class="section-header">
                    <span>Support</span>
                    <h2>Frequently Asked Questions</h2>
                    <p>Quick answers to common questions about orders, shipping, and returns.</p>
                </div>

                <div class="faq-list">
                    <?php foreach ($faqs as $i => $faq): ?>
                        <div class="faq-item" id="faq-<?= $i ?>">
                            <button class="faq-question" onclick="toggleFaq(<?= $i ?>)" aria-expanded="false">
                                <?= htmlspecialchars($faq['q']) ?>
                                <i class="fa-solid fa-chevron-down faq-icon"></i>
                            </button>
                            <div class="faq-answer" id="faq-answer-<?= $i ?>">
                                <p><?= htmlspecialchars($faq['a']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

    </main>

    <?php require __DIR__ . '/includes/footer.php'; ?>

    <script>
        function toggleFaq(index) {
            const answer = document.getElementById('faq-answer-' + index);
            const btn    = answer.previousElementSibling;
            const icon   = btn.querySelector('.faq-icon');
            const isOpen = answer.classList.contains('open');

            // Close all
            document.querySelectorAll('.faq-answer').forEach(a => a.classList.remove('open'));
            document.querySelectorAll('.faq-question').forEach(b => {
                b.classList.remove('active');
                b.setAttribute('aria-expanded', 'false');
                b.querySelector('.faq-icon').style.transform = '';
            });

            if (!isOpen) {
                answer.classList.add('open');
                btn.classList.add('active');
                btn.setAttribute('aria-expanded', 'true');
                icon.style.transform = 'rotate(180deg)';
            }
        }
    </script>
</body>
</html>
