<?php
require_once __DIR__ . '/../app/config.php';

try {
    // Categories
    $pdo->exec("INSERT INTO categories (name, description, image) VALUES 
        ('Chef Specials', 'Our signature dishes', 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/de/Doro_wat.jpg/800px-Doro_wat.jpg'),
        ('Vegan Delights', 'Fasting food (Tsome)', 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Beyaynetu.jpg/800px-Beyaynetu.jpg'),
        ('Traditional Entrees', 'Authentic meat dishes', 'https://upload.wikimedia.org/wikipedia/commons/2/2f/Kitfo.jpg'),
        ('Beverages', 'Tej and Coffee', 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/Coffee_ceremony_Ethiopia.jpg/800px-Coffee_ceremony_Ethiopia.jpg')
    ON DUPLICATE KEY UPDATE description=VALUES(description), image=VALUES(image)");

    // Get Category IDs
    $cats = [];
    $stmt = $pdo->query("SELECT id, name FROM categories");
    while ($row = $stmt->fetch()) {
        $cats[$row['name']] = $row['id'];
    }

    // Insert Items
    $items = [
        [
            'cat' => 'Chef Specials',
            'name' => 'Doro Wat',
            'desc' => 'The national dish. Spicy chicken stew simmered for hours with berbere, onions, and herbal butter, served with a hard-boiled egg.',
            'price' => 24.99,
            'img' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/de/Doro_wat.jpg/800px-Doro_wat.jpg'
        ],
        [
            'cat' => 'Traditional Entrees',
            'name' => 'Special Kitfo',
            'desc' => 'Minced raw beef marinated in mitmita and niter kibbeh. Served with ayib (cheese) and gomen (collard greens).',
            'price' => 22.50,
            'img' => 'https://upload.wikimedia.org/wikipedia/commons/2/2f/Kitfo.jpg'
        ],
        [
            'cat' => 'Traditional Entrees',
            'name' => 'Tibs Firfir',
            'desc' => 'Sautéed beef strips mixed with pieces of injera and spicy sauce. A hearty breakfast or lunch.',
            'price' => 18.00,
            'img' => 'https://pbs.twimg.com/media/FjS7XbYWQAEmjR_.jpg'
        ],
        [
            'cat' => 'Vegan Delights',
            'name' => 'Beyaynetu',
            'desc' => 'A colorful platter of various vegan stews (wats) including lentil, split pea, cabbage, and spinach served atop injera.',
            'price' => 19.99,
            'img' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Beyaynetu.jpg/800px-Beyaynetu.jpg'
        ],
        [
            'cat' => 'Chef Specials',
            'name' => 'Shekla Tibs',
            'desc' => 'Sizzling lamb cubes served in a traditional clay pot with rosemary and onions. Creates an amazing aroma.',
            'price' => 21.00,
            'img' => 'https://media-cdn.tripadvisor.com/media/photo-s/1a/bc/4a/12/shekla-tibs.jpg'
        ],
        [
            'cat' => 'Beverages',
            'name' => 'Honey Wine (Tej)',
            'desc' => 'Homemade fermented honey wine. Sweet but potent.',
            'price' => 8.00,
            'img' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/23/Tej.jpg/640px-Tej.jpg'
        ]
    ];

    $stmt = $pdo->prepare("INSERT INTO menu_items (category_id, name, description, price, image) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE description=VALUES(description), price=VALUES(price), image=VALUES(image)");

    foreach ($items as $item) {
        if (isset($cats[$item['cat']])) {
            $stmt->execute([
                $cats[$item['cat']],
                $item['name'],
                $item['desc'],
                $item['price'],
                $item['img']
            ]);
        }
    }

    echo "Seeding completed successfully with new Ethiopian items!";

} catch (Exception $e) {
    echo "Seeding error: " . $e->getMessage();
}
