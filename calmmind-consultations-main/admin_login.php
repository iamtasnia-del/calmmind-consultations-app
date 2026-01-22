<?php
/**
 * CalmMind Consultation - Admin Login
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

if (isLoggedIn() && isAdmin()) {
    header('Location: /admin/dashboard.php');
    exit;
}

$errors = [];
$formData = ['email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['email'] = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($formData['email']) || !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }
    if (empty($password)) {
        $errors['password'] = 'Please enter your password.';
    }

    if (empty($errors)) {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("SELECT id, full_name, password_hash, role FROM users WHERE email = ? AND role = 'admin'");
        $stmt->execute([$formData['email']]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            loginUser((int)$user['id'], $user['full_name'], $user['role']);
            header('Location: /admin/dashboard.php');
            exit;
        } else {
            $errors['login'] = 'Invalid admin credentials or unauthorized access.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login – CalmMind Consultation</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="site-header">
    <div class="site-header-inner">
        <div class="brand">
            <img src="images/logo.png" alt="Logo" class="brand-logo-img">
            <div class="brand-text">
                <h1>CalmMind Consultation</h1>
                <p>Admin Portal</p>
            </div>
        </div>
    </div>
</header>
<main id="main">
    <div class="wrapper">
        <section class="form-grid">
            <div>
                <h2>Admin Login</h2>
                <p>Please enter your administrative credentials to access the dashboard.</p>
            </div>
            <form method="post" action="admin_login.php" novalidate>
                <?php if (isset($errors['login'])): ?>
                <div class="feedback feedback--error"><?= htmlspecialchars($errors['login']) ?></div>
                <?php endif; ?>
                <div class="form-row">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($formData['email']) ?>" required>
                </div>
                <div class="form-row">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" required>
                        <button type="button" class="toggle-password" data-toggle="#password">Show</button>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Login as Admin</button>
            </form>
        </section>
    </div>
</main>
</body>
</html>