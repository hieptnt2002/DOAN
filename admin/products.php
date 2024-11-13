<?php
require_once '../config/db.php';
require_once '../includes/functions.php';
session_start();

if (!isAdmin()) {
    header('Location: ../index.php');
    exit();
}

// Xử lý thêm sản phẩm
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $status = $_POST['status'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = uploadImage($_FILES['image'], '../uploads/products/');
        if ($image) {
            $stmt = $conn->prepare("
                INSERT INTO products (name, price, description, image, category_id, status) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$name, $price, $description, $image, $category_id, $status]);
            header('Location: products.php');
            exit();
        }
    }
}

// Xử lý sửa sản phẩm
if (isset($_POST['edit_product'])) {
    $id = $_POST['product_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $status = $_POST['status'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Xử lý upload ảnh mới
        $image = uploadImage($_FILES['image'], '../uploads/products/');
        if ($image) {
            // Xóa ảnh cũ
            $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $old_image = $stmt->fetchColumn();
            if ($old_image) {
                unlink('../uploads/products/' . $old_image);
            }
        }
    } else {
        // Giữ nguyên ảnh cũ
        $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $image = $stmt->fetchColumn();
    }

    $stmt = $conn->prepare("
        UPDATE products 
        SET name = ?, price = ?, description = ?, image = ?, category_id = ?, status = ?
        WHERE id = ?
    ");
    $stmt->execute([$name, $price, $description, $image, $category_id, $status, $id]);
    header('Location: products.php');
    exit();
}

// Xử lý xóa sản phẩm
if (isset($_POST['delete_product'])) {
    $id = $_POST['product_id'];
    
    // Xóa ảnh
    $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $image = $stmt->fetchColumn();
    if ($image) {
        unlink('../uploads/products/' . $image);
    }
    
    // Xóa sản phẩm
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: products.php');
    exit();
}

// Lấy danh sách sản phẩm
$stmt = $conn->query("
    SELECT p.*, c.name as category_name,
           COALESCE(p.status, 'active') as status
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    ORDER BY p.created_at DESC
");
$products = $stmt->fetchAll();

// Lấy danh sách danh mục cho form
$categories = $conn->query("SELECT * FROM categories ORDER BY name")->fetchAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Sản Phẩm - Admin</title>
    <?php include '../includes/admin/components/head.php'; ?>
    <!-- Thêm CSS cho modal -->
    <style>
        .modal-lg { max-width: 800px; }
        .product-thumb { width: 50px; height: 50px; object-fit: cover; }
        .preview-image { max-width: 200px; margin-top: 10px; }
    </style>
</head>
<body>
    <?php include '../includes/admin/components/header.php'; ?>
    
    <div class="admin-container">
        <?php include '../includes/admin/components/sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="content-header">
                <h2>Quản Lý Sản Phẩm</h2>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="fa fa-plus"></i> Thêm Sản Phẩm
                </button>
            </div>
            
            <div class="products-list">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Hình Ảnh</th>
                            <th>Tên Sản Phẩm</th>
                            <th>Giá</th>
                            <th>Danh Mục</th>
                            <th>Trạng Thái</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td>
                                <img src="../uploads/products/<?php echo $product['image']; ?>" 
                                     alt="<?php echo $product['name']; ?>"
                                     class="product-thumb">
                            </td>
                            <td><?php echo $product['name']; ?></td>
                            <td><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</td>
                            <td><?php echo htmlspecialchars($product['category_name'] ?? 'Chưa phân loại'); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $product['status']; ?>">
                                    <?php echo getProductStatus($product['status']); ?>
                                </span>
                            </td>
                            <td class="actions">
                                <button type="button" class="btn btn-sm btn-info" 
                                        onclick="editProduct(<?php echo htmlspecialchars(json_encode($product)); ?>)">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" 
                                        onclick="deleteProduct(<?php echo $product['id']; ?>)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Sản Phẩm -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Sản Phẩm Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tên sản phẩm</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Giá</label>
                                    <input type="number" name="price" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Danh mục</label>
                                    <select name="category_id" class="form-control" required>
                                        <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Mô tả</label>
                                    <textarea name="description" class="form-control" rows="4"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Hình ảnh</label>
                                    <input type="file" name="image" class="form-control" required 
                                           onchange="previewImage(this, 'addImagePreview')">
                                    <img id="addImagePreview" class="preview-image">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Trạng thái</label>
                                    <select name="status" class="form-control">
                                        <option value="active">Đang bán</option>
                                        <option value="inactive">Ngừng bán</option>
                                        <option value="out_of_stock">Hết hàng</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" name="add_product" class="btn btn-primary">Thêm sản phẩm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Sửa Sản Phẩm -->
    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa Sản Phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="product_id" id="edit_product_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tên sản phẩm</label>
                                    <input type="text" name="name" id="edit_name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Giá</label>
                                    <input type="number" name="price" id="edit_price" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Danh mục</label>
                                    <select name="category_id" id="edit_category_id" class="form-control" required>
                                        <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Mô tả</label>
                                    <textarea name="description" id="edit_description" class="form-control" rows="4"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Hình ảnh hiện tại</label>
                                    <img id="edit_current_image" class="product-thumb d-block mb-2">
                                    <input type="file" name="image" class="form-control" 
                                           onchange="previewImage(this, 'editImagePreview')">
                                    <img id="editImagePreview" class="preview-image">
                                    <small class="text-muted">Chỉ chọn ảnh nếu muốn thay đổi</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Trạng thái</label>
                                    <select name="status" id="edit_status" class="form-control">
                                        <option value="active">Đang bán</option>
                                        <option value="inactive">Ngừng bán</option>
                                        <option value="out_of_stock">Hết hàng</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" name="edit_product" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include '../includes/admin/components/footer.php'; ?>

    <script>
    // Preview ảnh trước khi upload
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Mở modal sửa sản phẩm
    function editProduct(product) {
        document.getElementById('edit_product_id').value = product.id;
        document.getElementById('edit_name').value = product.name;
        document.getElementById('edit_price').value = product.price;
        document.getElementById('edit_description').value = product.description;
        document.getElementById('edit_category_id').value = product.category_id;
        document.getElementById('edit_status').value = product.status;
        document.getElementById('edit_current_image').src = '../uploads/products/' + product.image;
        
        var editModal = new bootstrap.Modal(document.getElementById('editProductModal'));
        editModal.show();
    }

    // Xác nhận xóa sản phẩm
    function deleteProduct(id) {
        if (confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="product_id" value="${id}">
                <input type="hidden" name="delete_product" value="1">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }
    </script>
</body>
</html> 