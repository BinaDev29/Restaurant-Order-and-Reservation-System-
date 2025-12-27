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
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-yellow: #FCDD09;
            --darkER-bg: #0d0d0d;
            --input-bg: #151515;
            --border-rgba: rgba(255, 255, 255, 0.08);
        }

        body {
            overflow-x: hidden;
            background-color: var(--darkER-bg);
            font-family: 'Outfit', sans-serif;
            color: #fff;
        }

        .split-layout {
            min-height: 100vh;
        }

        .image-side {
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('https://images.unsplash.com/photo-1543353071-873f17a7a088?q=80&w=2070&auto=format&fit=crop') no-repeat center center/cover;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 5rem 4rem;
        }

        .quote-container {
            max-width: 550px;
            z-index: 2;
        }

        .quote-card {
            background: rgba(15, 15, 15, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-rgba);
            padding: 2.5rem;
            border-radius: 20px;
            position: relative;
            border-left: 5px solid var(--primary-yellow);
        }

        .quote-text {
            font-family: 'Playfair Display', serif;
            font-size: 2.1rem;
            line-height: 1.3;
            margin-bottom: 1.5rem;
            font-style: italic;
        }

        .quote-sub {
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 700;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .quote-sub::before {
            content: '';
            width: 40px;
            height: 2px;
            background: var(--primary-yellow);
        }

        .form-side {
            background-color: var(--darkER-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            position: relative;
        }

        .form-container {
            width: 100%;
            max-width: 500px;
        }

        /* Nav Elements */
        .back-nav {
            position: absolute;
            top: 40px;
            left: 50px;
        }

        .lang-nav {
            position: absolute;
            top: 40px;
            right: 50px;
        }

        .nav-link {
            color: #fff;
            opacity: 0.7;
            text-decoration: none;
            font-size: 0.85rem;
            transition: 0.3s;
        }

        .nav-link:hover {
            opacity: 1;
            color: var(--primary-yellow);
        }

        /* Heading */
        .welcome-title {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }

        .welcome-sub {
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 2.5rem;
        }

        /* Inputs */
        .input-group-custom {
            margin-bottom: 1.2rem;
        }

        .input-label {
            display: block;
            margin-bottom: 0.4rem;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
        }

        .custom-input,
        .custom-select {
            background: var(--input-bg) !important;
            border: 1px solid var(--border-rgba) !important;
            border-radius: 12px !important;
            padding: 0.9rem 1.1rem !important;
            color: #fff !important;
            width: 100%;
            transition: 0.3s;
        }

        .custom-input:focus,
        .custom-select:focus {
            border-color: var(--primary-yellow) !important;
            box-shadow: 0 0 0 2px rgba(252, 221, 9, 0.1) !important;
            outline: none;
        }

        /* Google Btn */
        .btn-google {
            background: transparent;
            border: 1px solid var(--border-rgba);
            color: #fff;
            padding: 0.8rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            font-weight: 500;
            margin-bottom: 2rem;
            width: 100%;
            transition: 0.3s;
        }

        .btn-google:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .or-divider {
            display: flex;
            align-items: center;
            gap: 15px;
            color: rgba(255, 255, 255, 0.2);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2rem;
        }

        .or-divider::before,
        .or-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.05);
        }

        /* Primary Btn */
        .btn-register {
            background: var(--primary-yellow);
            color: #000;
            border: none;
            padding: 1rem;
            border-radius: 12px;
            font-weight: 700;
            width: 100%;
            margin-top: 1rem;
            transition: 0.3s;
            box-shadow: 0 10px 20px rgba(252, 221, 9, 0.1);
        }

        .btn-register:hover {
            transform: translateY(-1px);
            box-shadow: 0 15px 25px rgba(252, 221, 9, 0.2);
            background: #fde541;
        }

        .login-text {
            margin-top: 2rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
        }

        .login-text a {
            color: var(--primary-yellow);
            text-decoration: none;
            font-weight: 600;
        }

        /* Password Wrapper */
        .pass-wrapper {
            position: relative;
        }

        .toggle-btn {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.4);
            cursor: pointer;
            font-size: 1rem;
        }

        .fade-in {
            animation: fadeIn 0.8s ease forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        select option {
            background: #000;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0 split-layout">
        <div class="row g-0 h-100 min-vh-100">
            <!-- Left Side: Visual -->
            <div class="col-lg-6 d-none d-lg-flex image-side">
                <div class="quote-container fade-in">
                    <div class="quote-card">
                        <div class="quote-text">
                            "Every dish tells a story of tradition, passion, and the warmth of Ethiopian hospitality."
                        </div>
                        <div class="quote-sub">
                            Authentic Experience
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Interaction -->
            <div class="col-lg-6 form-side">
                <!-- Navigation -->
                <div class="back-nav">
                    <a href="index.php" class="nav-link">
                        <i class="fas fa-arrow-left me-2"></i> Back to Home
                    </a>
                </div>

                <div class="form-container fade-in">
                    <div class="mb-4">
                        <h1 class="welcome-title">Join Us</h1>
                        <p class="welcome-sub">Create an account to start your culinary journey.</p>
                    </div>

                    <button class="btn btn-google">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" width="20" alt="G">
                        Sign up with Google
                    </button>

                    <div class="or-divider">Or use email</div>

                    <!-- Alerts -->
                    <?php if (isset($_SESSION['flash']['register_error'])): ?>
                        <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border-0 py-2 small mb-4">
                            <?php echo flash('register_error')['message']; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="input-group-custom">
                            <label class="input-label">Full Name</label>
                            <input type="text" name="full_name" class="custom-input" placeholder="Enter your full name"
                                required>
                        </div>

                        <div class="input-group-custom">
                            <label class="input-label">Email Address</label>
                            <input type="email" name="email" class="custom-input" placeholder="Enter your email"
                                required>
                        </div>

                        <div class="row g-3">
                            <div class="col-4">
                                <div class="input-group-custom">
                                    <label class="input-label">Code</label>
                                    <select class="custom-select" id="countryCode">
                                        <option value="+251">🇪🇹 +251</option>
                                        <option value="+1">🇺🇸 +1</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="input-group-custom">
                                    <label class="input-label">Phone Number</label>
                                    <input type="tel" name="phone" class="custom-input" placeholder="912..." required>
                                </div>
                            </div>
                        </div>

                        <div class="input-group-custom">
                            <label class="input-label">Create Password</label>
                            <div class="pass-wrapper">
                                <input type="password" id="password" name="password" class="custom-input"
                                    placeholder="Create a strong password" required>
                                <span class="toggle-btn" onclick="togglePass()">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-register">Create Account</button>
                    </form>

                    <p class="login-text">
                        Already have an account? <a href="login.php">Log in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePass() {
            const p = document.getElementById('password');
            const i = document.querySelector('.toggle-btn i');
            if (p.type === 'password') {
                p.type = 'text';
                i.className = 'fas fa-eye-slash';
            } else {
                p.type = 'password';
                i.className = 'fas fa-eye';
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>