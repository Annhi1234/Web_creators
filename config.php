<?php
session_start();

define('FIREBASE_DB_URL', 'https://a-and-i-site-default-rtdb.firebaseio.com/');

define('FIREBASE_SECRET', 'serviceAccountKey.json');

function firebaseRequest($path, $method = 'GET', $data = null) {
    $url = FIREBASE_DB_URL . $path . '.json?auth=' . FIREBASE_SECRET;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    }
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

// Мови
$langs = ['en', 'ua'];
$lang = isset($_GET['lang']) && in_array($_GET['lang'], $langs) ? $_GET['lang'] : 'en';
$_SESSION['lang'] = $lang;

$translations = [
    'en' => [
        'title' => 'A and I Studio',
        'web_dev' => 'Website Creation',
        'app_dev' => 'App Creation',
        'style_dev' => 'Styling Service',
        'about_us' => 'About Us',
        'register' => 'Register',
        'login' => 'Login',
        'logout' => 'Logout',
        'welcome' => 'Welcome',
        'details' => 'Details',
        'price' => 'Price',
        'duration' => 'Duration',
        'contact_us' => 'Contact Us',
        'admin_panel' => 'Admin Panel',
        'add_product' => 'Add Product',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'save' => 'Save',
        'cancel' => 'Cancel'
    ],
    'ua' => [
        'title' => 'A and I Studio',
        'web_dev' => 'Створення сайтів',
        'app_dev' => 'Створення програм',
        'style_dev' => 'Розробка стилю',
        'about_us' => 'Про нас',
        'register' => 'Реєстрація',
        'login' => 'Вхід',
        'logout' => 'Вихід',
        'welcome' => 'Ласкаво просимо',
        'details' => 'Детальніше',
        'price' => 'Ціна',
        'duration' => 'Термін',
        'contact_us' => 'Зв\'язатися з нами',
        'admin_panel' => 'Панель адміна',
        'add_product' => 'Додати товар',
        'edit' => 'Редагувати',
        'delete' => 'Видалити',
        'save' => 'Зберегти',
        'cancel' => 'Скасувати'
    ]
];
$t = $translations[$lang];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'register') {
            $username = trim($_POST['username']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $users = firebaseRequest('users', 'GET') ?: [];
            if (!isset($users[$username])) {
                firebaseRequest('users/' . $username, 'PUT', ['password' => $password, 'created_at' => date('Y-m-d H:i:s')]);
                $_SESSION['user'] = $username;
                header('Location: index.php');
                exit;
            }
        } elseif ($_POST['action'] === 'login') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            $users = firebaseRequest('users', 'GET') ?: [];
            if (isset($users[$username]) && password_verify($password, $users[$username]['password'])) {
                $_SESSION['user'] = $username;
                header('Location: index.php');
                exit;
            }
        }
    }
}
?>
