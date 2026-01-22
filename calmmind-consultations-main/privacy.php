<?php require_once __DIR__ . '/includes/content.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Privacy Policy – CalmMind Consultation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Privacy policy for CalmMind Consultation platform.">
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

        <nav class="main-nav">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="services.php">Services</a>
            <a href="resources.html">Resources</a>
            <a href="contact.php">Contact</a>
            <a href="login.php">Login</a>
        </nav>
    </div>
</header>

<main id="main">
    <div class="wrapper">
        <section class="card" style="margin-top: 2rem; padding: 2rem;">
            <h2>Privacy Policy</h2>
            <div class="content">
                <?php renderContent('privacy', 'content', 'Your personal information is handled in accordance with our privacy policy. We are committed to protecting your privacy and ensuring that your personal data is handled securely and responsibly.'); ?>
            </div>
            <?php renderImage('privacy', 'banner', '', 'Privacy Banner'); ?>
        </section>
    </div>
</main>

<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-col">
            <h3>CalmMind Consultation</h3>
            <p>Supporting mental wellbeing through accessible, ethical and person-centred consultations.</p>
        </div>
    </div>
</footer>

<script src="js/script.js"></script>
</body>
</html>