<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['title'] ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', system-ui; }
        body { background: linear-gradient(135deg, #0f172a, #1e293b); color: #f1f5f9; min-height: 100vh; }
        
        .top-bar { display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 3rem; background: rgba(0,0,0,0.5); backdrop-filter: blur(10px); flex-wrap: wrap; gap: 1rem; }
        .lang-switch { display: flex; gap: 1rem; }
        .lang-btn { background: #3b82f6; padding: 0.5rem 1rem; border-radius: 2rem; color: white; text-decoration: none; font-weight: bold; transition: 0.2s; border: none; cursor: pointer; }
        .lang-btn:hover { background: #2563eb; transform: scale(1.05); }
        .nav { display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap; }
        .nav a, .nav button { color: #cbd5e1; text-decoration: none; background: none; border: none; font-size: 1rem; cursor: pointer; }
        .nav a:hover, .nav button:hover { color: #60a5fa; }
        
        .hero { text-align: center; padding: 3rem 2rem 2rem; }
        h1 { font-size: 4rem; background: linear-gradient(135deg, #a5f3fc, #818cf8); -webkit-background-clip: text; background-clip: text; color: transparent; }
        
        .cards { display: flex; flex-wrap: wrap; justify-content: center; gap: 2rem; padding: 2rem; max-width: 1400px; margin: auto; }
        .card { background: rgba(30, 41, 59, 0.8); backdrop-filter: blur(8px); border-radius: 2rem; padding: 2rem; width: 300px; text-align: center; transition: all 0.3s; border: 1px solid rgba(255,255,255,0.1); cursor: pointer; }
        .card:hover { transform: translateY(-10px); box-shadow: 0 25px 30px -12px rgba(0,0,0,0.5); border-color: #3b82f6; }
        .card h3 { font-size: 1.8rem; margin-bottom: 0.5rem; color: #93c5fd; }
        .card .price { font-size: 1.5rem; color: #fbbf24; margin: 0.5rem 0; }
        .card .duration { color: #94a3b8; margin-bottom: 1rem; }
        
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); justify-content: center; align-items: center; z-index: 1000; }
        .modal-content { background: #1e293b; padding: 2rem; border-radius: 2rem; max-width: 500px; width: 90%; position: relative; }
        .close-modal { position: absolute; top: 1rem; right: 1.5rem; font-size: 2rem; cursor: pointer; color: #94a3b8; }
        .close-modal:hover { color: #ef4444; }
        
        .auth-container { background: rgba(30, 41, 59, 0.95); backdrop-filter: blur(10px); border-radius: 2rem; padding: 2rem; max-width: 400px; margin: 4rem auto; }
        .auth-container input { width: 100%; padding: 0.8rem; margin: 0.5rem 0; border-radius: 1rem; border: none; background: #0f172a; color: white; }
        .auth-container button { background: #3b82f6; color: white; padding: 0.8rem; border: none; border-radius: 2rem; width: 100%; cursor: pointer; font-size: 1rem; }
        .auth-container button:hover { background: #2563eb; }
        
        .about-section { background: rgba(15, 23, 42, 0.7); padding: 3rem 2rem; margin-top: 3rem; text-align: center; border-top: 1px solid #334155; }
        .about-section h2 { font-size: 2.5rem; margin-bottom: 1.5rem; color: #a5f3fc; }
        .about-section p { max-width: 800px; margin: 0 auto 1rem; line-height: 1.6; }
        
        .admin-section { background: #0f172a; margin: 2rem; padding: 2rem; border-radius: 2rem; }
        .admin-form { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 2rem; }
        .admin-form input, .admin-form textarea { padding: 0.7rem; border-radius: 1rem; border: none; background: #1e293b; color: white; flex: 1; min-width: 150px; }
        .admin-form button { background: #10b981; padding: 0.7rem 1.5rem; border: none; border-radius: 2rem; cursor: pointer; }
        .admin-item { background: #1e293b; padding: 1rem; margin: 0.5rem 0; border-radius: 1rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
        .admin-item button { background: #ef4444; border: none; padding: 0.4rem 1rem; border-radius: 1rem; cursor: pointer; margin-left: 0.5rem; }
        .admin-item button.edit-btn { background: #3b82f6; }
        
        .footer { text-align: center; padding: 2rem; border-top: 1px solid #334155; }
        @media (max-width: 700px) { .top-bar { flex-direction: column; } .cards .card { width: 100%; } }
    </style>
</head>
<body>

<div class="top-bar">
    <div class="lang-switch">
        <a href="?lang=en" class="lang-btn">EN</a>
        <a href="?lang=ua" class="lang-btn">UA</a>
    </div>
    <div class="nav">
        <a href="index.php"><?= $t['title'] ?></a>
        <?php if(isset($_SESSION['user'])): ?>
            <span><?= $t['welcome'] ?> <?= $_SESSION['user'] ?>!</span>
            <a href="?logout=1" onclick="return confirm('Logout?')"><?= $t['logout'] ?></a>
            <?php if($_SESSION['user'] === 'admin'): ?>
                <button onclick="toggleAdmin()"><?= $t['admin_panel'] ?></button>
            <?php endif; ?>
        <?php else: ?>
            <button onclick="showLogin()"><?= $t['login'] ?></button>
            <button onclick="showRegister()"><?= $t['register'] ?></button>
        <?php endif; ?>
    </div>
</div>

<div class="hero">
    <h1>A and I Studio</h1>
    <p style="margin-top: 1rem; font-size: 1.2rem;"><?= $lang == 'ua' ? 'Втілюємо ідеї в цифрову реальність' : 'Turning ideas into digital reality' ?></p>
</div>

<div class="cards" id="products-container">
    <?php
    $products = firebaseRequest('products', 'GET') ?: [];
    foreach ($products as $id => $product):
    ?>
    <div class="card" onclick="showProductDetails('<?= $id ?>', '<?= addslashes($product['name']) ?>', '<?= addslashes($product['description']) ?>', '<?= $product['price'] ?>', '<?= $product['duration'] ?>')">
        <h3><?= htmlspecialchars($product['name']) ?></h3>
        <div class="price">💰 <?= htmlspecialchars($product['price']) ?> ₴</div>
        <div class="duration">⏱️ <?= htmlspecialchars($product['duration']) ?></div>
        <p style="margin-top: 0.5rem;"><?= htmlspecialchars(substr($product['description'], 0, 60)) ?>...</p>
        <button class="lang-btn" style="margin-top: 1rem; background:#4f46e5;" onclick="event.stopPropagation(); showProductDetails('<?= $id ?>', '<?= addslashes($product['name']) ?>', '<?= addslashes($product['description']) ?>', '<?= $product['price'] ?>', '<?= $product['duration'] ?>')"><?= $t['details'] ?> →</button>
    </div>
    <?php endforeach; ?>
    <?php if(empty($products)): ?>
        <p style="text-align:center; width:100%;"><?= $lang == 'ua' ? 'Немає товарів. Додайте через адмін-панель.' : 'No products. Add via admin panel.' ?></p>
    <?php endif; ?>
</div>

<div id="productModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <h2 id="modal-title"></h2>
        <p id="modal-description" style="margin: 1rem 0; line-height: 1.6;"></p>
        <p><strong>💰 <?= $t['price'] ?>:</strong> <span id="modal-price"></span> ₴</p>
        <p><strong>⏱️ <?= $t['duration'] ?>:</strong> <span id="modal-duration"></span></p>
        <button class="lang-btn" style="margin-top: 1rem; background:#10b981;" onclick="contactUs()">📞 <?= $t['contact_us'] ?></button>
    </div>
</div>

<div id="loginModal" class="modal">
    <div class="auth-container" style="margin:0;">
        <span class="close-modal" onclick="closeLoginModal()">&times;</span>
        <h2><?= $t['login'] ?></h2>
        <form method="POST">
            <input type="hidden" name="action" value="login">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit"><?= $t['login'] ?></button>
        </form>
    </div>
</div>

<div id="registerModal" class="modal">
    <div class="auth-container" style="margin:0;">
        <span class="close-modal" onclick="closeRegisterModal()">&times;</span>
        <h2><?= $t['register'] ?></h2>
        <form method="POST">
            <input type="hidden" name="action" value="register">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit"><?= $t['register'] ?></button>
        </form>
    </div>
</div>

<?php if(isset($_SESSION['user']) && $_SESSION['user'] === 'admin'): ?>
<div id="adminPanel" style="display:none;">
    <div class="admin-section">
        <h2><?= $t['add_product'] ?></h2>
        <form method="POST" action="admin.php" class="admin-form">
            <input type="hidden" name="action" value="add">
            <input type="text" name="name" placeholder="Назва товару" required>
            <textarea name="description" placeholder="Опис" rows="2" required></textarea>
            <input type="text" name="price" placeholder="Ціна (грн)" required>
            <input type="text" name="duration" placeholder="Термін (днів)" required>
            <button type="submit">+ <?= $t['add_product'] ?></button>
        </form>
        
        <h3>📦 <?= $lang == 'ua' ? 'Управління товарами' : 'Manage Products' ?></h3>
        <?php foreach (firebaseRequest('products', 'GET') ?: [] as $id => $product): ?>
        <div class="admin-item">
            <span><strong><?= htmlspecialchars($product['name']) ?></strong> — <?= htmlspecialchars($product['price']) ?> ₴</span>
            <div>
                <form method="POST" action="admin.php" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <button type="submit" onclick="return confirm('Delete?')">🗑️ <?= $t['delete'] ?></button>
                </form>
                <button class="edit-btn" onclick="editProduct('<?= $id ?>', '<?= addslashes($product['name']) ?>', '<?= addslashes($product['description']) ?>', '<?= $product['price'] ?>', '<?= $product['duration'] ?>')">✏️ <?= $t['edit'] ?></button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeEditModal()">&times;</span>
        <h2><?= $t['edit'] ?></h2>
        <form method="POST" action="admin.php">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit-id">
            <input type="text" name="name" id="edit-name" placeholder="Назва" style="width:100%; padding:0.5rem; margin:0.5rem 0;" required>
            <textarea name="description" id="edit-description" placeholder="Опис" rows="3" style="width:100%; padding:0.5rem; margin:0.5rem 0;" required></textarea>
            <input type="text" name="price" id="edit-price" placeholder="Ціна" style="width:100%; padding:0.5rem; margin:0.5rem 0;" required>
            <input type="text" name="duration" id="edit-duration" placeholder="Термін" style="width:100%; padding:0.5rem; margin:0.5rem 0;" required>
            <button type="submit" style="background:#10b981;">💾 <?= $t['save'] ?></button>
        </form>
    </div>
</div>
<?php endif; ?>

<div class="about-section">
    <h2>📖 <?= $t['about_us'] ?></h2>
    <p><?= $lang == 'ua' ? 'Ми — команда професіоналів, що створює сайти, застосунки та дизайн з 2019 року. Клієнтоорієнтованість, інновації та естетика — наші головні принципи.' : 'We are a team of pros creating websites, apps & design since 2019. Client focus, innovation & aesthetics are our main principles.' ?></p>
    <p><?= $lang == 'ua' ? '📍 Київ, Україна | 📧 hello@aandistudio.com | 📞 +380 68 123 4567' : '📍 Kyiv, Ukraine | 📧 hello@aandistudio.com | 📞 +38 068 123 4567' ?></p>
</div>

<div class="footer">
    <p>© 2026 A and I Studio — <?= $lang == 'ua' ? 'Ваша довіра — наш пріоритет' : 'Your trust is our priority' ?></p>
</div>

<script>
function showProductDetails(id, name, desc, price, duration) {
    document.getElementById('modal-title').innerText = name;
    document.getElementById('modal-description').innerText = desc;
    document.getElementById('modal-price').innerText = price;
    document.getElementById('modal-duration').innerText = duration;
    document.getElementById('productModal').style.display = 'flex';
}
function closeModal() { document.getElementById('productModal').style.display = 'none'; }
function showLogin() { document.getElementById('loginModal').style.display = 'flex'; }
function closeLoginModal() { document.getElementById('loginModal').style.display = 'none'; }
function showRegister() { document.getElementById('registerModal').style.display = 'flex'; }
function closeRegisterModal() { document.getElementById('registerModal').style.display = 'none'; }
function toggleAdmin() { 
    let panel = document.getElementById('adminPanel');
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
}
function editProduct(id, name, desc, price, duration) {
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-description').value = desc;
    document.getElementById('edit-price').value = price;
    document.getElementById('edit-duration').value = duration;
    document.getElementById('editModal').style.display = 'flex';
}
function closeEditModal() { document.getElementById('editModal').style.display = 'none'; }
function contactUs() { alert("<?= $lang == 'ua' ? 'Зв\'яжіться з нами: hello@aandistudio.com або +380 68 123 4567' : 'Contact us: hello@aandistudio.com or +38 068 123 4567' ?>"); }
window.onclick = function(e) { if (e.target.classList.contains('modal')) e.target.style.display = 'none'; }
<?php if(isset($_GET['logout'])): session_destroy(); echo "window.location.href='index.php';"; endif; ?>
</script>
</body>
</html>
