<?php
require_once '../app/config.php';
require_once '../app/functions.php';
require_once '../app/controllers/OrderController.php';

$controller = new OrderController();
$user = current_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    if (in_array($user['role'], ['admin', 'staff', 'chef', 'waiter'])) {
        $controller->updateStatus($_POST['order_id'], $_POST['status']);
        flash('order_msg', 'Order #' . $_POST['order_id'] . ' updated to ' . $_POST['status']);
        redirect('dashboard/orders.php');
    }
}

$orders = $controller->getOrdersWithItems();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders | <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <style>
        .order-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            transition: all 0.3s ease;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .order-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--primary-gold);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .order-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-body {
            padding: 1.5rem;
        }

        .order-footer {
            padding: 1rem 1.5rem;
            background: rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .status-pill {
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .status-pending {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.2);
        }

        .status-preparing {
            background: rgba(13, 202, 240, 0.1);
            color: #0dcaf0;
            border: 1px solid rgba(13, 202, 240, 0.2);
        }

        .status-ready {
            background: rgba(25, 135, 84, 0.1);
            color: #198754;
            border: 1px solid rgba(25, 135, 84, 0.2);
            box-shadow: 0 0 15px rgba(25, 135, 84, 0.2);
            animation: pulse-green 2s infinite;
        }

        .status-completed {
            background: rgba(255, 255, 255, 0.05);
            color: #aaa;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .status-cancelled {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        @keyframes pulse-green {
            0% {
                box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(25, 135, 84, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(25, 135, 84, 0);
            }
        }

        .item-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .item-list li {
            padding: 8px 0;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.05);
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
        }

        .item-list li:last-child {
            border-bottom: none;
        }

        .btn-action-sm {
            padding: 5px 12px;
            font-size: 0.75rem;
            border-radius: 10px;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <?php include 'partials/sidebar.php'; ?>

    <div class="main-content">
        <!-- Header -->
        <div class="dashboard-header mb-4">
            <div>
                <h2 class="display-6 fw-bold text-white mb-1">Live Orders</h2>
                <p class="text-white-50">Manage real-time kitchen requests and deliveries.</p>
            </div>

            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-glass" onclick="location.reload()">
                    <i class="fas fa-sync-alt me-2"></i> Refresh
                </button>
                <div class="user-avatar-premium">
                    <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                    <div class="status-indicator"></div>
                </div>
            </div>
        </div>

        <?php if (isset($_SESSION['flash']['order_msg'])): ?>
            <div class="alert alert-premium mb-4 animate__animated animate__fadeInDown">
                <i class="fas fa-check-circle me-3 text-success"></i>
                <?php echo flash('order_msg')['message']; ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <?php if (empty($orders)): ?>
                <div class="col-12 text-center py-5">
                    <div class="opacity-25 mb-3">
                        <i class="fas fa-receipt fa-4x"></i>
                    </div>
                    <h4 class="text-white-50">No orders found matching your criteria.</h4>
                </div>
            <?php endif; ?>

            <?php foreach ($orders as $order): ?>
                <div class="col-xl-4 col-md-6">
                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <span
                                    class="text-primary-gold fw-bold">#ORD-<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></span>
                                <div class="small text-white-50">
                                    <?php echo date('M d, H:i', strtotime($order['created_at'])); ?>
                                </div>
                            </div>
                            <span class="status-pill status-<?php echo $order['order_status']; ?>">
                                <?php echo $order['order_status']; ?>
                            </span>
                        </div>

                        <div class="order-body">
                            <div class="d-flex justify-content-between mb-3 align-items-center">
                                <h6 class="mb-0 text-white-50">Customer Details</h6>
                                <span
                                    class="badge bg-primary-gold text-dark"><?php echo ucfirst($order['order_type'] ?? 'Dine-in'); ?></span>
                            </div>
                            <p class="mb-4 fw-bold"><?php echo htmlspecialchars($order['full_name'] ?? 'Guest'); ?></p>

                            <h6 class="text-primary-gold mb-3 small text-uppercase letter-spacing-1">Order Items</h6>
                            <ul class="item-list">
                                <?php
                                $items = explode(', ', $order['items_summary']);
                                foreach ($items as $item): ?>
                                    <li>
                                        <span><?php echo htmlspecialchars($item); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div class="order-footer">
                            <div class="text-white">
                                <span class="small opacity-50">Total</span>
                                <div class="fw-bold fs-5"><?php echo format_price($order['total_amount']); ?></div>
                            </div>

                            <div class="d-flex gap-2">
                                <?php if ($order['order_status'] === 'pending'): ?>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <input type="hidden" name="status" value="preparing">
                                        <button type="submit" class="btn btn-action-sm btn-outline-info">
                                            Start Cooking
                                        </button>
                                    </form>
                                <?php elseif ($order['order_status'] === 'preparing'): ?>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <input type="hidden" name="status" value="ready">
                                        <button type="submit" class="btn btn-action-sm btn-outline-success">
                                            Mark Ready
                                        </button>
                                    </form>
                                <?php elseif ($order['order_status'] === 'ready'): ?>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="btn btn-action-sm btn-success text-white">
                                            Served/Paid
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <div class="dropdown">
                                    <button class="btn btn-action-sm btn-glass px-2" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end border-secondary">
                                        <li><a class="dropdown-item py-2" href="#"
                                                onclick="viewDetails(<?php echo $order['id']; ?>)">View Details</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <?php if ($user['role'] === 'admin'): ?>
                                            <li><a class="dropdown-item py-2 text-danger" href="#"
                                                    onclick="cancelOrder(<?php echo $order['id']; ?>)">Cancel Order</a></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Details Modal Placeholder -->
    <div class="modal fade" id="detailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark border-secondary rounded-4">
                <div id="modalContent">
                    <div class="p-5 text-center">
                        <div class="spinner-border text-primary-gold"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Toggle for Mobile
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('sidebarToggle');
            if (toggle) {
                toggle.addEventListener('click', function () {
                    document.querySelector('.sidebar').classList.toggle('show');
                });
            }
        });

        function viewDetails(id) {
            const modalElement = document.getElementById('detailsModal');
            const modal = new bootstrap.Modal(modalElement);
            const content = document.getElementById('modalContent');

            content.innerHTML = `
                <div class="modal-body p-5 text-center">
                    <div class="spinner-border text-primary-gold mb-3"></div>
                    <p class="text-white-50">Fetching exquisite order details...</p>
                </div>
            `;
            modal.show();

            fetch(`../app/api/orders.php?id=${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        const order = data.order;
                        let itemsHtml = order.items.map(item => `
                            <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom border-secondary border-opacity-25">
                                <img src="${item.image_url}" class="rounded-3" style="width: 60px; height: 60px; object-fit: cover;">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-white">${item.name}</h6>
                                    <small class="text-white-50">${item.quantity} x ${item.unit_price_fmt}</small>
                                </div>
                                <div class="fw-bold text-primary-gold">${item.subtotal_fmt}</div>
                            </div>
                        `).join('');

                        content.innerHTML = `
                            <div class="modal-header border-secondary p-4">
                                <div>
                                    <h5 class="modal-title fw-bold">Order #ORD-${String(order.id).padStart(5, '0')}</h5>
                                    <small class="text-white-50">${new Date(order.created_at).toLocaleString()}</small>
                                </div>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <h6 class="text-primary-gold small text-uppercase mb-3">Customer Information</h6>
                                        <p class="mb-1 fw-bold">${order.full_name || 'Guest User'}</p>
                                        <p class="mb-1 text-white-50 small"><i class="fas fa-envelope me-2"></i>${order.email || 'N/A'}</p>
                                        <p class="mb-0 text-white-50 small"><i class="fas fa-phone me-2"></i>${order.phone || 'N/A'}</p>
                                    </div>
                                    <div class="col-md-6 text-md-end">
                                        <h6 class="text-primary-gold small text-uppercase mb-3">Order Status</h6>
                                        <span class="status-pill status-${order.order_status}">${order.order_status}</span>
                                    </div>
                                </div>
                                
                                <h6 class="text-primary-gold small text-uppercase mb-3">Order Items</h6>
                                <div class="item-details-list">
                                    ${itemsHtml}
                                </div>
                                
                                <div class="mt-4 p-3 rounded-4 bg-white bg-opacity-5">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-white-50">Subtotal</span>
                                        <span>${order.total_amount_fmt}</span>
                                    </div>
                                    <div class="d-flex justify-content-between pt-2 border-top border-secondary border-opacity-50">
                                        <span class="fw-bold">Total Amount</span>
                                        <span class="fw-bold fs-5 text-primary-gold">${order.total_amount_fmt}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-secondary p-4">
                                <button class="btn btn-glass px-4" data-bs-dismiss="modal">Close</button>
                                ${order.order_status === 'pending' ? `<button class="btn btn-outline-danger px-4" onclick="cancelOrder(${order.id})">Cancel Order</button>` : ''}
                            </div>
                        `;
                    } else {
                        content.innerHTML = `<div class="p-5 text-center text-danger">${data.message}</div>`;
                    }
                })
                .catch(err => {
                    content.innerHTML = `<div class="p-5 text-center text-danger">Connection Error</div>`;
                });
        }

        function cancelOrder(id) {
            if (!confirm('Are you sure you want to cancel this order?')) return;

            fetch('../app/api/orders.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'cancel', order_id: id })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                });
        }

        // Live Polling
        let lastOrderCount = <?php echo count($orders); ?>;
        setInterval(() => {
            // For a production app, we'd fetch actual counts here. 
            // Simple refresh if count changes would be minimal implementation:
            fetch(location.href)
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newCards = doc.querySelectorAll('.order-card').length;
                    if (newCards !== lastOrderCount) {
                        // Better to show a "New Orders" toast or update DOM dynamically
                        console.log("New items detected!");
                        // For demo, we'll just log. In real usage: location.reload();
                    }
                });
        }, 15000);
    </script>
</body>

</html>