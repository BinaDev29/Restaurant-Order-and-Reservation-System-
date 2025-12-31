<?php
require_once 'app/config.php';
require_once 'app/functions.php';
require_once 'app/models/Menu.php';

$menuModel = new Menu($pdo);
$categories = $menuModel->getCategories();
$items = $menuModel->getAllItems();
// Group by category
$menuItems = [];
foreach ($items as $item) {
    $menuItems[$item['category_name']][] = $item;
}

$user = is_logged_in() ? current_user() : null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu | <?php echo APP_NAME; ?></title>
    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap"
        rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        :root {
            --primary-gold: #D4AF37;
            --light-gold: #FFD700;
            --dark-gold: #A67C00;
            --golden-gradient: linear-gradient(135deg, #FFD700 0%, #D4AF37 50%, #A67C00 100%);
            --dark-bg: #050505;
            --glass-bg: rgba(10, 10, 10, 0.9);
            --card-glass: rgba(255, 255, 255, 0.02);
            --border-rgba: rgba(212, 175, 55, 0.15);
        }

        body {
            background-color: var(--dark-bg);
            font-family: 'Outfit', sans-serif;
            color: #fff;
            background-image: radial-gradient(circle at top right, rgba(212, 175, 55, 0.1), transparent 500px);
            background-attachment: fixed;
        }

        .navbar {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-rgba);
            padding: 1.2rem 0;
            z-index: 1000;
        }

        .hero-mini {
            height: 40vh;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.9)), url('https://images.unsplash.com/photo-1542365857-3a3a532aad26?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-bottom: 4rem;
        }

        .category-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: var(--primary-gold);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .category-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, rgba(252, 221, 9, 0.3), transparent);
        }

        .food-card {
            background: var(--card-glass);
            border: 1px solid var(--border-rgba);
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            height: 100%;
            position: relative;
        }

        .food-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary-gold);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            background: rgba(255, 255, 255, 0.05);
        }

        .food-card img {
            height: 240px;
            width: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .food-card:hover img {
            transform: scale(1.1);
        }

        .item-price {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-gold);
        }

        .btn-add-cart {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-rgba);
            color: #fff;
            padding: 12px;
            border-radius: 14px;
            transition: 0.3s;
            font-weight: 600;
        }

        .btn-add-cart:hover {
            background: var(--primary-gold);
            color: #000;
            border-color: var(--primary-gold);
            transform: scale(1.02);
        }

        /* Cart Drawer */
        .cart-drawer {
            position: fixed;
            top: 0;
            right: -400px;
            width: 400px;
            height: 100vh;
            background: #0a0a0a;
            border-left: 1px solid var(--border-rgba);
            z-index: 1060;
            transition: right 0.4s cubic-bezier(0.77, 0, 0.175, 1);
            padding: 2rem;
            display: flex;
            flex-direction: column;
            box-shadow: -20px 0 50px rgba(0, 0, 0, 0.8);
        }

        .cart-drawer.open {
            right: 0;
        }

        .cart-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
            z-index: 1055;
            display: none;
        }

        .cart-item-ui {
            background: rgba(255, 255, 255, 0.02);
            border-radius: 16px;
            padding: 15px;
            display: flex;
            gap: 15px;
            margin-bottom: 12px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .cart-footer {
            margin-top: auto;
            border-top: 1px solid var(--border-rgba);
            padding-top: 2rem;
        }

        #cart-count {
            background: var(--primary-gold);
            color: #000;
            font-size: 0.75rem;
            font-weight: 800;
        }
    </style>
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand text-primary-gold fw-bold" href="index.php">
                <i class="fas fa-utensils me-2"></i> Golden Bar and Restaurant
            </a>

            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-warning position-relative border-0 fs-5" id="open-cart">
                    <i class="fas fa-shopping-basket"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle"
                        id="cart-count">0</span>
                </button>

                <?php if ($user): ?>
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                            data-bs-toggle="dropdown">
                            <div class="bg-primary-gold rounded-circle d-flex align-items-center justify-content-center text-dark fw-bold me-1"
                                style="width: 35px; height: 35px;">
                                <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end border-secondary p-2">
                            <li><a class="dropdown-item rounded-2" href="dashboard/index.php">Dashboard</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger rounded-2" href="logout.php">Sign Out</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary-gold rounded-pill px-4 btn-sm">Sign In</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero-mini">
        <div class="container">
            <h1 class="display-3 fw-bold animate__animated animate__fadeInDown text-white">Culinary Collection</h1>
            <p class="lead text-white-50 animate__animated animate__fadeInUp">Discover Authentic Flavors & Premium
                Dining</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container pb-5">
        <?php foreach ($menuItems as $category => $items): ?>
            <div class="mb-5">
                <h2 class="category-title text-white"><?php echo $category; ?></h2>
                <div class="row g-4">
                    <?php foreach ($items as $item): ?>
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="card food-card">
                                <img src="<?php echo get_image_url($item['image']); ?>" class="card-img-top"
                                    alt="<?php echo $item['name']; ?>">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-1 text-white"><?php echo $item['name']; ?></h5>
                                    <p class="text-white-50 small mb-4">
                                        <?php echo $item['description']; ?>
                                    </p>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="item-price"><?php echo format_price($item['price']); ?></span>
                                        <button class="btn btn-add-cart add-to-cart-btn" data-id="<?php echo $item['id']; ?>"
                                            data-name="<?php echo $item['name']; ?>" data-price="<?php echo $item['price']; ?>">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Footer / Cart Include -->
    <?php include 'app/partials/cart_drawer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>