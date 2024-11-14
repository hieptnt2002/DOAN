<?php

function getAllProducts($conn)
{
    $stmt = $conn->prepare("SELECT * FROM products ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMaleProducts($conn, $page = 1, $products_per_page = 8)
{
    $offset = ($page - 1) * $products_per_page;

    $stmt = $conn->prepare("SELECT * FROM products 
            WHERE category_id = 1  
            ORDER BY id DESC 
            LIMIT :limit 
            OFFSET :offset");

    $stmt->bindValue(':limit', $products_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getFemaleProducts($conn, $page = 1, $products_per_page = 8)
{
    $offset = ($page - 1) * $products_per_page;

    $stmt = $conn->prepare("SELECT * FROM products 
            WHERE category_id = 2  
            ORDER BY id DESC 
            LIMIT :limit 
            OFFSET :offset");

    $stmt->bindValue(':limit', $products_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTotalMaleProducts($conn)
{
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM products WHERE category_id = 1");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}

function getTotalFemaleProducts($conn)
{
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM products WHERE category_id = 2");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}

function formatPrice($price)
{
    return number_format($price, 0, ',', '.') . 'đ';
}

function getBanner($conn, $category_id)
{
    $stmt = $conn->prepare("SELECT * FROM banners WHERE category_id = ?");
    $stmt->execute([$category_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function registerUser($conn, $username, $email, $password, $fullname = null, $phone = null, $address = null)
{
    try {
        $username = trim($username);
        $email = trim(strtolower($email));
        $fullname = trim($fullname);
        $phone = trim($phone);
        $address = trim($address);

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, fullname, phone, address, role) 
                               VALUES (:username, :email, :password, :fullname, :phone, :address, 'user')");

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);

        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Registration error: " . $e->getMessage());
        return false;
    }
}

function loginUser($conn, $email, $password)
{
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([trim($email)]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
        return false;
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        return false;
    }
}

function getKnowledgeArticles($conn, $page = 1, $per_page = 6)
{
    $offset = ($page - 1) * $per_page;

    $stmt = $conn->prepare("SELECT * FROM articles 
            WHERE status = 'published' 
            ORDER BY created_at DESC 
            LIMIT :limit OFFSET :offset");

    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getOrders($conn)
{
    if (!isset($_SESSION['user_id'])) {
        return [];
    }

    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("
        SELECT 
            od.id,
            od.order_id,
            od.quantity,
            od.price,
            p.name as product_name,
            p.stock as product_stock,
            (od.price * od.quantity) as subtotal
        FROM orders o
        JOIN order_details od ON o.id = od.order_id
        JOIN products p ON od.product_id = p.id
        WHERE o.user_id = ? AND o.status = 'pending'
        ORDER BY od.id DESC
    ");

    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTotalOrders($orders)
{
    $total = 0;
    foreach ($orders as $order) {
        $total += ($order['price'] * $order['quantity']);
    }
    return $total;
}

function updateQuantityOrder($conn)
{
    if (isset($_POST['action']) && $_POST['action'] == 'update_quantity' && isset($_POST['order_id']) && isset($_POST['quantity'])) {
        try {
            $orderDetailId = intval($_POST['order_id']);
            $quantity = max(1, intval($_POST['quantity']));
            $userId = $_SESSION['user_id'];

            // Kiểm tra order detail và stock
            $stmt = $conn->prepare("
                SELECT od.id, p.stock 
                FROM order_details od
                JOIN orders o ON od.order_id = o.id
                JOIN products p ON od.product_id = p.id
                WHERE od.id = ? AND o.user_id = ? AND o.status = 'pending'
            ");
            $stmt->execute([$orderDetailId, $userId]);
            $result = $stmt->fetch();

            if ($result) {
                // Kiểm tra số lượng tồn kho
                if ($quantity > $result['stock']) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Số lượng vượt quá hàng tồn kho'
                    ]);
                    exit;
                }

                // Cập nhật số lượng
                $stmt = $conn->prepare("UPDATE order_details SET quantity = :quantity WHERE id = :id");
                $stmt->bindParam(":quantity", $quantity);
                $stmt->bindParam(":id", $orderDetailId);
                $stmt->execute();

                // Lấy danh sách orders mới và tính tổng
                $orders = getOrders($conn);
                $totalPrice = getTotalOrders($orders);

                echo json_encode([
                    'success' => true,
                    'totalPrice' => formatPrice($totalPrice),
                    'orders' => $orders
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm trong giỏ hàng'
                ]);
            }
        } catch (PDOException $e) {
            error_log("Update quantity error: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật số lượng'
            ]);
        }
        exit;
    }
}

function deleteOrder($conn)
{
    if (!isset($_POST['action']) || $_POST['action'] !== 'delete' || !isset($_POST['id'])) {
        return;
    }

    try {
        $order_details_id = $_POST['id'];
        $user_id = $_SESSION['user_id'];

        // Xác thực order thuộc về user hiện tại
        $stmt = $conn->prepare("
            SELECT od.id 
            FROM order_details od
            JOIN orders o ON od.order_id = o.id
            WHERE od.id = ? AND o.user_id = ? AND o.status = 'pending'
        ");
        $stmt->execute([$order_details_id, $user_id]);

        if ($stmt->fetch()) {
            // Xóa order detail
            $stmt = $conn->prepare("DELETE FROM order_details WHERE id = ?");
            $stmt->execute([$order_details_id]);

            // Lấy danh sách orders mới
            $orders = getOrders($conn);
            $totalPrice = getTotalOrders($orders);

            echo json_encode([
                'success' => true,
                'orders' => $orders,
                'totalPrice' => $totalPrice
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm trong giỏ hàng'
            ]);
        }
    } catch (PDOException $e) {
        error_log("Delete order error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi xóa sản phẩm'
        ]);
    }
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function getCurrentUser($conn)
{
    if (isLoggedIn()) {
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

/**
 * Chuyển đổi trạng thái đơn hàng sang tiếng Việt
 */
function getOrderStatus($status)
{
    $statusMap = [
        'pending' => 'Chờ xác nhận',
        'processing' => 'Đang xử lý',
        'shipping' => 'Đang giao hàng',
        'completed' => 'Hoàn thành',
        'cancelled' => 'Đã hủy'
    ];
    return $statusMap[$status] ?? $status;
}

/**
 * Lấy class CSS cho từng trạng thái
 */
function getOrderStatusClass($status)
{
    $classMap = [
        'pending' => 'status-pending',
        'processing' => 'status-processing',
        'shipping' => 'status-shipping',
        'completed' => 'status-completed',
        'cancelled' => 'status-cancelled'
    ];

    return $classMap[$status] ?? 'status-pending';
}

function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function getProductStatus($status)
{
    $statusMap = [
        'active' => 'Đang bán',
        'inactive' => 'Ngừng bán',
        'out_of_stock' => 'Hết hàng'
    ];
    return $statusMap[$status] ?? 'Không xác định';
}

function getCartCount($conn)
{
    if (!isset($_SESSION['user_id'])) return 0;

    $user_id = $_SESSION['user_id'];
    $sql = "SELECT SUM(od.quantity) as total 
            FROM orders o 
            JOIN order_details od ON o.id = od.order_id 
            WHERE o.user_id = ? AND o.status = 'pending'";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
    $result = $stmt->fetch();

    return $result['total'] ?? 0;
}

function addToCart($conn, $product_id, $quantity = 1)
{
    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    try {
        $conn->beginTransaction();

        $user_id = $_SESSION['user_id'];

        // Kiểm tra đơn hàng pending
        $stmt = $conn->prepare("SELECT id FROM orders WHERE user_id = ? AND status = 'pending' LIMIT 1");
        $stmt->execute([$user_id]);
        $order = $stmt->fetch();

        if (!$order) {
            // Tạo đơn hàng mới
            $stmt = $conn->prepare("INSERT INTO orders (user_id, status, created_at) VALUES (?, 'pending', NOW())");
            $stmt->execute([$user_id]);
            $order_id = $conn->lastInsertId();
        } else {
            $order_id = $order['id'];
        }

        // Lấy giá sản phẩm
        $stmt = $conn->prepare("SELECT price, stock FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();

        if (!$product) {
            throw new Exception("Sản phẩm không tồn tại");
        }

        if ($product['stock'] < $quantity) {
            throw new Exception("Số lượng sản phẩm trong kho không đủ");
        }

        // Kiểm tra sản phẩm trong giỏ hàng
        $stmt = $conn->prepare("SELECT id, quantity FROM order_details WHERE order_id = ? AND product_id = ?");
        $stmt->execute([$order_id, $product_id]);
        $orderDetail = $stmt->fetch();

        if ($orderDetail) {
            // Cập nhật số lượng
            $newQuantity = $orderDetail['quantity'] + $quantity;
            $stmt = $conn->prepare("UPDATE order_details SET quantity = ? WHERE id = ?");
            $stmt->execute([$newQuantity, $orderDetail['id']]);
        } else {
            // Thêm sản phẩm mới
            $stmt = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$order_id, $product_id, $quantity, $product['price']]);
        }

        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollBack();
        error_log("Add to cart error: " . $e->getMessage());
        return false;
    }
}
function uploadImage($file)
{

    if (isset($file) && $file['error'] === 0) {

        $imageName = $file['name'];
        $imageTmpName = $file['tmp_name'];
        $imageSize = $file['size'];
        $imageError = $file['error'];

        if ($imageError === 0) {

            if ($imageSize < 50000000) {

                $imageExt = pathinfo($imageName, PATHINFO_EXTENSION);
                $imageExtLower = strtolower($imageExt);


                $allowed = array('jpg', 'jpeg', 'png', 'gif');

                if (in_array($imageExtLower, $allowed)) {

                    $newImageName = uniqid('', true) . '.' . $imageExtLower;

                    $uploadDir = '../image/';
                    $uploadPath = $uploadDir . $newImageName;
                    $imagePath = './image/' . $newImageName;

                    if (move_uploaded_file($imageTmpName, $uploadPath)) {
                        return $imagePath;
                    } else {
                        return "Error: Unable to move the uploaded file.";
                    }
                } else {
                    return "Error: Invalid file type. Only JPG, JPEG, PNG, GIF are allowed.";
                }
            } else {
                return "Error: File size is too large. Max allowed size is 5MB.";
            }
        } else {
            return "Error: There was an error uploading the file.";
        }
    } else {
        return "Error: No file uploaded or error during upload.";
    }
}
