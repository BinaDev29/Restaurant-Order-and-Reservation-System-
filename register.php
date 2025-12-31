<?php
require_once 'app/config.php';
require_once 'app/controllers/AuthController.php';

$auth = new AuthController();
$auth->register();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | <?php echo APP_NAME; ?></title>
    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap"
        rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-gold: #FCDD09;
            --secondary-gold: #d4af37;
            --eth-green: #078930;
            --eth-red: #DA121A;
            --dark-bg: #080808;
            --glass-bg: rgba(15, 15, 15, 0.7);
            --glass-border: 1px solid rgba(255, 255, 255, 0.1);
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
                url('https://images.unsplash.com/photo-1543353071-873f17a7a088?q=80&w=2070&auto=format&fit=crop');
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
            z-index: 1;
        }

        .auth-wrapper {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 1100px;
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
            width: 40%;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.9)), url('https://images.unsplash.com/photo-1543353071-873f17a7a088?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            padding: 4rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .auth-form-side {
            width: 60%;
            padding: 4rem 4rem;
            background: rgba(0, 0, 0, 0.2);
        }

        .welcome-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .welcome-header p {
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 2.5rem;
        }

        .form-label {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
            letter-spacing: 1px;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-wrapper i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-gold);
            opacity: 0.6;
        }

        .form-control-premium {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 16px !important;
            padding: 12px 20px 12px 50px !important;
            color: #fff !important;
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
            font-size: 1rem;
            box-shadow: 0 10px 20px rgba(252, 221, 9, 0.2);
            transition: all 0.3s ease;
            margin-top: 1.1rem;
        }

        .btn-auth-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(252, 221, 9, 0.3);
        }

        .back-btn {
            position: absolute;
            top: 40px;
            left: 40px;
            color: #fff;
            text-decoration: none;
            font-size: 0.9rem;
            z-index: 10;
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
        }

        @media (max-width: 992px) {
            .auth-visual {
                display: none;
            }

            .auth-form-side {
                width: 100%;
                padding: 3rem 2rem;
            }

            .auth-wrapper {
                max-width: 500px;
            }
        }
    </style>
</head>

<body>
    <a href="index.php" class="back-btn">
        <i class="fas fa-arrow-left me-2"></i> Back to Home
    </a>

    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-visual">
                <div class="brand-logo h3 fw-bold"
                    style="font-family: 'Playfair Display', serif; color: var(--primary-gold);">
                    <i class="fas fa-utensils me-2"></i> LUMINA
                </div>

                <div class="quote-box">
                    <span class="ethio-tag small mb-3">Ancient Flavors, Modern Luxury</span>
                    <h2 class="display-6 italic" style="font-family: 'Playfair Display', serif; font-style: italic;">
                        "Food is the most primitive form of comfort."</h2>
                </div>

                <div class="visual-footer small opacity-50">
                    &copy; <?php echo date('Y'); ?> Lumina Dining.
                </div>
            </div>

            <div class="auth-form-side">
                <div class="welcome-header">
                    <h1>Create Account</h1>
                    <p>Start your exclusive culinary journey today.</p>
                </div>

                <?php if (isset($_SESSION['flash']['register_error'])): ?>
                    <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border-0 py-3 rounded-4 mb-4 small">
                        <i class="fas fa-info-circle me-2"></i> <?php echo flash('register_error')['message']; ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Full Name</label>
                            <div class="input-wrapper">
                                <i class="fas fa-user"></i>
                                <input type="text" name="full_name" class="form-control form-control-premium"
                                    placeholder="John Doe" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <div class="input-wrapper">
                                <i class="fas fa-envelope"></i>
                                <input type="email" name="email" class="form-control form-control-premium"
                                    placeholder="name@email.com" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <div class="input-wrapper">
                                <i class="fas fa-phone"></i>
                                <input type="tel" name="phone" class="form-control form-control-premium"
                                    placeholder="+251..." required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Create Password</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="password" class="form-control form-control-premium"
                                    placeholder="min. 8 characters" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-auth-premium">Create Exclusive Account</button>

                    <div class="text-center mt-4">
                        <span class="text-muted small">Already a member? <a href="login.php"
                                style="color:var(--primary-gold); text-decoration:none; font-weight:600;">Sign
                                In</a></span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>