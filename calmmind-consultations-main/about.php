<?php require_once __DIR__ . '/includes/content.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About – CalmMind Consultation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Learn about CalmMind Consultation, our mission, and how we support clients and practitioners on our mental health platform.">
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
            <a href="index.php">Home</a>
            <a href="about.php" aria-current="page">About</a>
            <a href="services.php">Services</a>
            <a href="resources.html">Resources</a>
            <a href="contact.php">Contact</a>
            <a href="login.php">Login</a>
            <a href="register.php" class="nav-cta">Register</a>
        </nav>
    </div>
</header>

<main id="main">
    <div class="wrapper">
        <section class="two-column" aria-labelledby="about-heading">
            <article class="card">
                <h2 id="about-heading"><?php renderContent('about', 'intro_title', 'About CalmMind Consultation'); ?></h2>
                <p>
                    <?php renderContent('about', 'intro_text_1', 'CalmMind Consultation is a prototype mental health platform designed for individuals seeking professional guidance in a safe and confidential environment. The website demonstrates how clients might explore services, learn about practitioners and request consultations online.'); ?>
                </p>
                <p>
                    <?php renderContent('about', 'intro_text_2', 'While this site is created for learning purposes, the content reflects good practice principles such as privacy, informed consent and inclusive communication.'); ?>
                </p>

                <h3>Our guiding principles</h3>
                <ul class="list-check">
                    <li>Respect each person’s lived experience and cultural background.</li>
                    <li>Promote autonomy through informed decision-making.</li>
                    <li>Support trauma-aware, strengths-based conversations.</li>
                    <li>Use technology in ways that protect safety and confidentiality.</li>
                </ul>
            </article>

            <aside class="card" aria-label="Key facts about CalmMind">
                <h3><?php renderContent('about', 'snapshot_title', 'Platform snapshot'); ?></h3>
                <?php renderImage('about', 'snapshot_image', '', 'About Banner'); ?>
                <ul class="list-check">
                    <li><?php renderContent('about', 'snapshot_point_1', 'Prototype for a mental health consultation platform.'); ?></li>
                    <li><?php renderContent('about', 'snapshot_point_2', 'Showcases accessible navigation and responsive design.'); ?></li>
                    <li><?php renderContent('about', 'snapshot_point_3', 'Demonstrates ethical use of digital media and AI-assisted content.'); ?></li>
                    <li><?php renderContent('about', 'snapshot_point_4', 'Designed with screen-reader and keyboard users in mind.'); ?></li>
                </ul>
                <p class="muted">
                    Note: This site does not collect or store real personal data. Any forms you complete stay within
                    your browser for demonstration only.
                </p>
            </aside>
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
                <li><a href="index.php">Home</a></li>
                <li><a href="services.php">Consultation services</a></li>
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