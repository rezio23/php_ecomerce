<?php
require __DIR__ . '/data.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/server/connection.php';

// Fetch all posts
$stmt = $con->prepare("SELECT * FROM blog_posts ORDER BY published_at DESC");
$stmt->execute();
$posts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Category filter
$activeTag = trim($_GET['category'] ?? 'all');

$filteredPosts = ($activeTag === 'all')
    ? $posts
    : array_values(array_filter($posts, fn($p) => strtolower($p['category']) === strtolower($activeTag)));

// Unique categories
$blogCategories = array_unique(array_column($posts, 'category'));
sort($blogCategories);

$featured  = $posts[0] ?? null;
$remaining = array_slice($filteredPosts, ($activeTag === 'all') ? 1 : 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog | <?= htmlspecialchars($site['brand']) ?></title>
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
                <a href="index.php">Home</a><span>/</span><span>Blog</span>
            </div>
            <h1>Style Journal</h1>
            <p>Fashion tips, style guides, and the latest trends from our editorial team.</p>
        </div>
    </div>

    <main class="blog-page section-space">
        <div class="container">

            <?php if ($featured && $activeTag === 'all'): ?>
            <!-- Featured Post -->
            <a href="blog-post.php?slug=<?= urlencode($featured['slug']) ?>" class="featured-post-card">
                <div class="featured-post-image">
                    <img src="<?= htmlspecialchars($featured['cover_image']) ?>"
                         alt="<?= htmlspecialchars($featured['title']) ?>"
                         loading="lazy">
                </div>
                <div class="featured-post-body">
                    <span class="blog-category-badge"><?= htmlspecialchars($featured['category']) ?></span>
                    <h2 class="featured-post-title"><?= htmlspecialchars($featured['title']) ?></h2>
                    <p class="featured-post-excerpt"><?= htmlspecialchars($featured['excerpt']) ?></p>
                    <div class="post-meta">
                        <span class="post-author"><i class="fa-regular fa-user"></i> <?= htmlspecialchars($featured['author']) ?></span>
                        <span class="post-date"><i class="fa-regular fa-calendar"></i> <?= date('M j, Y', strtotime($featured['published_at'])) ?></span>
                    </div>
                    <span class="post-read-more">Read Article <i class="fa-solid fa-arrow-right"></i></span>
                </div>
            </a>
            <?php endif; ?>

            <!-- Category Tabs -->
            <div class="blog-tabs">
                <a href="blog.php?category=all" class="blog-tab <?= $activeTag === 'all' ? 'active' : '' ?>">All Posts</a>
                <?php foreach ($blogCategories as $cat): ?>
                    <a href="blog.php?category=<?= urlencode($cat) ?>"
                       class="blog-tab <?= strtolower($activeTag) === strtolower($cat) ? 'active' : '' ?>">
                        <?= htmlspecialchars($cat) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Posts Grid -->
            <?php if (empty($filteredPosts) && $activeTag !== 'all'): ?>
                <div class="shop-empty">
                    <i class="fa-solid fa-newspaper"></i>
                    <h3>No posts in this category yet.</h3>
                    <a href="blog.php" class="primary-btn">View All Posts</a>
                </div>
            <?php else: ?>
                <div class="blog-grid">
                    <?php foreach ($remaining as $post): ?>
                        <a href="blog-post.php?slug=<?= urlencode($post['slug']) ?>" class="blog-card">
                            <div class="blog-card-image">
                                <img src="<?= htmlspecialchars($post['cover_image']) ?>"
                                     alt="<?= htmlspecialchars($post['title']) ?>"
                                     loading="lazy">
                            </div>
                            <div class="blog-card-body">
                                <span class="blog-category-badge"><?= htmlspecialchars($post['category']) ?></span>
                                <h3 class="blog-card-title"><?= htmlspecialchars($post['title']) ?></h3>
                                <p class="blog-card-excerpt"><?= htmlspecialchars($post['excerpt']) ?></p>
                                <div class="post-meta">
                                    <span class="post-author"><i class="fa-regular fa-user"></i> <?= htmlspecialchars($post['author']) ?></span>
                                    <span class="post-date"><i class="fa-regular fa-calendar"></i> <?= date('M j, Y', strtotime($post['published_at'])) ?></span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </main>

    <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
