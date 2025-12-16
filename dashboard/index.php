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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>

<body>

    <?php include 'partials/sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4 text-white">
            <h2>Dashboard</h2>
            <div class="text-muted">Welcome back, <?php echo $user['full_name']; ?></div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-5">
            <?php if ($user['role'] === 'admin'): ?>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div>
                            <h6 class="text-muted mb-1">Total Revenue</h6>
                            <h3 class="text-white mb-0"><?php echo format_price($stats['revenue']); ?></h3>
                        </div>
                        <div class="stat-icon bg-success text-white bg-opacity-25">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div>
                            <h6 class="text-muted mb-1">Pending Orders</h6>
                            <h3 class="text-white mb-0"><?php echo $stats['pending_orders']; ?></h3>
                        </div>
                        <div class="stat-icon bg-warning text-white bg-opacity-25">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div>
                            <h6 class="text-muted mb-1">Today's Visits</h6>
                            <h3 class="text-white mb-0"><?php echo $stats['reservations_today']; ?></h3>
                        </div>
                        <div class="stat-icon bg-info text-white bg-opacity-25">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div>
                            <h6 class="text-muted mb-1">Active Menu</h6>
                            <h3 class="text-white mb-0"><?php echo $stats['active_items']; ?> Items</h3>
                        </div>
                        <div class="stat-icon bg-primary text-white bg-opacity-25">
                            <i class="fas fa-utensils"></i>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div>
                            <h6 class="text-muted mb-1">Active Orders</h6>
                            <h3 class="text-white mb-0"><?php echo $stats['active_orders']; ?></h3>
                        </div>
                        <div class="stat-icon bg-warning text-white bg-opacity-25">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div>
                            <h6 class="text-muted mb-1">Upcoming Reservations</h6>
                            <h3 class="text-white mb-0"><?php echo $stats['upcoming_reservations']; ?></h3>
                        </div>
                        <div class="stat-icon bg-info text-white bg-opacity-25">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div>
                            <h6 class="text-muted mb-1">Total Spent</h6>
                            <h3 class="text-white mb-0"><?php echo format_price($stats['total_spent']); ?></h3>
                        </div>
                        <div class="stat-icon bg-success text-white bg-opacity-25">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Activity -->
        <h4 class="text-white mb-3">Recent Activity</h4>
        <div class="card bg-dark border-secondary">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <?php if ($user['role'] === 'admin'): ?>
                                    <th>User</th><?php endif; ?>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo date('M d, H:i', strtotime($order['created_at'])); ?></td>
                                    <?php if ($user['role'] === 'admin'): ?>
                                        <td><?php echo htmlspecialchars($order['full_name']); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo format_price($order['total_amount']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php
                                        echo match ($order['order_status']) {
                                            'pending' => 'warning',
                                            'confirmed', 'preparing' => 'info',
                                            'ready' => 'primary',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                            default => 'secondary'
                                        };
                                        ?>">
                                            <?php echo ucfirst($order['order_status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="orders.php?id=<?php echo $order['id']; ?>"
                                            class="btn btn-sm btn-outline-light"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($recent)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No recent activity found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>