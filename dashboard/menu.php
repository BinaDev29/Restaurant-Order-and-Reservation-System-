<?php
require_once '../app/config.php';
require_once '../app/functions.php';
require_once '../app/controllers/MenuController.php';

$controller = new MenuController();

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $controller->store();
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $controller->delete($_POST['item_id']);
    }
}

$items = $controller->index();
$categories = $controller->getCategories();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Management | <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>

<body>

    <?php include 'partials/sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4 text-white">
            <h2>Menu Management</h2>
            <button class="btn btn-primary-gold" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus me-2"></i> Add New Item
            </button>
        </div>

        <?php if (isset($_SESSION['flash']['menu_msg'])): ?>
            <div class="alert alert-<?php echo flash('menu_msg')['class']; ?> py-2 px-3 mb-4">
                <?php echo flash('menu_msg')['message']; ?>
            </div>
        <?php endif; ?>

        <div class="card bg-dark border-secondary">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td style="width: 80px;">
                                        <img src="<?php echo get_image_url($item['image']); ?>" class="rounded"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                    </td>
                                    <td class="fw-bold"><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td><span
                                            class="badge bg-secondary"><?php echo htmlspecialchars($item['category_name']); ?></span>
                                    </td>
                                    <td class="text-primary-gold"><?php echo format_price($item['price']); ?></td>
                                    <td class="small text-muted text-truncate" style="max-width: 200px;">
                                        <?php echo htmlspecialchars($item['description']); ?></td>
                                    <td>
                                        <form method="POST" onsubmit="return confirm('Delete this item?');"
                                            style="display:inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                        <button class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white border-secondary">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title">Add New Dish</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label class="form-label">Dish Name</label>
                            <input type="text" name="name" class="form-control bg-black text-white border-secondary"
                                required>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-select bg-black text-white border-secondary"
                                    required>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Price</label>
                                <input type="number" step="0.01" name="price"
                                    class="form-control bg-black text-white border-secondary" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control bg-black text-white border-secondary"
                                rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image (Upload OR URL)</label>
                            <input type="file" name="image"
                                class="form-control bg-black text-white border-secondary mb-2">
                            <input type="url" name="image_url" placeholder="https://..."
                                class="form-control bg-black text-white border-secondary">
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary-gold">Add Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>