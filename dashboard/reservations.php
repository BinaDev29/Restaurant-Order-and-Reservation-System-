<?php
require_once '../app/config.php';
require_once '../app/functions.php';
require_once '../app/controllers/ReservationController.php';

require_login(); 

$controller = new ReservationController();
$user = current_user();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user['role'] === 'admin') {
    if (isset($_POST['action']) && isset($_POST['id'])) {
        $id = $_POST['id'];
        $action = $_POST['action'];
        
        if ($action === 'confirm') {
            $controller->updateStatus($id, 'confirmed');
            flash('success', 'Reservation confirmed successfully.');
        } elseif ($action === 'cancel') {
            $controller->updateStatus($id, 'cancelled');
            flash('success', 'Reservation cancelled.');
        } elseif ($action === 'complete') {
            $controller->updateStatus($id, 'completed');
            flash('success', 'Reservation marked as completed.');
        }
    }
    redirect('dashboard/reservations.php');
}

$search = sanitize_input($_GET['search'] ?? '');
$reservations = $controller->index($search);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservations | <?php echo APP_NAME; ?></title>
    
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
   
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>

<body>

    <?php include 'partials/sidebar.php'; ?>

    <div class="main-content">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-end mb-4 text-white fade-in-up gap-3 dashboard-header">
            <div class="d-flex align-items-center">
                 <button class="btn btn-outline-light d-lg-none me-3" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div>
                     <h6 class="text-primary-gold text-uppercase letter-spacing-2 mb-1">Bookings</h6>
                    <h2 class="display-5 fw-bold" style="font-family: 'Playfair Display', serif;">Reservations</h2>
                </div>
            </div>
            
            <div class="d-flex gap-3 align-items-center w-100 w-md-auto">
                 <?php if($user['role'] === 'admin'): ?>
                    <form action="" method="GET" class="flex-grow-1">
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-muted"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" class="form-control bg-dark border-secondary text-white" placeholder="Search name or ID..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                    </form>
                 <?php endif; ?>
            
                 <!-- Action Button -->
                 <?php if($user['role'] !== 'admin'): ?>
                    <a href="../reservation.php" class="btn btn-primary-gold rounded-pill px-4 fw-bold shadow-sm text-nowrap">
                        <i class="fas fa-plus me-2"></i>New Reservation
                    </a>
                <?php endif; ?>

                <div class="dropdown text-end ms-2">
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

        <?php if (isset($_SESSION['flash']['success'])): ?>
            <div class="alert alert-success bg-success text-white border-0 bg-opacity-25 fade-in-up mb-4">
                <i class="fas fa-check-circle me-2"></i> <?php echo flash('success')['message']; ?>
            </div>
        <?php endif; ?>

        <!-- Reservations Table -->
        <div class="table-card fade-in-up" style="animation-delay: 0.2s;">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Date & Time</th>
                            <?php if ($user['role'] === 'admin'): ?>
                                <th>Guest Info</th>
                            <?php endif; ?>
                            <th>Table</th>
                            <th>Guests</th>
                            <th>Status</th>
                            <?php if ($user['role'] === 'admin'): ?>
                                <th class="text-end pe-4">Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $res): ?>
                            <tr>
                                <td class="ps-4 fw-medium text-white-50">#<?php echo $res['id']; ?></td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-white"><?php echo date('M d, Y', strtotime($res['reservation_date'])); ?></span>
                                        <small class="text-muted"><?php echo date('h:i A', strtotime($res['reservation_time'])); ?></small>
                                    </div>
                                </td>
                                <?php if ($user['role'] === 'admin'): ?>
                                    <td>
                                        <div class="text-white fw-medium"><?php echo htmlspecialchars($res['full_name']); ?></div>
                                        <div class="small text-muted"><i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($res['phone'] ?? 'N/A'); ?></div>
                                    </td>
                                <?php endif; ?>
                                
                                <td>
                                    <?php if($res['table_number']): ?>
                                        <span class="badge bg-secondary bg-opacity-25 text-white border border-secondary">
                                            Table <?php echo $res['table_number']; ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">Not Assigned</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-white"><i class="fas fa-user-friends me-2 text-muted"></i><?php echo $res['guests']; ?></td>
                                
                                <td>
                                     <?php 
                                    $statusClass = match ($res['status']) {
                                        'pending' => 'bg-warning text-dark',
                                        'confirmed' => 'bg-eth-green pulse-green text-white',
                                        'cancelled' => 'bg-eth-red text-white',
                                        'completed' => 'bg-info text-dark',
                                        default => 'bg-secondary text-white'
                                    };
                                    ?>
                                    <span class="badge rounded-pill <?php echo $statusClass; ?> px-3 py-2 fw-normal text-uppercase" style="font-size: 0.7rem;">
                                        <?php echo ucfirst($res['status']); ?>
                                    </span>
                                </td>
                                
                                <?php if ($user['role'] === 'admin'): ?>
                                    <td class="text-end pe-4">
                                        <?php if ($res['status'] === 'pending'): ?>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="id" value="<?php echo $res['id']; ?>">
                                                <input type="hidden" name="action" value="confirm">
                                                <button type="submit" class="btn btn-sm btn-success rounded-circle" title="Confirm">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="id" value="<?php echo $res['id']; ?>">
                                                <input type="hidden" name="action" value="cancel">
                                                <button type="submit" class="btn btn-sm btn-danger rounded-circle" title="Cancel">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        <?php elseif ($res['status'] === 'confirmed'): ?>
                                             <form method="POST" class="d-inline">
                                                <input type="hidden" name="id" value="<?php echo $res['id']; ?>">
                                                <input type="hidden" name="action" value="complete">
                                                <button type="submit" class="btn btn-sm btn-info rounded-circle text-white" title="Mark Completed">
                                                    <i class="fas fa-flag-checkered"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($reservations)): ?>
                             <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted opacity-50 mb-2"><i class="fas fa-calendar-times fa-3x"></i></div>
                                    <p class="text-muted">No reservations found.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });
    </script>
</body>
</html>
