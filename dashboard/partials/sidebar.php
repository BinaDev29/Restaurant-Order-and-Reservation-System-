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
        <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.05);">
            <div class="d-flex align-items-center mb-3">
                <div class="user-avatar me-3">
                    <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                </div>
                <div style="overflow: hidden;">
                    <div class="text-white text-truncate fw-medium"><?php echo htmlspecialchars($user['full_name']); ?>
                    </div>
                    <div class="text-primary-gold small" style="font-size: 0.75rem;">
                        <?php echo ucfirst($user['role']); ?>
                    </div>
                </div>
            </div>
            <hr class="border-secondary opacity-25 my-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="profile.php"
                        class="nav-link px-0 py-1 <?php echo $current_page == 'profile.php' ? 'active' : ''; ?>">
                        <i class="fas fa-user-circle me-2"></i> My Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../logout.php" class="nav-link px-0 py-1 text-white-50 opacity-75 hover-opacity-100">
                        <i class="fas fa-sign-out-alt me-2"></i> Sign out
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>