<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$action = $_POST['action'] ?? '';
$products = firebaseRequest('products', 'GET') ?: [];

if ($action === 'add') {
    $newProduct = [
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'price' => $_POST['price'],
        'duration' => $_POST['duration'],
        'created_at' => date('Y-m-d H:i:s')
    ];
    firebaseRequest('products', 'POST', $newProduct);
    
} elseif ($action === 'edit') {
    $id = $_POST['id'];
    $updated = [
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'price' => $_POST['price'],
        'duration' => $_POST['duration']
    ];
    firebaseRequest('products/' . $id, 'PATCH', $updated);
    
} elseif ($action === 'delete') {
    $id = $_POST['id'];
    firebaseRequest('products/' . $id, 'DELETE');
}

header('Location: index.php');
exit;
?>
