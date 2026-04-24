<?php
require __DIR__ . '/data.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/server/connection.php';

$slug = trim($_GET['slug'] ?? '');
if ($slug === '') {
    header('Location: blog.php');
    exit;
}

$stmt = $con->prepare("SELECT * FROM blog_posts WHERE slug = ? LIMIT 1");
$stmt->bind_param("s", $slug);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$post) {
    http_response_code(404);
}

// Related posts (same category, exclude current)
$related = [];
if ($post) {
    $stmt2 = $con->prepare("SELECT * FROM blog_posts WHERE category = ? AND slug != ? ORDER BY published_at DESC LIMIT 3");
    $stmt2->bind_param("ss", $post['category'], $post['slug']);
    $stmt2->execute();
    $related = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt2->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $post ? htmlspecialchars($post['title']) : 'Post Not Found' ?> | <?= htmlspecialchars($site['brand']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/includes/header.php'; ?>

    <?php if (!$post): ?>
        <main class="section-space">
            <div class="container">
                <div class="notice error">Post not found. <a href="blog.php">Back to blog</a>.</div>
            </div>
        </main>
    <?php else: ?>

    <!-- Post Hero -->
    <div class="post-hero">
        <img src="<?= htmlspecialchars($post['cover_image']) ?>"
             alt="<?= htmlspecialchars($post['title']) ?>"
             class="post-hero-image">
        <div class="post-hero-overlay"></div>
        <div class="post-hero-content container">
            <div class="breadcrumb breadcrumb-light">
                <a href="index.php">Home</a><span>/</span>
                <a href="blog.php">Blog</a><span>/</span>
                <span><?= htmlspecialchars($post['title']) ?></span>
            </div>
            <span class="blog-category-badge"><?= htmlspecialchars($post['category']) ?></span>
            <h1 class="post-hero-title"><?= htmlspecialchars($post['title']) ?></h1>
            <div class="post-hero-meta">
                <span><i class="fa-regular fa-user"></i> <?= htmlspecialchars($post['author']) ?></span>
                <span><i class="fa-regular fa-calendar"></i> <?= date('F j, Y', strtotime($post['published_at'])) ?></span>
            </div>
        </div>
    </div>

    <main class="post-page section-space">
        <div class="container">
            <div class="post-layout">

                <!-- Article -->
                <article class="post-article">
                    <p class="post-lead"><?= htmlspecialchars($post['excerpt']) ?></p>
                    <div class="post-content">
                        <?= $post['content'] ?>
                    </div>

                    <!-- Share -->
                    <div class="post-share">
                        <span>Share this article:</span>
                        <div class="share-links">
                            <a href="#" class="share-btn share-fb" aria-label="Share on Facebook">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="#" class="share-btn share-tw" aria-label="Share on Twitter">
                                <i class="fa-brands fa-x-twitter"></i>
                            </a>
                            <a href="#" class="share-btn share-wa" aria-label="Share on WhatsApp">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>

                    <a href="blog.php" class="back-link">
                        <i class="fa-solid fa-arrow-left"></i> Back to Blog
                    </a>
                </article>

                <!-- Sidebar -->
                <aside class="post-sidebar">
                    <!-- Author -->
                    <div class="post-sidebar-block">
                        <h4 class="sidebar-title">About the Author</h4>
                        <div class="author-card">
                            <div class="author-avatar">
                                <?= strtoupper(substr($post['author'], 0, 1)) ?>
                            </div>
                            <div>
                                <p class="author-name"><?= htmlspecialchars($post['author']) ?></p>
                                <p class="author-role">Editorial Team</p>
                            </div>
                        </div>
                    </div>

                    <!-- Related Posts -->
                    <?php if (!empty($related)): ?>
                    <div class="post-sidebar-block">
                        <h4 class="sidebar-title">Related Articles</h4>
                        <div class="related-posts">
                            <?php foreach ($related as $rel): ?>
                                <a href="blog-post.php?slug=<?= urlencode($rel['slug']) ?>" class="related-post-item">
                                    <img src="<?= htmlspecialchars($rel['cover_image']) ?>"
                                         alt="<?= htmlspecialchars($rel['title']) ?>">
                                    <div>
                                        <p class="related-post-title"><?= htmlspecialchars($rel['title']) ?></p>
                                        <span class="related-post-date"><?= date('M j, Y', strtotime($rel['published_at'])) ?></span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- CTA -->
                    <div class="post-sidebar-block sidebar-cta">
                        <p>Ready to upgrade your wardrobe?</p>
                        <a href="shop.php" class="primary-btn full-btn">Shop Now</a>
                    </div>
                </aside>

            </div>
        </div>
    </main>

    <?php endif; ?>

    <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
