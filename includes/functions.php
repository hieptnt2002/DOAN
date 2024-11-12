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
        return false;
    }
}

function loginUser($conn, $email, $password)
{
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return true;
    }
    return false;
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

// function getTotalArticles($conn) {
//     $stmt = $conn->prepare("SELECT COUNT(*) as total FROM articles WHERE status = 'published'");
//     $stmt->execute();
//     $result = $stmt->fetch(PDO::FETCH_ASSOC);
//     return $result['total'];
// }

// function getFeaturedProducts($conn, $limit = 3) {
//     $stmt = $conn->prepare("SELECT * FROM products 
//             WHERE featured = 1 
//             ORDER BY RAND() 
//             LIMIT :limit");

//     $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
//     $stmt->execute();

//     return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }

function getOrders($conn)
{

    // if (isset($_SESSION['user_id'])) {
    $userId = 1;
    $stmt = $conn->prepare("
            SELECT 
                o.id AS order_id,
                o.total_amount,
                p.id AS product_id,
                p.name AS product_name,
                od.quantity,
                od.price AS order_price,
                od.id AS order_details_id,
                total_amount
            FROM 
                orders o
            INNER JOIN 
                order_details od ON o.id = od.order_id
            INNER JOIN 
                products p ON od.product_id = p.id
            WHERE 
                o.user_id = :user_id;

            ");
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }
    // return [];
}
function getTotalOrders($orders)
{
    $sum = 0;
    foreach ($orders as $order) {
        $sum += $order['order_price'] * $order['quantity'];
    }
    return $sum;
}

function updateQuantityOrder($conn)
{
    if (isset($_POST['action']) && $_POST['action'] == 'update_quantity' && isset($_POST['order_id']) && isset($_POST['quantity'])) {
        $orderId = intval($_POST['order_id']);
        $quantity = max(1, intval($_POST['quantity']));

        $sql = "UPDATE order_details SET quantity = :quantity WHERE order_id = :order_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":order_id", $orderId);
        $stmt->execute();

        $orders = getOrders($conn);
        $totalPrice = getTotalOrders($orders);

        echo json_encode([
            'totalPrice' => formatPrice($totalPrice),
            'orders' => $orders
        ]);
        exit;
    }
}

function deleteOrder($conn)
{
    if (isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['id'])) {
        $id = intval($_POST['id']);

        $sql = "DELETE FROM order_details WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $orders = getOrders($conn);
        $totalPrice = getTotalOrders($orders);

        echo json_encode([
            'totalPrice' => formatPrice($totalPrice),
            'orders' => $orders
        ]);

        exit;
    }
}
