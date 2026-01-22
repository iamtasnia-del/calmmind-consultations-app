<?php
/**
 * CalmMind Consultation - User Registration
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
$success = '';
$formData = [
    'full_name' => '',
    'email' => ''
];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['full_name'] = trim($_POST['full_name'] ?? '');
    $formData['email'] = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Server-side validation
    if (empty($formData['full_name'])) {
        $errors['full_name'] = 'Please enter your full name.';
    } elseif (strlen($formData['full_name']) < 2) {
        $errors['full_name'] = 'Name must be at least 2 characters.';
    } elseif (strlen($formData['full_name']) > 100) {
        $errors['full_name'] = 'Name must not exceed 100 characters.';
    }

    if (empty($formData['email'])) {
        $errors['email'] = 'Please enter your email address.';
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if (empty($password)) {
        $errors['password'] = 'Please enter a password.';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters.';
    }

    if ($password !== $confirmPassword) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    // Check if email already exists
    if (empty($errors['email'])) {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$formData['email']]);
        if ($stmt->fetch()) {
            $errors['email'] = 'This email address is already registered.';
        }
    }

    // If no errors, create the account
    if (empty($errors)) {
        $pdo = getDbConnection();
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare('INSERT INTO users (full_name, email, password_hash, role) VALUES (?, ?, ?, ?)');
        $stmt->execute([$formData['full_name'], $formData['email'], $passwordHash, 'client']);

        // Log the user in automatically
        $userId = $pdo->lastInsertId();
        loginUser((int)$userId, $formData['full_name'], 'client');

        // Redirect to client dashboard
        header('Location: /client/dashboard.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register – CalmMind Consultation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Create your CalmMind Consultation account to book mental health consultations and manage your appointments.">
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
            <a href="login.php">Login</a>
            <a href="register.php" class="nav-cta" aria-current="page">Register</a>
        </nav>
    </div>
</header>

<main id="main">
    <div class="wrapper">
        <section aria-labelledby="register-heading" class="form-grid">
            <div>
                <h2 id="register-heading">Create your account</h2>
                <p>
                    Join CalmMind Consultation to request appointments with our registered mental health professionals
                    and track your consultation history.
                </p>

                <ul class="list-check">
                    <li>Submit consultation requests online.</li>
                    <li>Track your consultation status.</li>
                    <li>Secure and confidential access.</li>
                </ul>

                <p>
                    Already have an account? <a href="login.php">Log in here</a>.
                </p>
            </div>

            <form method="post" action="register.php" novalidate>
                <?php if (!empty($errors)): ?>
                <div class="feedback feedback--error" role="alert">
                    Please fix the highlighted fields and try again.
                </div>
                <?php endif; ?>

                <div class="form-row">
                    <label for="full_name">Full name</label>
                    <input type="text" id="full_name" name="full_name"
                           value="<?= h($formData['full_name']) ?>"
                           aria-invalid="<?= isset($errors['full_name']) ? 'true' : 'false' ?>"
                           required>
                    <?php if (isset($errors['full_name'])): ?>
                    <span class="error-msg" style="display:block;"><?= h($errors['full_name']) ?></span>
                    <?php endif; ?>
                </div>

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
                    <span class="help-text">Minimum 8 characters.</span>
                    <?php if (isset($errors['password'])): ?>
                    <span class="error-msg" style="display:block;"><?= h($errors['password']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <label for="confirm_password">Confirm password</label>
                    <div class="password-wrapper">
                        <input type="password" id="confirm_password" name="confirm_password"
                               aria-invalid="<?= isset($errors['confirm_password']) ? 'true' : 'false' ?>"
                               required>
                        <button type="button" class="toggle-password" data-toggle="#confirm_password">Show</button>
                    </div>
                    <?php if (isset($errors['confirm_password'])): ?>
                    <span class="error-msg" style="display:block;"><?= h($errors['confirm_password']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <p class="privacy-notice" style="font-size: 0.85rem; color: #9ca3af; margin: 0;">
                        <strong>Privacy Notice:</strong> Your personal information is collected to create your account
                        and manage consultation requests. We do not share your data with third parties.
                        Your password is securely hashed and never stored in plain text.
                    </p>
                </div>

                <button type="submit" class="btn btn-primary">Create account</button>
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