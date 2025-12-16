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
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/Coffee_ceremony_Ethiopia.jpg/1280px-Coffee_ceremony_Ethiopia.jpg') no-repeat center center/cover;
            padding: 20px;
        }

        .auth-card {
            background-color: rgba(20, 20, 20, 0.95);
            /* Deeper, solid background for international feel */
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
            position: relative;
        }

        .lang-selector {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
            cursor: pointer;
            transition: color 0.3s;
        }

        .lang-selector:hover {
            color: var(--primary-color);
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="auth-card fade-in-up">
            <!-- Language Selector -->
            <div class="lang-selector dropdown">
                <a class="text-decoration-none text-light dropdown-toggle" href="#" role="button"
                    data-bs-toggle="dropdown">
                    <i class="fas fa-globe me-1"></i> EN
                </a>
                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end small shadow">
                    <li><a class="dropdown-item active" href="#">English (EN)</a></li>
                    <li><a class="dropdown-item" href="#">Amharic (AM)</a></li>
                    <li><a class="dropdown-item" href="#">Français (FR)</a></li>
                </ul>
            </div>

            <!-- Header -->
            <div class="text-center mb-5 mt-2">
                <a href="index.php" class="text-decoration-none">
                    <h2 class="text-primary-gold Playfair mb-2"><i
                            class="fas fa-utensils me-2"></i><?php echo APP_NAME; ?></h2>
                </a>
                <p class="text-white-50 small letter-spacing-1 text-uppercase">Member Login</p>
            </div>

            <!-- Social Login -->
            <div class="d-grid gap-3 mb-4">
                <button type="button"
                    class="btn btn-light d-flex align-items-center justify-content-center gap-3 py-2 fw-bold text-dark border-0 shadow-sm"
                    style="border-radius: 50px;">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" style="width:20px;">
                    <span>Continue with Google</span>
                </button>
                <button type="button"
                    class="btn btn-primary d-flex align-items-center justify-content-center gap-3 py-2 fw-bold border-0 shadow-sm"
                    style="background:#1877F2; border-radius: 50px;">
                    <i class="fab fa-facebook-f text-white fs-5"></i>
                    <span>Continue with Facebook</span>
                </button>
            </div>

            <div class="position-relative text-center mb-4">
                <hr class="border-secondary opacity-25">
                <span
                    class="position-absolute top-50 start-50 translate-middle px-3 text-white-50 small bg-dark">OR</span>
            </div>

            <?php if (isset($_SESSION['flash']['login_error'])): ?>
                <div class="alert alert-danger py-2 border-0 bg-danger bg-opacity-25 text-danger small mb-4">
                    <i class="fas fa-exclamation-circle me-1"></i> <?php echo flash('login_error')['message']; ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['flash']['register_success'])): ?>
                <div class="alert alert-success py-2 border-0 bg-success bg-opacity-25 text-success small mb-4">
                    <i class="fas fa-check-circle me-1"></i> <?php echo flash('register_success')['message']; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold ps-1">EMAIL ADDRESS</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-secondary text-primary-gold"><i
                                class="fas fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control border-secondary"
                            placeholder="name@example.com" required>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label text-muted small fw-bold ps-1 mb-0">PASSWORD</label>
                        <a href="#" class="text-primary-gold text-decoration-none small"
                            style="font-size: 0.75rem;">Forgot Password?</a>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-secondary text-primary-gold"><i
                                class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control border-secondary"
                            placeholder="Enter password" required>
                    </div>
                </div>

                <button type="submit"
                    class="btn btn-primary-gold w-100 py-3 rounded-pill fw-bold text-uppercase letter-spacing-1 shadow-lg mb-4">
                    Sign In
                </button>

                <div class="text-center">
                    <p class="text-muted small mb-0">New to <?php echo APP_NAME; ?>? <a href="register.php"
                            class="text-primary-gold text-decoration-none fw-bold">Create Account</a></p>
                </div>
            </form>

            <div class="text-center mt-4">
                <a href="index.php" class="text-white-50 text-decoration-none small hover-gold"><i
                        class="fas fa-arrow-left me-1"></i> Back to Homepage</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>