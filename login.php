<?php
/**
 * CalmMind Consultation - User Login
 * ICT726 Assignment 4
 */

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

// Redirect if already logged in
if (isLoggedIn()) {
    $redirect = isAdmin() ? '/admin/dashboard.php' : '/client/dashboard.php';
    header('Location: ' . $redirect);
    exit;
}

$errors = [];
$formData = ['email' => ''];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['email'] = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Server-side validation
    if (empty($formData['email'])) {
        $errors['email'] = 'Please enter your email address.';
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if (empty($password)) {
        $errors['password'] = 'Please enter your password.';
    }

    // Verify credentials if no validation errors
    if (empty($errors)) {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare('SELECT id, full_name, password_hash, role FROM users WHERE email = ?');
        $stmt->execute([$formData['email']]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            if ($user['role'] === 'admin') {
                $errors['login'] = 'Admins must log in through the admin portal.';
            } else {
                // Login successful for clients
                loginUser((int)$user['id'], $user['full_name'], $user['role']);
                header('Location: /client/dashboard.php');
                exit;
            }
        } else {
            $errors['login'] = 'Invalid email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login – CalmMind Consultation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Log in to your CalmMind Consultation account to manage your mental health consultations.">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<a href="#main" class="skip-link">Skip to main content</a>

<header class="site-header">
    <div class="site-header-inner">
        <div class="brand">
            <img src="images/logo.png" alt="CalmMind Consultation logo" class="brand-logo-img">
            <div class="brand-text">
                <h1>CalmMind Consultation</h1>
                <p>Mental Health Consultation Platform</p>
            </div>
        </div>

        <button class="nav-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false" data-nav-toggle>☰</button>

        <nav class="main-nav" aria-label="Main navigation" data-main-nav>
            <a href="index.html">Home</a>
            <a href="about.html">About</a>
            <a href="services.html">Services</a>
            <a href="resources.html">Resources</a>
            <a href="contact.php">Contact</a>
            <a href="login.php" aria-current="page">Login</a>
            <a href="register.php" class="nav-cta">Register</a>
        </nav>
    </div>
</header>

<main id="main">
    <div class="wrapper">
        <section aria-labelledby="login-heading" class="form-grid">
            <div>
                <h2 id="login-heading">Log in to your account</h2>
                <p>
                    Access your CalmMind Consultation dashboard to view and manage your consultation requests.
                </p>

                <ul class="list-check">
                    <li>View your consultation history.</li>
                    <li>Submit new consultation requests.</li>
                    <li>Track request status updates.</li>
                </ul>

                <p>
                    Are you a staff member? <a href="admin_login.php"><strong>Admin Login</strong></a>.
                </p>
                <p>
                    Don't have an account? <a href="register.php">Register here</a>.
                </p>
            </div>

            <form method="post" action="login.php" novalidate>
                <?php if (isset($errors['login'])): ?>
                <div class="feedback feedback--error" role="alert">
                    <?= h($errors['login']) ?>
                </div>
                <?php elseif (!empty($errors)): ?>
                <div class="feedback feedback--error" role="alert">
                    Please fix the highlighted fields and try again.
                </div>
                <?php endif; ?>

                <div class="form-row">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email"
                           value="<?= h($formData['email']) ?>"
                           aria-invalid="<?= isset($errors['email']) ? 'true' : 'false' ?>"
                           required>
                    <?php if (isset($errors['email'])): ?>
                    <span class="error-msg" style="display:block;"><?= h($errors['email']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password"
                               aria-invalid="<?= isset($errors['password']) ? 'true' : 'false' ?>"
                               required>
                        <button type="button" class="toggle-password" data-toggle="#password">Show</button>
                    </div>
                    <?php if (isset($errors['password'])): ?>
                    <span class="error-msg" style="display:block;"><?= h($errors['password']) ?></span>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary">Log in</button>
            </form>
        </section>
    </div>
</main>

<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-col">
            <h3>CalmMind Consultation</h3>
            <p>Supporting mental wellbeing through accessible, ethical and person-centred consultations.</p>
        </div>
        <div class="footer-col">
            <h3>Quick links</h3>
            <ul class="footer-links">
                <li><a href="index.html">Home</a></li>
                <li><a href="services.html">Consultation services</a></li>
                <li><a href="resources.html">Self-help resources</a></li>
                <li><a href="contact.php">Book a consultation</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h3>Need immediate help?</h3>
            <p class="muted">If you are in crisis, contact local emergency services or a crisis helpline.</p>
        </div>
    </div>
</footer>

<script src="js/script.js"></script>
</body>
</html>