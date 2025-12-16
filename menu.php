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
    echo "<!-- Debug: Item " . $item['name'] . " Cat: " . $item['category_name'] . " -->";
    $menuItems[$item['category_name']][] = $item;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Full Menu | <?php echo APP_NAME; ?></title>
    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="100">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand text-primary-gold" href="index.php">
                <i class="fas fa-utensils me-2"></i><?php echo APP_NAME; ?>
            </a>
            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link text-white" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-white active" href="menu.php">Menu</a></li>

                    <li class="nav-item ms-2">
                        <button class="btn btn-outline-warning position-relative" id="checkout-btn">
                            <i class="fas fa-shopping-cart"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                id="cart-count">
                                0
                            </span>
                        </button>
                    </li>
                    <li class="nav-item ms-2">
                        <button class="btn btn-outline-light rounded-circle" id="theme-toggle" title="Toggle Theme">
                            <i class="fas fa-moon"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <section class="hero-section" style="height: 50vh;">
        <div class="hero-content text-center">
            <h1 class="display-3 text-primary-gold">Full Menu</h1>
            <p class="lead text-white">Explore our complete culinary collection</p>
        </div>
    </section>

    <!-- Menu Section -->
    <section class="section-padding menu-section-bg">
        <div class="container">
            <?php if (empty($menuItems)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-utensils fa-3x text-muted mb-3 opacity-50"></i>
                    <h4 class="text-white">Menu Unavailable</h4>
                    <p class="text-muted">No items found. Please check back later.</p>
                </div>
            <?php else: ?>
                <?php foreach ($menuItems as $category => $categoryItems): ?>
                    <div class="mb-5">
                        <h3
                            class="text-primary-gold border-bottom border-warning border-opacity-25 pb-2 mb-4 d-inline-block pe-5 Playfair">
                            <?php echo $category; ?></h3>
                        <div class="row g-4">
                            <?php foreach ($categoryItems as $item): ?>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card food-card h-100 border-0 overflow-hidden shadow-lg">
                                        <div class="position-relative overflow-hidden">
                                            <img src="<?php echo get_image_url($item['image']); ?>" class="card-img-top w-100"
                                                alt="<?php echo $item['name']; ?>">
                                            <div
                                                class="position-absolute top-0 end-0 m-3 badge bg-warning text-dark shadow-sm fw-bold rounded-pill">
                                                <i class="fas fa-certificate me-1"></i> Authentic
                                            </div>
                                        </div>
                                        <div class="card-body d-flex flex-column p-4">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h5 class="card-title fw-bold text-white mb-0"><?php echo $item['name']; ?></h5>
                                                <span
                                                    class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fs-6 item-price"><?php echo format_price($item['price']); ?></span>
                                            </div>
                                            <p class="card-text text-muted mb-4 flex-grow-1 small" style="line-height: 1.6;">
                                                <?php echo $item['description']; ?></p>
                                            <button class="btn btn-outline-warning w-100 fw-bold rounded-pill add-to-cart-btn"
                                                data-id="<?php echo $item['id']; ?>">
                                                <i class="fas fa-cart-plus me-2"></i> Add to Order
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <footer class="text-center py-5 border-top border-secondary menu-section-bg">
        <div class="container">
            <h2 class="text-primary-gold mb-4" style="font-family: 'Playfair Display', serif;"><?php echo APP_NAME; ?>
            </h2>
            <p class="text-muted small mb-0">&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. Constructed by
                Ethco Coders.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>