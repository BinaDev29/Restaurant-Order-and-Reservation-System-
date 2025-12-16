<?php
$current_page = basename($_SERVER['PHP_SELF']);
$user = current_user();
?>
<div class="sidebar d-flex flex-column text-white">
    <div class="px-4 mb-4">
        <h4 class="text-primary-gold"><i class="fas fa-utensils me-2"></i>Lumina</h4>
    </div>
    <ul class="nav flex-column mb-auto">
        <li class="nav-item">
            <a href="index.php" class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
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
                <i class="fas fa-calendar-check"></i> Reservations
            </a>
        </li>
        <?php if ($user['role'] === 'admin'): ?>
            <li class="nav-item">
                <a href="menu.php" class="nav-link <?php echo $current_page == 'menu.php' ? 'active' : ''; ?>">
                    <i class="fas fa-book-open"></i> Menu Management
                </a>
            </li>
            <li class="nav-item">
                <a href="users.php" class="nav-link <?php echo $current_page == 'users.php' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i> Users
                </a>
            </li>
        <?php endif; ?>
    </ul>
    <div class="mt-auto px-4 pb-4">
        <div class="d-flex align-items-center mb-3">
            <div class="bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center text-white"
                style="width: 35px; height: 35px;">
                <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
            </div>
            <div>
                <small class="d-block text-white"><?php echo htmlspecialchars($user['full_name']); ?></small>
                <small class="text-muted" style="font-size: 0.8rem;"><?php echo ucfirst($user['role']); ?></small>
            </div>
        </div>
        <a href="../logout.php" class="btn btn-outline-danger btn-sm w-100">
            <i class="fas fa-sign-out-alt me-1"></i> Logout
        </a>
    </div>
</div>