<?php
require_once 'app/config.php';
require_once 'app/controllers/AuthController.php';

$auth = new AuthController();
$auth->login(); // Handles POST request
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | <?php echo APP_NAME; ?></title>
    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            overflow-x: hidden;
            background-color: var(--dark-bg);
            font-family: 'Outfit', sans-serif;
        }

        .split-layout {
            min-height: 100vh;
        }

        .image-side {
            background: url('https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/Coffee_ceremony_Ethiopia.jpg/1280px-Coffee_ceremony_Ethiopia.jpg') no-repeat center center/cover;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 4rem;
        }

        /* Dark overlay for image to ensure text readability if needed, though we use a card now */
        .image-side::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
            z-index: 1;
        }

        .quote-card {
            background: rgba(20, 20, 20, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 16px;
            position: relative;
            z-index: 2;
            max-width: 600px;
            border-left: 4px solid var(--primary-color);
        }

        .form-side {
            background-color: var(--dark-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }

        [data-theme="light"] .form-side {
            background-color: #ffffff;
        }

        .form-container {
            width: 100%;
            max-width: 420px;
        }

        /* Language Selector - Top Right */
        .lang-selector {
            position: absolute;
            top: 30px;
            right: 40px;
            z-index: 10;
        }

        .lang-selector .dropdown-toggle {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .lang-selector .dropdown-toggle:hover {
            color: var(--primary-color);
        }

        [data-theme="light"] .lang-selector .dropdown-toggle {
            color: #555;
        }

        /* Password Toggle */
        .password-toggle {
            cursor: pointer;
            z-index: 10;
        }

        /* Floating Label overrides */
        .form-floating>.form-control {
            background-color: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: var(--text-color) !important;
        }

        .form-floating>.form-control:focus {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 1px var(--primary-color);
        }

        .form-floating>label {
            color: rgba(255, 255, 255, 0.5);
        }

        [data-theme="light"] .form-floating>.form-control {
            background-color: #f8f9fa !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
            color: #000 !important;
        }

        [data-theme="light"] .form-floating>label {
            color: rgba(0, 0, 0, 0.5);
        }

        .btn-social {
            transition: all 0.2s ease;
        }

        .btn-social:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .back-link {
            position: absolute;
            top: 30px;
            left: 40px;
            z-index: 100;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0 split-layout">
        <div class="row g-0 h-100 min-vh-100">
            <!-- Left Side: Image with Quote -->
            <div class="col-lg-6 d-none d-lg-flex image-side">
                <div class="quote-card fade-in-up">
                    <h3 class="text-white mb-3" style="font-family: 'Playfair Display', serif; font-style: italic;">
                        "Dining is not just about eating; it's about the experience, the culture, and the joy of
                        sharing."</h3>
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 40px; height: 1px; background: var(--primary-color);"></div>
                        <span class="text-uppercase small letter-spacing-1 text-primary-gold fw-bold">Experience
                            Ethio-Luxury</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="col-lg-6 form-side">
                <!-- Back Link -->
                <a href="index.php" class="back-link text-decoration-none text-muted small hover-gold">
                    <i class="fas fa-arrow-left me-2"></i>Back to Home
                </a>

                <!-- Language Selector -->
                <div class="lang-selector dropdown">
                    <a class="text-decoration-none dropdown-toggle d-flex align-items-center gap-2" href="#"
                        role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-globe"></i> English (US)
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end small shadow border-0"
                        style="background: var(--card-bg); backdrop-filter: blur(10px);">
                        <li><a class="dropdown-item text-white-50 hover-gold" href="#">🇺🇸 English</a></li>
                        <li><a class="dropdown-item text-white-50 hover-gold" href="#">🇪🇹 Amharic</a></li>
                        <li><a class="dropdown-item text-white-50 hover-gold" href="#">🇫🇷 French</a></li>
                    </ul>
                </div>

                <div class="form-container fade-in-up">
                    <div class="mb-5">
                        <h2 class="fw-bold mb-2 display-6" style="font-family: 'Playfair Display', serif;">Welcome Back
                        </h2>
                        <p class="text-muted">Please enter your details to sign in.</p>
                    </div>

                    <!-- Social Login -->
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <button type="button"
                                class="btn btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2 py-2 btn-social"
                                style="border-color: rgba(255,255,255,0.15);">
                                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google"
                                    style="width:20px;">
                                <span class="fw-medium">Log in with Google</span>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-4">
                        <hr class="flex-grow-1 border-secondary opacity-10 m-0">
                        <span class="px-3 text-muted small text-uppercase" style="font-size: 0.75rem;">Or</span>
                        <hr class="flex-grow-1 border-secondary opacity-10 m-0">
                    </div>

                    <!-- Alerts -->
                    <?php if (isset($_SESSION['flash']['login_error'])): ?>
                        <div
                            class="alert alert-danger py-2 border-0 bg-danger bg-opacity-10 text-danger small mb-4 rounded-2">
                            <i class="fas fa-exclamation-circle me-1"></i> <?php echo flash('login_error')['message']; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['flash']['register_success'])): ?>
                        <div
                            class="alert alert-success py-2 border-0 bg-success bg-opacity-10 text-success small mb-4 rounded-2">
                            <i class="fas fa-check-circle me-1"></i> <?php echo flash('register_success')['message']; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="email" placeholder=" " required>
                            <label for="email">Email Address</label>
                        </div>

                        <div class="position-relative mb-3">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder=" " required>
                                <label for="password">Password</label>
                            </div>
                            <span
                                class="password-toggle position-absolute top-50 end-0 translate-middle-y me-3 text-muted"
                                onclick="togglePassword()">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input bg-transparent border-secondary" type="checkbox"
                                    id="remember">
                                <label class="form-check-label small text-muted" for="remember">Remember me</label>
                            </div>
                            <a href="#" class="text-primary-gold text-decoration-none small hover-gold">Forgot
                                Password?</a>
                        </div>

                        <button type="submit" class="btn btn-primary-gold w-100 py-3 rounded-3 fw-bold shadow-lg">
                            Log In
                        </button>
                    </form>

                    <div class="text-center mt-5">
                        <p class="text-muted small">Don't have an account? <a href="register.php"
                                class="text-primary-gold text-decoration-none fw-bold hover-gold">Sign up for free</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Theme Script -->
    <script>
        // Password Toggle
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.querySelector('.password-toggle i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>