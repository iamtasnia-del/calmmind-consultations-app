<?php
/**
 * CalmMind Consultation - Contact Form
 * ICT726 Assignment 4
 */

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/content.php';

// Helper function for escaping output
function h(?string $string): string {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

$errors = [];
$success = false;
$formData = [
    'full_name' => '',
    'email' => '',
    'topic' => '',
    'message' => ''
];

// Valid topics
$validTopics = [
    'anxiety' => 'Anxiety & stress',
    'relationship' => 'Relationships',
    'depression' => 'Low mood / depression',
    'self-esteem' => 'Self-esteem',
    'trauma' => 'Trauma-informed support'
];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['full_name'] = trim($_POST['fullName'] ?? '');
    $formData['email'] = trim($_POST['email'] ?? '');
    $formData['topic'] = trim($_POST['topic'] ?? '');
    $formData['message'] = trim($_POST['message'] ?? '');

    // Server-side validation
    if (empty($formData['full_name'])) {
        $errors['fullName'] = 'Please enter your full name.';
    } elseif (strlen($formData['full_name']) < 2) {
        $errors['fullName'] = 'Name must be at least 2 characters.';
    } elseif (strlen($formData['full_name']) > 100) {
        $errors['fullName'] = 'Name must not exceed 100 characters.';
    }

    if (empty($formData['email'])) {
        $errors['email'] = 'Please enter your email address.';
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if (empty($formData['topic'])) {
        $errors['topic'] = 'Please choose a consultation topic.';
    } elseif (!array_key_exists($formData['topic'], $validTopics)) {
        $errors['topic'] = 'Please select a valid consultation topic.';
    }

    if (empty($formData['message'])) {
        $errors['message'] = 'Please provide a brief summary of your concern.';
    } elseif (strlen($formData['message']) < 10) {
        $errors['message'] = 'Please provide more details (at least 10 characters).';
    } elseif (strlen($formData['message']) > 2000) {
        $errors['message'] = 'Message is too long (maximum 2000 characters).';
    }

    // If no errors, save to database
    if (empty($errors)) {
        try {
            $pdo = getDbConnection();
            $stmt = $pdo->prepare('INSERT INTO contact_messages (full_name, email, topic, message) VALUES (?, ?, ?, ?)');
            $stmt->execute([
                $formData['full_name'],
                $formData['email'],
                $validTopics[$formData['topic']], // Store the full topic name
                $formData['message']
            ]);

            $success = true;
            $formData = ['full_name' => '', 'email' => '', 'topic' => '', 'message' => ''];
        } catch (PDOException $e) {
            $errors['database'] = 'Sorry, there was an error submitting your request. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact – CalmMind Consultation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Book a mental health consultation with CalmMind by submitting a confidential request through our secure contact form.">
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
            <a href="about.php">About</a>
            <a href="services.php">Services</a>
            <a href="resources.html">Resources</a>
            <a href="contact.php" aria-current="page">Contact</a>
            <a href="login.php">Login</a>
            <a href="register.php" class="nav-cta">Register</a>
        </nav>
    </div>
</header>

<main id="main">
    <div class="wrapper">

        <section aria-labelledby="contact-heading" class="form-grid">
            <div>
                <h2 id="contact-heading">Book a consultation</h2>
                <p>
                    <?php renderContent('contact', 'intro', 'Use the form to request an appointment with one of our registered mental health professionals. Your details are securely stored and kept confidential.'); ?>
                </p>

                <ul class="list-check">
                    <li>Confidential & secure submission.</li>
                    <li>Choose your support topic.</li>
                    <li>Receive a response within one working day.</li>
                </ul>

                <p>
                    Prefer email or social media? You can also contact us at
                    <a href="mailto:hello@calmmind.example">hello@calmmind.example</a>,
                    or follow <a href="#">@CalmMindConsultation</a> on Instagram and
                    <a href="#">CalmMind Consultation</a> on Facebook for wellbeing updates.
                </p>
                <?php renderImage('contact', 'banner', '', 'Contact Banner'); ?>
            </div>

            <!-- Consultation Form -->
            <form method="post" action="contact.php" novalidate>
                <?php if ($success): ?>
                <div class="feedback feedback--success" role="alert">
                    Thank you for reaching out. A CalmMind coordinator will contact you within one working day.
                </div>
                <?php elseif (!empty($errors)): ?>
                <div class="feedback feedback--error" role="alert">
                    <?= isset($errors['database']) ? h($errors['database']) : 'Please fix the highlighted fields and try again.' ?>
                </div>
                <?php endif; ?>

                <div class="form-row">
                    <label for="fullName">Full name</label>
                    <input type="text" id="fullName" name="fullName"
                           value="<?= h($formData['full_name']) ?>"
                           aria-invalid="<?= isset($errors['fullName']) ? 'true' : 'false' ?>"
                           required>
                    <?php if (isset($errors['fullName'])): ?>
                    <span class="error-msg" style="display:block;"><?= h($errors['fullName']) ?></span>
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
                    <label for="topic">Consultation topic</label>
                    <select id="topic" name="topic"
                            aria-invalid="<?= isset($errors['topic']) ? 'true' : 'false' ?>"
                            required>
                        <option value="">Select a topic...</option>
                        <?php foreach ($validTopics as $value => $label): ?>
                        <option value="<?= h($value) ?>" <?= $formData['topic'] === $value ? 'selected' : '' ?>><?= h($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['topic'])): ?>
                    <span class="error-msg" style="display:block;"><?= h($errors['topic']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <label for="message">Brief summary of your concern</label>
                    <textarea id="message" name="message" rows="4"
                              aria-invalid="<?= isset($errors['message']) ? 'true' : 'false' ?>"
                              required><?= h($formData['message']) ?></textarea>
                    <?php if (isset($errors['message'])): ?>
                    <span class="error-msg" style="display:block;"><?= h($errors['message']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <p class="privacy-notice" style="font-size: 0.85rem; color: #9ca3af; margin: 0;">
                        <strong>Privacy Notice:</strong> Your personal information is collected to process your consultation request.
                        We do not share your data with third parties. Information submitted through this form is handled
                        in accordance with our privacy policy.
                    </p>
                </div>

                <button type="submit" class="btn btn-primary">Submit request</button>
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
                <li><a href="index.php">Home</a></li>
                <li><a href="services.php">Consultation services</a></li>
                <li><a href="resources.html">Self-help resources</a></li>
                <li><a href="contact.php">Book a consultation</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h3>Need immediate help?</h3>
            <p class="muted">
                If you are in crisis, contact local emergency services or a crisis helpline in your area.
            </p>
        </div>
    </div>
</footer>

<script src="js/script.js"></script>
</body>
</html>