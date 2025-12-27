<?php
require_once '../app/config.php';
require_once '../app/functions.php';
require_once '../app/controllers/OrderController.php';

$controller = new OrderController();
$user = current_user();

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    if ($user['role'] === 'admin') {
        $controller->updateStatus($_POST['order_id'], $_POST['status']);
        flash('order_msg', 'Order status updated.');
        redirect('dashboard/orders.php');
    }
}

$orders = $controller->index();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders | <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>

<body>

    <?php include 'partials/sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4 text-white">
            <div class="d-flex align-items-center">
                 <button class="btn btn-outline-light d-lg-none me-3" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h2 class="mb-0">Order Management</h2>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <?php if (isset($_SESSION['flash']['order_msg'])): ?>
                    <div class="alert alert-success py-1 px-3 mb-0 d-none d-md-block"><?php echo flash('order_msg')['message']; ?></div>
                <?php endif; ?>

                <div class="dropdown text-end">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="bg-primary-gold rounded-circle d-flex align-items-center justify-content-center text-dark fw-bold me-2" style="width: 40px; height: 40px;">
                            <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark px-2 shadow-lg border-secondary" aria-labelledby="dropdownUser1" style="min-width: 200px;">
                         <li><div class="dropdown-header text-white fw-bold"><?php echo htmlspecialchars($user['full_name']); ?></div></li>
                        <li><a class="dropdown-item rounded-2 mb-1" href="profile.php"><i class="fas fa-user-circle me-2 text-primary-gold"></i> Profile</a></li>
                        <li><hr class="dropdown-divider bg-secondary opacity-25"></li>
                        <li><a class="dropdown-item rounded-2 text-danger" href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i> Sign out</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card bg-dark border-secondary">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Date</th>
                                <?php if ($user['role'] === 'admin'): ?>
                                    <th>User</th><?php endif; ?>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?php echo $order['id']; ?></td>
                                    <td><?php echo date('M d, H:i', strtotime($order['created_at'])); ?></td>
                                    <?php if ($user['role'] === 'admin'): ?>
                                        <td><?php echo htmlspecialchars($order['full_name'] ?? 'Guest'); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo format_price($order['total_amount']); ?></td>
                                    <td>
                                        <span class="badge <?php
                                        echo match ($order['order_status']) {
                                            'pending' => 'bg-warning text-dark',
                                            'completed' => 'bg-eth-green text-white',
                                            'cancelled' => 'bg-eth-red text-white',
                                            'preparing' => 'bg-info text-dark',
                                            'ready' => 'bg-primary pulse-green text-white',
                                            default => 'bg-secondary text-white'
                                        };
                                        ?> rounded-pill px-3 py-2 fw-normal">
                                            <?php echo ucfirst($order['order_status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-light" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#details-<?php echo $order['id']; ?>">
                                            Details
                                        </button>
                                        <?php if ($user['role'] === 'admin'): ?>
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                data-bs-target="#statusModal-<?php echo $order['id']; ?>">
                                                Update
                                            </button>

                                            <!-- Status Modal -->
                                            <div class="modal fade" id="statusModal-<?php echo $order['id']; ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content bg-dark text-white border-secondary">
                                                        <form method="POST">
                                                            <div class="modal-header border-secondary">
                                                                <h5 class="modal-title">Update Order
                                                                    #<?php echo $order['id']; ?></h5>
                                                                <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" name="action" value="update_status">
                                                                <input type="hidden" name="order_id"
                                                                    value="<?php echo $order['id']; ?>">
                                                                <label class="form-label">Select Status</label>
                                                                <select name="status"
                                                                    class="form-select bg-black text-white border-secondary">
                                                                    <option value="pending" <?php echo $order['order_status'] == 'pending' ? 'selected' : ''; ?>>
                                                                        Pending</option>
                                                                    <option value="preparing" <?php echo $order['order_status'] == 'preparing' ? 'selected' : ''; ?>>
                                                                        Preparing</option>
                                                                    <option value="ready" <?php echo $order['order_status'] == 'ready' ? 'selected' : ''; ?>>Ready
                                                                    </option>
                                                                    <option value="completed" <?php echo $order['order_status'] == 'completed' ? 'selected' : ''; ?>>
                                                                        Completed</option>
                                                                    <option value="cancelled" <?php echo $order['order_status'] == 'cancelled' ? 'selected' : ''; ?>>
                                                                        Cancelled</option>
                                                                </select>
                                                            </div>
                                                            <div class="modal-footer border-secondary">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary-gold">Save
                                                                    changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr class="collapse" id="details-<?php echo $order['id']; ?>">
                                    <td colspan="6" class="bg-secondary bg-opacity-10 p-3">
                                        <h6 class="text-primary-gold">Order Items</h6>
                                        <!-- Logic to fetch items usually goes here, but for list view efficiency we might lazy load or pre-fetch if needed. 
                                         For this demo, I'll just show static text or require detailed fetch. 
                                         Ideally, we fetch all items with orders or trigger an AJAX call.
                                         I will leave a placeholder for simplicity in this file. -->
                                        <p class="text-muted small">Click 'View Full Details' (Implementation Pending for
                                            specific item query)</p>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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