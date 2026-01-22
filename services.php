<?php require_once __DIR__ . '/includes/content.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Services – CalmMind Consultation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
          content="Explore CalmMind Consultation services, including individual therapy, couples support and wellbeing coaching, offered online and in-person.">
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

        <button class="nav-toggle" type="button"
                aria-label="Toggle navigation" aria-expanded="false" data-nav-toggle>☰</button>

        <nav class="main-nav" aria-label="Main navigation" data-main-nav>
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="services.php" aria-current="page">Services</a>
            <a href="resources.html">Resources</a>
            <a href="contact.php">Contact</a>
            <a href="login.php">Login</a>
            <a href="register.php" class="nav-cta">Register</a>
        </nav>
    </div>
</header>

<main id="main">
    <div class="wrapper">
        <section class="two-column" aria-labelledby="services-heading">
            <article class="card">
                <h2 id="services-heading"><?php renderContent('services', 'intro_title', 'Consultation services we offer'); ?></h2>
                <p>
                    <?php renderContent('services', 'intro', 'CalmMind Consultation provides flexible support options so you can choose how and when you receive care. All services are delivered by registered mental health professionals using evidence-informed approaches.'); ?>
                </p>

                <h3><?php renderContent('services', 'therapy_title', 'Individual therapy'); ?></h3>
                <p><?php renderContent('services', 'therapy_text', 'Support for anxiety, low mood, stress and burnout. Goal-focused sessions tailored to your pace and preferences. Available as online video, audio-only or in-person appointments.'); ?></p>

                <h3><?php renderContent('services', 'relationships_title', 'Couples and relationship support'); ?></h3>
                <p><?php renderContent('services', 'relationships_text', 'Improve communication and conflict-resolution skills. Explore patterns that impact connection and trust. LGBTQIA+ inclusive and culturally responsive practice.'); ?></p>

                <h3><?php renderContent('services', 'coaching_title', 'Wellbeing coaching'); ?></h3>
                <p><?php renderContent('services', 'coaching_text', 'Build sustainable routines for sleep, movement and self-care. Clarify values and set realistic wellbeing goals. Perfect for clients who want a structured, future-focused approach.'); ?></p>
            </article>

            <aside class="card" aria-label="How CalmMind sessions work">
                <h3><?php renderContent('services', 'sidebar_title', 'How sessions work'); ?></h3>
                <?php renderImage('services', 'sidebar_image', '', 'Services Banner'); ?>
                <ul class="list-check">
                    <li>Sessions typically run for 50 minutes.</li>
                    <li>Standard availability: Monday–Saturday, including some evenings.</li>
                    <li>Consultations are confidential and follow ethical guidelines.</li>
                    <li>
                        First session focuses on understanding your goals and
                        creating a personalised support plan.
                    </li>
                </ul>
                <p class="muted">
                    This is a prototype website for learning purposes. Pricing and
                    booking details are examples only and do not represent real services.
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