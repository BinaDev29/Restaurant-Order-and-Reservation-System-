<?php
require_once 'app/config.php';
require_once 'app/controllers/AuthController.php';

$auth = new AuthController();
$auth->login();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | <?php echo APP_NAME; ?></title>
   
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap"
        rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        const currentTheme = localStorage.getItem('theme') || 'dark';
        if (currentTheme === 'light') {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    </script>

    <style>
        :root {
            --primary-gold: #FCDD09;
            --secondary-gold: #d4af37;
            --eth-green: #078930;
            --eth-red: #DA121A;
            --dark-bg: #080808;
            --glass-bg: rgba(15, 15, 15, 0.85);
            /* Slightly darker for better text contrast */
            --glass-border: 1px solid rgba(255, 255, 255, 0.2);
        }

        [data-theme="light"] {
            --dark-bg: #f5f5f5;
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: 1px solid rgba(0, 0, 0, 0.1);
        }

        [data-theme="light"] body::before {
            background: rgba(255, 255, 255, 0.3);
        }

        [data-theme="light"] .auth-form-side {
            background: rgba(0, 0, 0, 0.02);
        }

        [data-theme="light"] .welcome-header h1,
        [data-theme="light"] .welcome-header p,
        [data-theme="light"] .form-label,
        [data-theme="light"] .auth-footer,
        [data-theme="light"] .auth-footer a,
        [data-theme="light"] .back-btn,
        [data-theme="light"] .form-check-label,
        [data-theme="light"] .hover-gold,
        [data-theme="light"] .small {
            color: #1a1a1a !important;
            opacity: 1;
        }

        [data-theme="light"] .form-control-premium {
            background: #ffffff !important;
            border: 1px solid #ddd !important;
            color: #1a1a1a !important;
        }

        [data-theme="light"] .form-control-premium::placeholder {
            color: #888 !important;
        }

        [data-theme="light"] .input-wrapper i {
            color: var(--secondary-gold);
        }

        [data-theme="light"] .form-control-premium,
        [data-theme="light"] .form-control-premium:focus {
            color: #1a1a1a !important;
        }

        [data-theme="light"] .btn-outline-light {
            border-color: #ddd !important;
            color: #333 !important;
            background: #fff !important;
        }

        [data-theme="light"] .auth-visual,
        [data-theme="light"] .auth-visual * {
            color: #ffffff !important;
        }

        /* Forced White Text in Dark Mode */
        :root:not([data-theme="light"]) .welcome-header h1,
        :root:not([data-theme="light"]) .welcome-header p,
        :root:not([data-theme="light"]) .form-label,
        :root:not([data-theme="light"]) .auth-footer,
        :root:not([data-theme="light"]) .auth-footer a,
        :root:not([data-theme="light"]) .back-btn,
        :root:not([data-theme="light"]) .form-check-label,
        :root:not([data-theme="light"]) .text-white-80,
        :root:not([data-theme="light"]) .visual-footer,
        :root:not([data-theme="light"]) .form-control-premium,
        :root:not([data-theme="light"]) .form-control-premium:focus {
            color: #ffffff !important;
            opacity: 1;
        }

        :root:not([data-theme="light"]) .form-control-premium::placeholder {
            color: rgba(255, 255, 255, 0.7) !important;
        }

        body {
            background-color: var(--dark-bg);
            font-family: 'Outfit', sans-serif;
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            background-image:
                radial-gradient(circle at 10% 20%, rgba(252, 221, 9, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(212, 175, 55, 0.05) 0%, transparent 40%),
                url('https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.75);
            z-index: 0;
        }

        .login-wrapper {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 1000px;
            padding: 20px;
            animation: fadeInScale 0.8s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(20px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .auth-card {
            background: var(--glass-bg);
            backdrop-filter: blur(25px);
            border: var(--glass-border);
            border-radius: 30px;
            overflow: hidden;
            display: flex;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }

        .auth-visual {
            width: 45%;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.8)), url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            padding: 4rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .auth-form-side {
            width: 55%;
            padding: 4rem 3.5rem;
            background: rgba(0, 0, 0, 0.2);
        }

        .brand-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            color: var(--primary-gold);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
        }

        .quote-box h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            line-height: 1.2;
            margin-top: 2rem;
            font-style: italic;
        }

        .ethio-tag {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 15px;
            border-radius: 50px;
            font-size: 0.75rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .welcome-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            margin-bottom: 0.5rem;
        }

        .welcome-header p {
            color: #ffffff;
            margin-bottom: 2.5rem;
            font-size: 1.05rem;
            opacity: 0.9;
        }

        .form-label {
            font-size: 0.85rem;
            color: #ffffff;
            font-weight: 500;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.95;
        }

        .input-wrapper {
            position: relative;
            margin-bottom: 1.8rem;
        }

        .input-wrapper i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-gold);
            opacity: 0.6;
            font-size: 1.1rem;
        }

        .form-control-premium {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 16px !important;
            padding: 14px 20px 14px 55px !important;
            color: #fff !important;
            transition: all 0.3s ease !important;
        }

        .form-control-premium:focus {
            background: rgba(255, 255, 255, 0.08) !important;
            border-color: var(--primary-gold) !important;
            box-shadow: 0 0 15px rgba(252, 221, 9, 0.2) !important;
            outline: none;
        }

        .btn-auth-premium {
            background: linear-gradient(135deg, var(--primary-gold), var(--secondary-gold));
            color: #000;
            border: none;
            padding: 16px;
            border-radius: 16px;
            font-weight: 700;
            width: 100%;
            font-size: 1.1rem;
            box-shadow: 0 10px 20px rgba(252, 221, 9, 0.2);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .btn-auth-premium:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(252, 221, 9, 0.3);
            filter: brightness(1.1);
        }

        .auth-footer {
            text-align: center;
            margin-top: 1rem;
            color: #ffffff;
            opacity: 0.9;
        }

        .auth-footer a {
            color: var(--primary-gold);
            text-decoration: none;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .auth-visual {
                display: none;
            }

            .auth-form-side {
                width: 100%;
                padding: 3rem 2rem;
            }

            .login-wrapper {
                max-width: 500px;
            }
        }

        .back-btn {
            position: absolute;
            top: 40px;
            left: 40px;
            color: #fff;
            text-decoration: none;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            opacity: 0.7;
            transition: 0.3s;
            z-index: 10;
        }

        .back-btn:hover {
            opacity: 1;
            color: var(--primary-gold);
        }
    </style>
</head>

<body>
    <a href="index.php" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Home
    </a>

    <div class="login-wrapper">
        <div class="auth-card">
            <!-- Left Visual Side -->
            <div class="auth-visual">
                <a href="index.php" class="brand-logo">
                    <i class="fas fa-utensils"></i> Golden Bar and Restaurant
                </a>

                <div class="quote-box">
                    <span class="ethio-tag">
                        <span style="color:var(--eth-green)">•</span>
                        <span style="color:var(--primary-gold)">•</span>
                        <span style="color:var(--eth-red)">•</span>
                        Ethio-Luxury Experience
                    </span>
                    <h2>"Gastronomy is the art of using food to create happiness."</h2>
                    <p class="mt-3 text-white-80">— Chef de Cuisine</p>
                </div>

                <div class="visual-footer small opacity-75">
                    &copy; <?php echo date('Y'); ?> Golden Bar and Restaurant. Crafted with passion.
                </div>
            </div>

            <!-- Right Form Side -->
            <div class="auth-form-side">
                <div class="welcome-header">
                    <h1>Welcome Back</h1>
                    <p>Enter your credentials to access your dashboard.</p>
                </div>

                <?php if (isset($_SESSION['flash']['login_error'])): ?>
                    <div
                        class="alert alert-danger bg-danger bg-opacity-10 text-danger border-0 py-3 rounded-4 mb-4 small d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-3"></i>
                        <?php echo flash('login_error')['message']; ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label">Email Address</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" class="form-control form-control-premium"
                                placeholder="name@example.com" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label">Password</label>
                            <a href="#" class="small text-muted text-decoration-none hover-gold mb-2">Forgot
                                Password?</a>
                        </div>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" class="form-control form-control-premium"
                                placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="rememberMe">
                        <label class="form-check-label small text-muted" for="rememberMe">
                            Remember me for 30 days
                        </label>
                    </div>

                    <button type="submit" class="btn btn-auth-premium">Sign In to Account</button>

                    <button type="button"
                        class="btn btn-outline-light w-100 rounded-4 py-3 d-flex align-items-center justify-content-center gap-3 mb-4"
                        style="border-color: rgba(255,255,255,0.1); background: rgba(255,255,255,0.02);">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" width="18" alt="G">
                        Continue with Google
                    </button>
                </form>

                <div class="auth-footer text-muted small">
                    New to Golden Bar? <a href="register.php">Create an account</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>