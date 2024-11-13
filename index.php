<?php
require_once 'config/db.php';
require_once 'includes/functions.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nước Hoa</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
   
    <link rel="stylesheet" href="main.css?v=1.0">
    <link rel="stylesheet" href="./css/style.css?v=1.0">
    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

    <?php include 'includes/header.php'; ?>

    
    <?php include 'includes/slider.php'; ?>

    
    <!-- <?php include 'includes/product-list.php'; ?> -->
    
  
    <?php include 'includes/male-products.php'; ?>
    
    
    <?php include 'includes/pagination.php'; ?>
    
  
    <?php include 'includes/female-products.php'; ?>
  

    <?php include 'includes/pagination.php'; ?>

   
    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
</body>
</html>
