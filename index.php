<?php
require_once 'app/config.php';
require_once 'app/functions.php';
require_once 'app/models/Menu.php';

$menuModel = new Menu($pdo);
$categories = $menuModel->getCategories();
$featured = $menuModel->getFeaturedItems();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> | Authentic Ethiopian Cuisine</title>
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
    <!-- Animate On Scroll -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
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
                    <li class="nav-item"><a class="nav-link text-white" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#about">Our Story</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#menu">Menu</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#experience">Experience</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#reservation">Reservation</a></li>
                    <li class="nav-item ms-2">
                        <button class="btn btn-outline-warning position-relative border-0 fs-5" id="open-cart">
                            <i class="fas fa-shopping-basket"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle"
                                id="cart-count"
                                style="background: var(--primary-color); color: #000; font-size: 0.7rem;">
                                0
                            </span>
                        </button>
                    </li>
                    <li class="nav-item ms-2">
                        <button class="btn btn-outline-light rounded-circle" id="theme-toggle" title="Toggle Theme">
                            <i class="fas fa-moon"></i>
                        </button>
                    </li>
                    <?php if (is_logged_in()): ?>
                        <li class="nav-item dropdown ms-lg-3">
                            <a class="nav-link dropdown-toggle btn btn-primary-gold px-3 text-dark" href="#" role="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> <?php echo $_SESSION['user_name']; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                                <li><a class="dropdown-item" href="dashboard/index.php">Dashboard</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item ms-lg-3">
                            <a class="btn btn-primary-gold" href="login.php">Login / Join</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="hero-content text-center" data-aos="zoom-in">
            <h4 class="text-warning mb-2 letter-spacing-2">SELAM & WELCOME</h4>
            <h1 class="display-3 mb-4 text-white">Taste the <span class="text-ethiopian">Spirit of Ethiopia</span></h1>
            <p class="lead mb-5 text-white-50">Authentic spices, communal dining, and the warmth of Ethiopian
                hospitality.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="#menu" class="btn btn-primary-gold btn-lg">View Menu</a>
                <a href="#reservation" class="btn btn-outline-light btn-lg">Book a Table</a>
            </div>
        </div>
    </section>

    <div class="pattern-divider"></div>

    <!-- About / Introduction -->
    <section id="about" class="section-padding menu-section-bg">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/Kitfo.jpg/800px-Kitfo.jpg"
                        class="img-fluid rounded-4 shadow-lg border border-secondary" alt="Ethiopian Food">
                </div>
                <div class="col-lg-6 ps-lg-5" data-aos="fade-left">
                    <h5 class="text-primary-gold text-uppercase ls-2">Our Heritage</h5>
                    <h2 class="display-5 mb-4 text-white">More Than Just Food, <br>It's a <span
                            class="border-ethiopian-bottom">Ceremony</span></h2>
                    <p class="text-muted mb-4 lead">At <?php echo APP_NAME; ?>, we bring the vibrant streets of Addis
                        Ababa to your table. We believe in the power of 'Gursha' — the act of feeding one another as a
                        sign of love and friendship.</p>
                    <div class="row g-4 mb-4">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-seedling text-ethiopian fa-2x me-3"></i>
                                <div>
                                    <h6 class="text-white mb-0">Organic Teff</h6>
                                    <small class="text-muted">Direct from farms</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-fire text-ethiopian fa-2x me-3"></i>
                                <div>
                                    <h6 class="text-white mb-0">Traditional Clay</h6>
                                    <small class="text-muted">Authentic cooking</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="#experience" class="text-primary-gold text-decoration-none fw-bold">Discover Our Culture <i
                            class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Section -->
    <section id="menu" class="section-padding menu-section-bg">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h5 class="text-primary-gold">CULINARY DELIGHTS</h5>
                <h2 class="display-5 text-white">Our Signature Dishes</h2>
                <div class="mx-auto mt-3" style="width: 60px; height: 3px; background: var(--primary-color);"></div>
            </div>

            <div class="row g-4">
                <?php if (empty($featured)): ?>
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-utensils fa-3x text-muted mb-3 opacity-50"></i>
                        <h4 class="text-white">Our Menu is Curating...</h4>
                        <p class="text-muted">Admin hasn't added any signature dishes yet.</p>
                        <?php if (is_logged_in() && ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'staff')): ?>
                            <a href="dashboard/menu.php" class="btn btn-primary-gold mt-2">Add Items Now</a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <?php foreach ($featured as $index => $item): ?>
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                            <div class="card food-card h-100 border-0 overflow-hidden">
                                <div class="position-relative overflow-hidden">
                                    <img src="<?php echo get_image_url($item['image']); ?>" class="card-img-top w-100"
                                        alt="<?php echo $item['name']; ?>">
                                    <div
                                        class="position-absolute top-0 end-0 m-3 badge bg-warning text-dark shadow-sm fw-bold rounded-pill">
                                        <i class="fas fa-star me-1"></i> 4.9
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title fw-bold text-white mb-0"><?php echo $item['name']; ?></h5>
                                        <span
                                            class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fs-6 item-price"><?php echo format_price($item['price']); ?></span>
                                    </div>
                                    <p class="card-text text-muted mb-4 flex-grow-1" style="line-height: 1.6;">
                                        <?php echo $item['description']; ?>
                                    </p>
                                    <button class="btn btn-primary-gold w-100 fw-bold shadow-sm rounded-pill add-to-cart-btn"
                                        data-id="<?php echo $item['id']; ?>">
                                        <i class="fas fa-cart-plus me-2"></i> Add to Order
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="text-center mt-5">
                <a href="menu.php" class="btn btn-outline-light btn-lg px-5">View Full Menu</a>
            </div>
        </div>
    </section>

    <!-- Cultural Experience Section -->
    <section id="experience" class="section-padding position-relative overflow-hidden">
        <!-- Background Overlay -->
        <div class="position-absolute top-0 start-0 w-100 h-100"
            style="background: url('https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80') center/cover; opacity: 0.15; z-index: -1;">
        </div>

        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto" data-aos="fade-up">
                    <h2 class="display-5 text-white mb-3">Authentic Experiences</h2>
                    <p class="text-muted lead">Dining with us is a journey through Ethiopian traditions.</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-4" data-aos="flip-left">
                    <div class="card h-100 text-center p-4 border-0 bg-transparent">
                        <div class="mb-4 text-center">
                            <span class="fa-stack fa-3x">
                                <i class="fas fa-circle fa-stack-2x text-dark"></i>
                                <i class="fas fa-coffee fa-stack-1x text-primary-gold"></i>
                            </span>
                        </div>
                        <h4 class="text-white">Coffee Ceremony</h4>
                        <p class="text-muted">Experience the legendary "Bunna" ceremony. We roast fresh beans at your
                            table, filling the air with aromatic incense.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="flip-up" data-aos-delay="100">
                    <div class="card h-100 text-center p-4 border-0 bg-transparent">
                        <div class="mb-4 text-center">
                            <span class="fa-stack fa-3x">
                                <i class="fas fa-circle fa-stack-2x text-dark"></i>
                                <i class="fas fa-hands fa-stack-1x text-ethiopian"></i>
                            </span>
                        </div>
                        <h4 class="text-white">Communal Dining</h4>
                        <p class="text-muted">Share a "Mesob" (woven basket table) with friends. Our platters are
                            designed for sharing, strengthening your bonds.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="flip-right" data-aos-delay="200">
                    <div class="card h-100 text-center p-4 border-0 bg-transparent">
                        <div class="mb-4 text-center">
                            <span class="fa-stack fa-3x">
                                <i class="fas fa-circle fa-stack-2x text-dark"></i>
                                <i class="fas fa-music fa-stack-1x text-danger"></i>
                            </span>
                        </div>
                        <h4 class="text-white">Live Masinko</h4>
                        <p class="text-muted">On weekends, enjoy live traditional music featuring the Masinko and Krar,
                            setting the perfect mood.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reservation Section -->
    <section id="reservation" class="section-padding menu-section-bg">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5" data-aos="fade-right">
                    <h5 class="text-primary-gold">RESERVATIONS</h5>
                    <h2 class="display-4 text-white mb-4">Book Your Table</h2>
                    <p class="text-muted mb-4">Whether for a romantic dinner, a family gathering, or a traditional
                        coffee ceremony, we have the perfect spot for you.</p>

                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-phone-alt text-primary-gold me-3 fa-2x"></i>
                        <div>
                            <h6 class="text-white mb-0">Call Us 24/7</h6>
                            <p class="text-muted mb-0">+251 911 234 567</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-map-marker-alt text-primary-gold me-3 fa-2x"></i>
                        <div>
                            <h6 class="text-white mb-0">Location</h6>
                            <p class="text-muted mb-0">Bole Atlas, Addis Ababa</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7" data-aos="fade-left">
                    <div class="card p-4">
                        <form action="app/api/reservations.php" method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">DATE</label>
                                    <div class="input-group">
                                        <span
                                            class="input-group-text bg-transparent border-secondary text-primary-gold"><i
                                                class="fas fa-calendar-alt"></i></span>
                                        <input type="date" name="date" class="form-control border-secondary"
                                            min="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">TIME</label>
                                    <div class="input-group">
                                        <span
                                            class="input-group-text bg-transparent border-secondary text-primary-gold"><i
                                                class="fas fa-clock"></i></span>
                                        <input type="time" name="time" class="form-control border-secondary" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">GUESTS</label>
                                    <div class="input-group">
                                        <span
                                            class="input-group-text bg-transparent border-secondary text-primary-gold"><i
                                                class="fas fa-users"></i></span>
                                        <select name="guests" class="form-select border-secondary">
                                            <option value="1">1 Person</option>
                                            <option value="2" selected>2 People</option>
                                            <option value="3">3 People</option>
                                            <option value="4">4 People</option>
                                            <option value="5">5 People</option>
                                            <option value="6">6 People</option>
                                            <option value="7">7 People</option>
                                            <option value="8">8 People</option>
                                            <option value="9">9 People</option>
                                            <option value="10">10+ People</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">EXPERIENCE TYPE</label>
                                    <div class="input-group">
                                        <span
                                            class="input-group-text bg-transparent border-secondary text-primary-gold"><i
                                                class="fas fa-glass-cheers"></i></span>
                                        <select name="type" class="form-select border-secondary">
                                            <option value="dinner">Standard Dinner</option>
                                            <option value="coffee">Coffee Ceremony</option>
                                            <option value="lunch">Lunch</option>
                                            <option value="celebration">Celebration/Event</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted small fw-bold">CONTACT PHONE</label>
                                    <div class="input-group">
                                        <span
                                            class="input-group-text bg-transparent border-secondary text-primary-gold"><i
                                                class="fas fa-phone"></i></span>
                                        <input type="tel" name="phone" class="form-control border-secondary"
                                            placeholder="+251..." required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted small fw-bold">SPECIAL REQUESTS
                                        (OPTIONAL)</label>
                                    <textarea name="requests" class="form-control border-secondary" rows="2"
                                        placeholder="Allergies, anniversaries, or specific table preferences..."></textarea>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit"
                                        class="btn btn-primary-gold w-100 py-3 text-uppercase letter-spacing-2 fw-bold shadow-lg">
                                        Confirm Reservation <i class="fas fa-paper-plane ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </section>

    <!-- Footer -->
    <footer class="text-center py-5 border-top border-secondary menu-section-bg">
        <div class="container">
            <h2 class="text-primary-gold mb-4" style="font-family: 'Playfair Display', serif;"><?php echo APP_NAME; ?>
            </h2>
            <div class="mb-4 social-icons">
                <a href="#" class="text-white mx-3 hover-gold"><i class="fab fa-facebook-f fa-lg"></i></a>
                <a href="#" class="text-white mx-3 hover-gold"><i class="fab fa-instagram fa-lg"></i></a>
                <a href="#" class="text-white mx-3 hover-gold"><i class="fab fa-twitter fa-lg"></i></a>
            </div>
            <p class="text-muted small mb-0">&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. Constructed by
                Ethco Coders.</p>
        </div>
    </footer>

    <!-- Cart Drawer Partial -->
    <?php include 'app/partials/cart_drawer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/theme.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
</body>

</html>