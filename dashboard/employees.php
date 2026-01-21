<?php
require_once '../app/config.php';
require_once '../app/functions.php';
require_once '../app/controllers/UserController.php';

require_login();
$user = current_user();

if ($user['role'] !== 'admin') {
    redirect('index.php');
}

$controller = new UserController();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $name = sanitize_input($_POST['full_name']);
            $email = sanitize_input($_POST['email']);
            $phone = sanitize_input($_POST['phone']);
            $password = $_POST['password'];

            // New Fields
            $position = sanitize_input($_POST['position']);
            $salary = (float) $_POST['salary'];
            $hire_date = $_POST['hire_date'];

            if ($controller->createEmployee($name, $email, $password, $phone, $position, $salary, $hire_date)) {
                flash('success', 'Employee added successfully');
            } else {
                flash('error', 'Failed to add employee. Email might be in use.');
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['id'];
            if ($id != $user['id']) {
                $controller->delete($id);
                flash('success', 'Employee removed');
            } else {
                flash('error', 'Cannot delete your own account');
            }
        }
    }
    redirect('dashboard/employees.php');
}

$search = sanitize_input($_GET['search'] ?? '');
$employees = $controller->getEmployees($search);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees | <?php echo APP_NAME; ?></title>
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
                    <h6 class="text-primary-gold text-uppercase letter-spacing-2 mb-1">Human Resources</h6>
                    <h2 class="display-5 fw-bold" style="font-family: 'Playfair Display', serif;">Employees</h2>
                </div>
            </div>

            <div class="d-flex gap-3 align-items-center w-100 w-md-auto">
                <form action="" method="GET" class="flex-grow-1">
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-muted"><i
                                class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control bg-dark border-secondary text-white"
                            placeholder="Search staff..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                </form>

                <button class="btn btn-primary-gold rounded-pill px-4 fw-bold shadow-sm text-nowrap"
                    data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                    <i class="fas fa-user-plus me-2"></i>Add Employee
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
        <?php if (isset($_SESSION['flash']['error'])): ?>
            <div class="alert alert-danger bg-danger text-white border-0 bg-opacity-25 fade-in-up mb-4">
                <i class="fas fa-exclamation-circle me-2"></i> <?php echo flash('error')['message']; ?>
            </div>
        <?php endif; ?>

        <!-- Employees List -->
        <div class="row g-4 fade-in-up" style="animation-delay: 0.2s;">
            <?php foreach ($employees as $emp): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="stat-card detail-card h-100 position-relative">
                        <!-- Status Badge -->
                        <span class="position-absolute top-0 end-0 m-4 badge rounded-pill 
                            <?php echo ($emp['emp_status'] ?? 'active') === 'active' ? 'bg-success' : 'bg-secondary'; ?> 
                            bg-opacity-25 text-white border border-secondary">
                            <?php echo ucfirst($emp['emp_status'] ?? 'Active'); ?>
                        </span>

                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary-gold rounded-circle d-flex align-items-center justify-content-center fw-bold text-dark fs-3 me-3"
                                style="width: 70px; height: 70px; min-width: 70px;">
                                <?php echo strtoupper(substr($emp['full_name'], 0, 1)); ?>
                            </div>
                            <div>
                                <h5 class="text-white mb-1"><?php echo htmlspecialchars($emp['full_name']); ?></h5>
                                <div class="text-primary-gold small text-uppercase letter-spacing-1 fw-bold">
                                    <?php echo htmlspecialchars($emp['position'] ?? 'Staff Member'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="bg-dark bg-opacity-50 p-3 rounded-3 mb-3 border border-secondary">
                            <div class="row g-2">
                                <div class="col-6">
                                    <small class="text-muted d-block">Contact</small>
                                    <div class="text-white-50 text-truncate"><?php echo htmlspecialchars($emp['email']); ?>
                                    </div>
                                    <div class="text-white-50 small"><?php echo htmlspecialchars($emp['phone']); ?></div>
                                </div>
                                <div class="col-6 border-start border-secondary ps-3">
                                    <small class="text-muted d-block">Salary</small>
                                    <div class="text-white fw-medium">ETB
                                        <?php echo number_format($emp['salary'] ?? 0, 2); ?>
                                    </div>
                                    <small class="text-muted d-block mt-2">Hire Date</small>
                                    <div class="text-white-50 small">
                                        <?php echo $emp['hire_date'] ? date('M d, Y', strtotime($emp['hire_date'])) : 'N/A'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 w-100">
                            <!-- Future: Edit Button -->
                            <form method="POST" class="w-100"
                                onsubmit="return confirm('Terminating this employee will remove their system access. Continue?');">
                                <input type="hidden" name="id" value="<?php echo $emp['id']; ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100 rounded-pill">Terminate /
                                    Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Add New Card -->
            <div class="col-md-6 col-lg-4" data-bs-toggle="modal" data-bs-target="#addEmployeeModal"
                style="cursor: pointer;">
                <div class="stat-card detail-card align-items-center justify-content-center text-center p-4 h-100"
                    style="border-style: dashed !important; border-color: rgba(255,255,255,0.1); min-height: 280px;">
                    <div class="bg-secondary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center text-white-50 fs-3 mb-3"
                        style="width: 60px; height: 60px;">
                        <i class="fas fa-plus"></i>
                    </div>
                    <h6 class="text-muted">Register New Staff</h6>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Employee Modal -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark border-secondary text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Register New Employee</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <div class="row g-3">
                            <!-- Personal Info -->
                            <div class="col-md-6">
                                <label class="form-label text-muted">Full Name</label>
                                <input type="text" name="full_name"
                                    class="form-control bg-black text-white border-secondary" required
                                    placeholder="John Doe">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Position / Job Title</label>
                                <input type="text" name="position"
                                    class="form-control bg-black text-white border-secondary" required
                                    placeholder="e.g. Head Chef">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted">Email Address</label>
                                <input type="email" name="email"
                                    class="form-control bg-black text-white border-secondary" required
                                    placeholder="staff@restaurant.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Phone Number</label>
                                <input type="tel" name="phone" class="form-control bg-black text-white border-secondary"
                                    placeholder="+251...">
                            </div>

                            <!-- Job Details -->
                            <div class="col-md-6">
                                <label class="form-label text-muted">Monthly Salary (ETB)</label>
                                <input type="number" step="0.01" name="salary"
                                    class="form-control bg-black text-white border-secondary" required
                                    placeholder="0.00">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Start Date</label>
                                <input type="date" name="hire_date"
                                    class="form-control bg-black text-white border-secondary" required
                                    value="<?php echo date('Y-m-d'); ?>">
                            </div>

                            <!-- Security -->
                            <div class="col-12">
                                <label class="form-label text-muted">Temporary Password</label>
                                <input type="password" name="password"
                                    class="form-control bg-black text-white border-secondary" required
                                    placeholder="Create password">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary-gold px-4">Create Employee Profile</button>
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