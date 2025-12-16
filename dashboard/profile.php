<?php
require_once '../app/config.php';
require_once '../app/functions.php';
require_once '../app/controllers/UserController.php';

require_login();
$user = current_user();
$controller = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize_input($_POST['full_name']);
    $phone = sanitize_input($_POST['phone']);
    $password = !empty($_POST['password']) ? $_POST['password'] : null;

    if ($controller->updateProfile($user['id'], $name, $phone, $password)) {
        // Update session
        $_SESSION['user_name'] = $name;
        // Re-fetch user to make sure we have latest data
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user['id']]);
        $_SESSION['user'] = $stmt->fetch();

        flash('success', 'Profile updated successfully.');
    } else {
        flash('error', 'Failed to update profile.');
    }
    redirect('profile.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | <?php echo APP_NAME; ?></title>
    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>

<body>
    <?php include 'partials/sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-end mb-4 text-white fade-in-up">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-light d-lg-none me-3" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div>
                    <h6 class="text-primary-gold text-uppercase letter-spacing-2 mb-1">Account</h6>
                    <h2 class="display-5 fw-bold" style="font-family: 'Playfair Display', serif;">My Profile</h2>
                </div>
            </div>

            <div class="dropdown text-end ms-2">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                    id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="bg-primary-gold rounded-circle d-flex align-items-center justify-content-center text-dark fw-bold me-2"
                        style="width: 40px; height: 40px;">
                        <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark px-2 shadow-lg border-secondary"
                    aria-labelledby="dropdownUser1" style="min-width: 200px;">
                    <li>
                        <div class="dropdown-header text-white fw-bold">
                            <?php echo htmlspecialchars($user['full_name']); ?>
                        </div>
                    </li>
                    <li><a class="dropdown-item rounded-2 mb-1 active" href="profile.php"><i
                                class="fas fa-user-circle me-2 text-primary-gold"></i> Profile</a></li>
                    <li>
                        <hr class="dropdown-divider bg-secondary opacity-25">
                    </li>
                    <li><a class="dropdown-item rounded-2 text-danger" href="../logout.php"><i
                                class="fas fa-sign-out-alt me-2"></i> Sign out</a></li>
                </ul>
            </div>
        </div>

        <?php if (isset($_SESSION['flash']['success'])): ?>
            <div class="alert alert-success bg-success text-white border-0 bg-opacity-25 fade-in-up mb-4">
                <i class="fas fa-check-circle me-2"></i> <?php echo flash('success')['message']; ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['flash']['error'])): ?>
            <div class="alert alert-danger bg-danger text-white border-0 bg-opacity-25 fade-in-up mb-4">
                <i class="fas fa-check-circle me-2"></i> <?php echo flash('error')['message']; ?>
            </div>
        <?php endif; ?>

        <div class="row fade-in-up" style="animation-delay: 0.2s;">
            <div class="col-lg-8">
                <div class="table-card p-5">
                    <form method="POST">
                        <div class="row g-4">
                            <div class="col-12 d-flex align-items-center mb-4">
                                <div class="bg-primary-gold rounded-circle d-flex align-items-center justify-content-center fw-bold text-dark fs-1 me-4"
                                    style="width: 100px; height: 100px;">
                                    <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                                </div>
                                <div>
                                    <h4 class="text-white mb-1"><?php echo htmlspecialchars($user['full_name']); ?></h4>
                                    <p class="text-muted mb-0 text-uppercase letter-spacing-1">
                                        <?php echo $user['role']; ?>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted">Full Name</label>
                                <input type="text" name="full_name"
                                    class="form-control bg-dark border-secondary text-white p-3"
                                    value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted">Email Address</label>
                                <input type="email" class="form-control bg-dark border-secondary text-muted p-3"
                                    value="<?php echo htmlspecialchars($user['email']); ?>" disabled
                                    title="Email cannot be changed">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted">Phone Number</label>
                                <input type="tel" name="phone"
                                    class="form-control bg-dark border-secondary text-white p-3"
                                    value="<?php echo htmlspecialchars($user['phone']); ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted">New Password (Optional)</label>
                                <input type="password" name="password"
                                    class="form-control bg-dark border-secondary text-white p-3"
                                    placeholder="Leave blank to keep current">
                            </div>

                            <div class="col-12 mt-5">
                                <button type="submit"
                                    class="btn btn-primary-gold rounded-pill px-5 py-3 fw-bold shadow-sm">
                                    <i class="fas fa-save me-2"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="stat-card flex-column align-items-start p-4 mb-4">
                    <h5 class="text-white mb-3">Account Status</h5>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span class="text-white-50">Email Verified</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-shield-alt text-primary-gold me-2"></i>
                        <span class="text-white-50">Role: <?php echo ucfirst($user['role']); ?></span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock text-info me-2"></i>
                        <span class="text-white-50">Joined:
                            <?php echo date('M Y', strtotime($user['created_at'])); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('show');
        });
    </script>
</body>

</html>