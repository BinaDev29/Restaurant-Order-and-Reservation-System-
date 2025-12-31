<?php
$current_page = basename($_SERVER['PHP_SELF']);
$user = current_user();
?>
<div class="sidebar d-flex flex-column">
    <div class="sidebar-brand">
        <!-- Brand -->
        <a href="../index.php" class="text-decoration-none">
            <h4 class="text-primary-gold mt-2"><i class="fas fa-utensils me-2"></i>Golden Bar</h4>
        </a>
    </div>

    <ul class="nav flex-column mb-auto">
        <li class="nav-item">
            <a href="index.php" class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                <i class="fas fa-layer-group"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="orders.php" class="nav-link <?php echo $current_page == 'orders.php' ? 'active' : ''; ?>">
                <i class="fas fa-shopping-bag"></i> Orders
            </a>
        </li>
        <li class="nav-item">
            <a href="reservations.php"
                class="nav-link <?php echo $current_page == 'reservations.php' ? 'active' : ''; ?>">
                <i class="fas fa-calendar-alt"></i> Reservations
            </a>
        </li>
        <?php if ($user['role'] === 'admin'): ?>
            <li class="nav-item mt-3 mb-2 px-4">
                <small class="text-uppercase text-muted" style="font-size: 0.7rem; letter-spacing: 1px;">Admin</small>
            </li>
            <li class="nav-item">
                <a href="menu.php" class="nav-link <?php echo $current_page == 'menu.php' ? 'active' : ''; ?>">
                    <i class="fas fa-book-open"></i> Menu
                </a>
            </li>
            <li class="nav-item">
                <a href="users.php" class="nav-link <?php echo $current_page == 'users.php' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i> Users
                </a>
            </li>
            <li class="nav-item">
                <a href="employees.php" class="nav-link <?php echo $current_page == 'employees.php' ? 'active' : ''; ?>">
                    <i class="fas fa-id-card-alt"></i> Employees
                </a>
            </li>
            <li class="nav-item">
                <a href="tables.php" class="nav-link <?php echo $current_page == 'tables.php' ? 'active' : ''; ?>">
                    <i class="fas fa-chair"></i> Tables
                </a>
            </li>
        <?php endif; ?>
    </ul>

    <div class="mt-auto px-4 pb-4">
        <!-- System Health - Humanaized Element -->
        <div class="mb-4 d-none d-lg-block">
            <div class="d-flex align-items-center mb-1">
                <span class="pulse-green-small me-2"></span>
                <small class="text-uppercase text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Cloud
                    Sync: Live</small>
            </div>
            <div class="progress" style="height: 3px; background: rgba(255,255,255,0.05);">
                <div class="progress-bar bg-primary-gold" role="progressbar" style="width: 85%;"></div>
            </div>
        </div>

        <div class="sidebar-user-card">
            <div class="d-flex align-items-center mb-3">
                <div class="user-avatar-small me-3">
                    <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                </div>
                <div style="overflow: hidden;">
                    <div class="text-white text-truncate fw-medium" style="font-size: 0.9rem;">
                        <?php echo htmlspecialchars($user['full_name']); ?>
                    </div>
                    <div class="text-primary-gold"
                        style="font-size: 0.7rem; font-weight: 600; text-transform: uppercase;">
                        <?php echo $user['role']; ?>
                    </div>
                </div>
            </div>
            <a href="../logout.php" class="btn btn-outline-danger btn-sm w-100 rounded-pill border-opacity-25"
                style="font-size: 0.75rem;">
                <i class="fas fa-power-off me-2"></i> Sign Out
            </a>
        </div>
    </div>
</div>

<style>
    .sidebar-user-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 18px;
        padding: 15px;
    }

    .user-avatar-small {
        width: 35px;
        height: 35px;
        background: var(--gold-gradient);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #000;
        font-weight: 800;
        font-size: 0.8rem;
    }

    .pulse-green-small {
        display: inline-block;
        width: 6px;
        height: 6px;
        background: #00ff88;
        border-radius: 50%;
        box-shadow: 0 0 10px #00ff88;
        animation: simple-pulse 2s infinite;
    }

    @keyframes simple-pulse {
        0% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: 0.4;
            transform: scale(1.2);
        }

        100% {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>