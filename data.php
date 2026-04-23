<?php

$site = [
    'brand' => 'orange',
    'tagline' => 'Minimal PHP eCommerce demo',
    'contact_email' => 'info@orange-demo.com',
    'contact_phone' => '+855 12 345 678',
    'contact_address' => 'Phnom Penh, Cambodia',
    'copyright' => 'orange © 2026 All Rights Reserved',
];

// Navbar links activated as shown in video 52: Activating navbar
$navLinks = [
    ['label' => 'Home', 'href' => 'index.php#home'],
    ['label' => 'Shop', 'href' => 'index.php#products'],
    ['label' => 'Blog', 'href' => '#'],
    ['label' => 'Contact Us', 'href' => 'contact.php'],
];

$hero = [
    'eyebrow' => 'New Collection',
    'title' => 'Clean style. Simple shopping.',
    'subtitle' => 'Updated from the assignment videos with a product listing page, single product page, clickable thumbnails, product detail URLs, and a cart page with totals using PHP.',
    'button_text' => 'Shop Now',
    'button_link' => '#products',
    'image' => 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=900&q=80',
    'image_alt' => 'Fashion collection hero image',
];

$products = [
    [
        'id' => 1,
        'name' => 'Classic White Sneakers',
        'price' => 165.00,
        'rating' => 5,
        'category' => 'Men\'s Fashion',
        'slug' => 'classic-white-sneakers',
        'short_description' => 'Clean everyday sneakers with a soft inner lining and lightweight sole.',
        'description' => 'A modern white sneaker designed for daily wear. It features a minimal look, breathable upper material, durable stitching, and a comfortable insole for long walks and casual styling.',
        'sizes' => ['39', '40', '41', '42', '43'],
        'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1608231387042-66d1773070a5?auto=format&fit=crop&w=900&q=80',
        ],
        'tag' => 'Best Seller',
    ],
    [
        'id' => 2,
        'name' => 'Mint Travel Backpack',
        'price' => 125.00,
        'rating' => 5,
        'category' => 'Accessories',
        'slug' => 'mint-travel-backpack',
        'short_description' => 'Compact backpack with a sleek silhouette and roomy storage.',
        'description' => 'This backpack is built for school, work, and short trips. The clean shape, front pocket, padded straps, and neat interior layout make it practical while keeping a stylish modern look.',
        'sizes' => ['Standard'],
        'image' => 'https://images.unsplash.com/photo-1581605405669-fcdf81165afa?auto=format&fit=crop&w=900&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1581605405669-fcdf81165afa?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1575844264771-892081089af5?auto=format&fit=crop&w=900&q=80',
        ],
        'tag' => 'New',
    ],
    [
        'id' => 3,
        'name' => 'Shadow Urban Backpack',
        'price' => 140.00,
        'rating' => 4,
        'category' => 'Accessories',
        'slug' => 'shadow-urban-backpack',
        'short_description' => 'Dark, modern backpack for everyday city use.',
        'description' => 'A versatile black backpack with a durable finish and spacious main compartment. Great for carrying daily essentials with a minimalist street-style appearance.',
        'sizes' => ['Standard'],
        'image' => 'https://images.unsplash.com/photo-1547949003-9792a18a2601?auto=format&fit=crop&w=900&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1547949003-9792a18a2601?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1523398002811-999ca8dec234?auto=format&fit=crop&w=900&q=80',
        ],
        'tag' => 'Popular',
    ],
    [
        'id' => 4,
        'name' => 'Sky Blue Daypack',
        'price' => 130.00,
        'rating' => 5,
        'category' => 'Accessories',
        'slug' => 'sky-blue-daypack',
        'short_description' => 'Soft blue daypack with a fresh sporty style.',
        'description' => 'Made for light daily carrying, this blue daypack balances comfort and style. It works well for students and casual travel with a clean front profile and practical storage.',
        'sizes' => ['Standard'],
        'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=900&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1496747611176-843222e1e57c?auto=format&fit=crop&w=900&q=80',
        ],
        'tag' => 'Hot',
    ],
];

$featuredLinks = [
    'MEN',
    'WOMEN',
    'BOYS',
    'GIRLS',
    'NEW ARRIVALS',
    'CLOTHES',
];

$instagramImages = [
    'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=400&q=80',
    'https://images.unsplash.com/photo-1581605405669-fcdf81165afa?auto=format&fit=crop&w=400&q=80',
    'https://images.unsplash.com/photo-1547949003-9792a18a2601?auto=format&fit=crop&w=400&q=80',
    'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=400&q=80',
    'https://images.unsplash.com/photo-1523398002811-999ca8dec234?auto=format&fit=crop&w=400&q=80',
    'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=400&q=80',
];
