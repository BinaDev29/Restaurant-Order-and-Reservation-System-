<?php
require_once '../app/config.php';
require_once '../app/functions.php';
require_once '../app/controllers/TableController.php';

require_login();
$user = current_user();

if ($user['role'] !== 'admin') {
    redirect('index.php'); // Admin only
}

$controller = new TableController();

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $number = sanitize_input($_POST['table_number']);
            $capacity = (int) $_POST['capacity'];
            $controller->store($number, $capacity);
            flash('success', 'Table added successfully');
        } elseif ($_POST['action'] === 'delete') {
            $controller->delete($_POST['id']);
            flash('success', 'Table removed');
        } elseif ($_POST['action'] === 'toggle') {
            $controller->toggleStatus($_POST['id']);
            flash('success', 'Table status updated');
        }
    }
    redirect('dashboard/tables.php');
}

$tables = $controller->index();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tables | <?php echo APP_NAME; ?></title>
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
            class="d-flex flex-column flex-md-row justify-content-between align-items-end mb-4 text-white fade-in-up gap-3 dashboard-header">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-light d-lg-none me-3" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div>
                    <h6 class="text-primary-gold text-uppercase letter-spacing-2 mb-1">Management</h6>
                    <h2 class="display-5 fw-bold" style="font-family: 'Playfair Display', serif;">Dining Tables</h2>
                </div>
            </div>

            <div class="d-flex gap-3 align-items-center w-100 w-md-auto">
                <button class="btn btn-primary-gold rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#addTableModal">
                    <i class="fas fa-plus me-2"></i>Add Table
                </button>

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

        <?php if (isset($_SESSION['flash']['success'])): ?>
            <div class="alert alert-success bg-success text-white border-0 bg-opacity-25 fade-in-up mb-4">
                <i class="fas fa-check-circle me-2"></i> <?php echo flash('success')['message']; ?>
            </div>
        <?php endif; ?>

        <!-- Tables Grid -->
        <div class="row g-4 fade-in-up" style="animation-delay: 0.2s;">
            <?php foreach ($tables as $table): ?>
                <div class="col-md-3">
                    <div
                        class="stat-card detail-card h-100 position-relative flex-column align-items-start p-4 <?php echo $table['status'] === 'occupied' ? 'border-danger' : ''; ?>">
                        <div class="d-flex justify-content-between w-100 mb-3">
                            <div class="bg-primary-gold rounded-circle d-flex align-items-center justify-content-center fw-bold text-dark fs-5"
                                style="width: 50px; height: 50px;">
                                <?php echo $table['table_number']; ?>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-link text-white opacity-50 p-0" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end bg-dark border-secondary shadow">
                                    <li>
                                        <form method="POST">
                                            <input type="hidden" name="id" value="<?php echo $table['id']; ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="dropdown-item text-danger"
                                                onclick="return confirm('Delete this table?')">Delete</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h5 class="text-white mb-1">Capacity: <?php echo $table['capacity']; ?> Guests</h5>

                        <div class="mt-3 w-100">
                            <form method="POST">
                                <input type="hidden" name="id" value="<?php echo $table['id']; ?>">
                                <input type="hidden" name="action" value="toggle">
                                <?php if ($table['status'] === 'available'): ?>
                                    <button class="btn btn-sm btn-outline-success w-100 rounded-pill">
                                        <i class="fas fa-check-circle me-1"></i> Available
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-outline-danger w-100 rounded-pill">
                                        <i class="fas fa-user-lock me-1"></i> Occupied
                                    </button>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Add Table Modal -->
    <div class="modal fade" id="addTableModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark border-secondary text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Add New Table</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label class="form-label text-muted">Table Number / Name</label>
                            <input type="text" name="table_number"
                                class="form-control bg-black text-white border-secondary" required
                                placeholder="e.g. T-12">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Seating Capacity</label>
                            <input type="number" name="capacity"
                                class="form-control bg-black text-white border-secondary" required placeholder="e.g. 4">
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary-gold">Save Table</button>
                    </div>
                </form>
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