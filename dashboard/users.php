<?php
require_once '../app/config.php';
require_once '../app/functions.php';
require_once '../app/controllers/UserController.php';

require_login();
$currentUser = current_user();

if ($currentUser['role'] !== 'admin') {
    redirect('index.php');
}

$controller = new UserController();
$search = sanitize_input($_GET['search'] ?? '');
$users = $controller->index($search);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users | <?php echo APP_NAME; ?></title>
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
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-end mb-4 text-white fade-in-up gap-3">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-light d-lg-none me-3" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div>
                    <h6 class="text-primary-gold text-uppercase letter-spacing-2 mb-1">System</h6>
                    <h2 class="display-5 fw-bold" style="font-family: 'Playfair Display', serif;">Users</h2>
                </div>
            </div>

            <div class="d-flex gap-3 align-items-center w-100 w-md-auto">
                <form action="" method="GET" class="flex-grow-1">
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-muted"><i
                                class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control bg-dark border-secondary text-white"
                            placeholder="Search users..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                </form>
                <div class="text-muted text-nowrap align-self-center">Total: <?php echo count($users); ?></div>

                <div class="dropdown text-end ms-2">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                        id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="bg-primary-gold rounded-circle d-flex align-items-center justify-content-center text-dark fw-bold me-2"
                            style="width: 40px; height: 40px;">
                            <?php echo strtoupper(substr($currentUser['full_name'], 0, 1)); ?>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark px-2 shadow-lg border-secondary"
                        aria-labelledby="dropdownUser1" style="min-width: 200px;">
                        <li>
                            <div class="dropdown-header text-white fw-bold">
                                <?php echo htmlspecialchars($currentUser['full_name']); ?>
                            </div>
                        </li>
                        <li><a class="dropdown-item rounded-2 mb-1" href="profile.php"><i
                                    class="fas fa-user-circle me-2 text-primary-gold"></i> Profile</a></li>
                        <li>
                            <hr class="dropdown-divider bg-secondary opacity-25">
                        </li>
                        <li><a class="dropdown-item rounded-2 text-danger" href="../logout.php"><i
                                    class="fas fa-sign-out-alt me-2"></i> Sign out</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="table-card fade-in-up" style="animation-delay: 0.2s;">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4">User</th>
                            <th>Role</th>
                            <th>Contact</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center text-white fw-bold"
                                            style="width: 40px; height: 40px;">
                                            <?php echo strtoupper(substr($u['full_name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="text-white fw-medium">
                                                <?php echo htmlspecialchars($u['full_name']); ?>
                                            </div>
                                            <div class="small text-muted"><?php echo htmlspecialchars($u['email']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($u['role'] === 'admin'): ?>
                                        <span class="badge bg-primary-gold text-dark rounded-pill px-3">Admin</span>
                                    <?php else: ?>
                                        <span
                                            class="badge bg-secondary bg-opacity-50 text-white rounded-pill px-3">Customer</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-white-50">
                                    <?php echo $u['phone'] ? htmlspecialchars($u['phone']) : '<span class="text-muted opacity-50">-</span>'; ?>
                                </td>
                                <td class="text-white-50">
                                    <?php echo date('M d, Y', strtotime($u['created_at'])); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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