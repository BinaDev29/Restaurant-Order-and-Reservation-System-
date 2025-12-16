<?php
require_once '../app/config.php';
require_once '../app/functions.php';
require_once '../app/controllers/DashboardController.php';

$controller = new DashboardController();
$stats = $controller->getStats();
$recent = $controller->getRecentActivity();
$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | <?php echo APP_NAME; ?></title>

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
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-end mb-5 text-white fade-in-up">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-light d-lg-none me-3" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div>
                    <h6 class="text-primary-gold text-uppercase letter-spacing-2 mb-1">Overview</h6>
                    <h2 class="display-5 fw-bold" style="font-family: 'Playfair Display', serif;">Dashboard</h2>
                </div>
            </div>
            <div class="dropdown text-end">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                    id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="bg-primary-gold rounded-circle d-flex align-items-center justify-content-center text-dark fw-bold me-2"
                        style="width: 45px; height: 45px;">
                        <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                    </div>
                    <div class="d-none d-md-block text-start">
                        <div class="fw-bold"><?php echo htmlspecialchars($user['full_name']); ?></div>
                        <small class="text-muted text-uppercase"
                            style="font-size: 0.7rem; letter-spacing: 1px;"><?php echo ucfirst($user['role']); ?></small>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark px-2 shadow-lg border-secondary"
                    aria-labelledby="dropdownUser1" style="min-width: 200px;">
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

        <!-- Stats Cards -->
        <div class="row g-4 mb-5 fade-in-up" style="animation-delay: 0.1s;">
            <?php if ($user['role'] === 'admin'): ?>
                <!-- Admin Stats -->
                <div class="col-md-3">
                    <div class="stat-card">
                        <div>
                            <h6 class="text-primary-gold">Total Revenue</h6>
                            <h3 class="text-white"><?php echo format_price($stats['revenue']); ?></h3>
                            <small class="text-muted" style="font-size: 0.75rem;">Lifetime Earnings</small>
                        </div>
                        <div class="stat-icon bg-success text-success bg-opacity-10">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div>
                            <h6 class="text-primary-gold">Pending Orders</h6>
                            <h3 class="text-white"><?php echo $stats['pending_orders']; ?></h3>
                            <small class="text-muted" style="font-size: 0.75rem;">Needs Action</small>
                        </div>
                        <div class="stat-icon bg-warning text-warning bg-opacity-10">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div>
                            <h6 class="text-primary-gold">Today's Visits</h6>
                            <h3 class="text-white"><?php echo $stats['reservations_today']; ?></h3>
                            <small class="text-muted" style="font-size: 0.75rem;">Confirmed Guests</small>
                        </div>
                        <div class="stat-icon bg-info text-info bg-opacity-10">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div>
                            <h6 class="text-primary-gold">Active Menu</h6>
                            <h3 class="text-white"><?php echo $stats['active_items']; ?></h3>
                            <small class="text-muted" style="font-size: 0.75rem;">Dishes Available</small>
                        </div>
                        <div class="stat-icon bg-primary text-primary bg-opacity-10">
                            <i class="fas fa-utensils"></i>
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif ($user['role'] === 'staff'): ?>
            <!-- Staff Stats -->
            <div class="col-md-4">
                <div class="stat-card">
                    <div>
                        <h6 class="text-primary-gold">Active Orders</h6>
                        <h3 class="text-white"><?php echo $stats['active_orders_count']; ?></h3>
                        <small class="text-muted" style="font-size: 0.75rem;">To Prepare/Serve</small>
                    </div>
                    <div class="stat-icon bg-warning text-warning bg-opacity-10">
                        <i class="fas fa-bell"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div>
                        <h6 class="text-primary-gold">Today's Reservations</h6>
                        <h3 class="text-white"><?php echo $stats['reservations_today']; ?></h3>
                        <small class="text-muted" style="font-size: 0.75rem;">Expected Guests</small>
                    </div>
                    <div class="stat-icon bg-info text-info bg-opacity-10">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div>
                        <h6 class="text-primary-gold">Occupied Tables</h6>
                        <h3 class="text-white"><?php echo $stats['occupied_tables']; ?></h3>
                        <small class="text-muted" style="font-size: 0.75rem;">Currently Seated</small>
                    </div>
                    <div class="stat-icon bg-success text-success bg-opacity-10">
                        <i class="fas fa-chair"></i>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Customer Stats -->
            <div class="col-md-4">
                <div class="stat-card">
                    <div>
                        <h6 class="text-primary-gold">Active Orders</h6>
                        <h3 class="text-white"><?php echo $stats['active_orders']; ?></h3>
                        <small class="text-muted" style="font-size: 0.75rem;">Being Prepare</small>
                    </div>
                    <div class="stat-icon bg-warning text-warning bg-opacity-10">
                        <i class="fas fa-receipt"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div>
                        <h6 class="text-primary-gold">Reservations</h6>
                        <h3 class="text-white"><?php echo $stats['upcoming_reservations']; ?></h3>
                        <small class="text-muted" style="font-size: 0.75rem;">Upcoming Visits</small>
                    </div>
                    <div class="stat-icon bg-info text-info bg-opacity-10">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div>
                        <h6 class="text-primary-gold">Total Spent</h6>
                        <h3 class="text-white"><?php echo format_price($stats['total_spent']); ?></h3>
                        <small class="text-muted" style="font-size: 0.75rem;">Thank you!</small>
                    </div>
                    <div class="stat-icon bg-success text-success bg-opacity-10">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Recent Activity Table -->
    <h5 class="text-white mb-4 fade-in-up" style="animation-delay: 0.2s;">Recent Activity</h5>
    <div class="table-card fade-in-up" style="animation-delay: 0.3s;">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Order ID</th>
                        <th>Date</th>
                        <?php if ($user['role'] === 'admin'): ?>
                            <th>Customer</th>
                        <?php endif; ?>
                        <th>Amount</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent as $order): ?>
                        <tr>
                            <td class="ps-4 fw-medium text-white-50">
                                #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span
                                        class="text-white"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></span>
                                    <small class="text-muted"
                                        style="font-size: 0.75rem;"><?php echo date('h:i A', strtotime($order['created_at'])); ?></small>
                                </div>
                            </td>
                            <?php if ($user['role'] === 'admin'): ?>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary-gold rounded-circle me-2 d-flex align-items-center justify-content-center text-dark fw-bold"
                                            style="width: 25px; height: 25px; font-size: 0.7rem;">
                                            <?php echo strtoupper(substr($order['full_name'], 0, 1)); ?>
                                        </div>
                                        <?php echo htmlspecialchars($order['full_name']); ?>
                                    </div>
                                </td>
                            <?php endif; ?>
                            <td class="fw-bold text-white"><?php echo format_price($order['total_amount']); ?></td>
                            <td>
                                <?php
                                $statusClass = match ($order['order_status']) {
                                    'pending' => 'bg-warning text-dark',
                                    'confirmed', 'preparing' => 'bg-info text-dark',
                                    'ready' => 'bg-primary text-white',
                                    'completed' => 'bg-success text-white',
                                    'cancelled' => 'bg-danger text-white',
                                    default => 'bg-secondary text-white'
                                };
                                ?>
                                <span class="badge rounded-pill <?php echo $statusClass; ?> px-3 py-2 fw-normal"
                                    style="font-size: 0.75rem;">
                                    <?php echo ucfirst($order['order_status']); ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="orders.php?id=<?php echo $order['id']; ?>"
                                    class="btn btn-sm btn-outline-light rounded-circle"
                                    style="width: 32px; height: 32px; padding: 0; line-height: 30px;">
                                    <i class="fas fa-chevron-right" style="font-size: 0.8rem;"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($recent)): ?>
                        <tr>
                            <td colspan="<?php echo ($user['role'] === 'admin') ? 6 : 5; ?>" class="text-center py-5">
                                <div class="text-muted opacity-50 mb-2"><i class="fas fa-inbox fa-3x"></i></div>
                                <p class="text-muted">No recent activity found.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('show');
        });
    </script>
</body>

</html>