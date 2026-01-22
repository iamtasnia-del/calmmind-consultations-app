<?php
/**
 * CalmMind Consultation - Submit Consultation Request
 * ICT726 Assignment 4
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

// Require client login
requireLogin();

$errors = [];
$success = false;
$formData = [
    'topic' => '',
    'message' => ''
];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['topic'] = trim($_POST['topic'] ?? '');
    $formData['message'] = trim($_POST['message'] ?? '');

    // Valid topics
    $validTopics = [
        'Anxiety & stress',
        'Relationships',
        'Low mood / depression',
        'Self-esteem',
        'Trauma-informed support',
        'Other'
    ];

    // Server-side validation
    if (empty($formData['topic'])) {
        $errors['topic'] = 'Please select a consultation topic.';
    } elseif (!in_array($formData['topic'], $validTopics)) {
        $errors['topic'] = 'Please select a valid consultation topic.';
    }

    if (empty($formData['message'])) {
        $errors['message'] = 'Please provide a brief summary of your concern.';
    } elseif (strlen($formData['message']) < 10) {
        $errors['message'] = 'Please provide more details (at least 10 characters).';
    } elseif (strlen($formData['message']) > 2000) {
        $errors['message'] = 'Message is too long (maximum 2000 characters).';
    }

    // If no errors, insert the consultation
    if (empty($errors)) {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare('INSERT INTO consultations (user_id, topic, message, status) VALUES (?, ?, ?, ?)');
        $stmt->execute([getCurrentUserId(), $formData['topic'], $formData['message'], 'pending']);

        $success = true;
        $formData = ['topic' => '', 'message' => ''];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Consultation – CalmMind Consultation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Submit a new consultation request to CalmMind Consultation.">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<a href="#main" class="skip-link">Skip to main content</a>

<header class="site-header">
    <div class="site-header-inner">
        <div class="brand">
            <img src="../images/logo.png" alt="CalmMind Consultation logo" class="brand-logo-img">
            <div class="brand-text">
                <h1>CalmMind Consultation</h1>
                <p>Mental Health Consultation Platform</p>
            </div>
        </div>

        <button class="nav-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false" data-nav-toggle>☰</button>

        <nav class="main-nav" aria-label="Main navigation" data-main-nav>
            <a href="../index.html">Home</a>
            <a href="../about.html">About</a>
            <a href="../services.html">Services</a>
            <a href="../resources.html">Resources</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="../logout.php" class="nav-cta">Logout</a>
        </nav>
    </div>
</header>

<main id="main">
    <div class="wrapper">
        <section aria-labelledby="submit-heading" class="form-grid">
            <div>
                <h2 id="submit-heading">Request a consultation</h2>
                <p>
                    Submit a new consultation request. Our team will review your request and respond
                    within one working day.
                </p>

                <ul class="list-check">
                    <li>Choose your preferred support topic.</li>
                    <li>Share a brief summary of your concern.</li>
                    <li>Track your request status in your dashboard.</li>
                </ul>

                <p>
                    <a href="dashboard.php">&larr; Back to dashboard</a>
                </p>
            </div>

            <form method="post" action="submit.php" novalidate>
                <?php if ($success): ?>
                <div class="feedback feedback--success" role="alert">
                    Your consultation request has been submitted successfully.
                    <a href="dashboard.php" style="color: inherit; text-decoration: underline;">View your dashboard</a>.
                </div>
                <?php elseif (!empty($errors)): ?>
                <div class="feedback feedback--error" role="alert">
                    Please fix the highlighted fields and try again.
                </div>
                <?php endif; ?>

                <div class="form-row">
                    <label for="topic">Consultation topic</label>
                    <select id="topic" name="topic"
                            aria-invalid="<?= isset($errors['topic']) ? 'true' : 'false' ?>"
                            required>
                        <option value="">Select a topic...</option>
                        <option value="Anxiety & stress" <?= $formData['topic'] === 'Anxiety & stress' ? 'selected' : '' ?>>Anxiety & stress</option>
                        <option value="Relationships" <?= $formData['topic'] === 'Relationships' ? 'selected' : '' ?>>Relationships</option>
                        <option value="Low mood / depression" <?= $formData['topic'] === 'Low mood / depression' ? 'selected' : '' ?>>Low mood / depression</option>
                        <option value="Self-esteem" <?= $formData['topic'] === 'Self-esteem' ? 'selected' : '' ?>>Self-esteem</option>
                        <option value="Trauma-informed support" <?= $formData['topic'] === 'Trauma-informed support' ? 'selected' : '' ?>>Trauma-informed support</option>
                        <option value="Other" <?= $formData['topic'] === 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                    <?php if (isset($errors['topic'])): ?>
                    <span class="error-msg" style="display:block;"><?= h($errors['topic']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <label for="message">Brief summary of your concern</label>
                    <textarea id="message" name="message" rows="5"
                              aria-invalid="<?= isset($errors['message']) ? 'true' : 'false' ?>"
                              required><?= h($formData['message']) ?></textarea>
                    <span class="help-text">Please share what you'd like to discuss in your consultation.</span>
                    <?php if (isset($errors['message'])): ?>
                    <span class="error-msg" style="display:block;"><?= h($errors['message']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <p class="privacy-notice" style="font-size: 0.85rem; color: #9ca3af; margin: 0;">
                        <strong>Privacy Notice:</strong> Your consultation request is kept confidential and will only
                        be reviewed by authorised CalmMind staff. The information you provide helps us match you
                        with an appropriate mental health professional.
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
                <li><a href="../index.html">Home</a></li>
                <li><a href="../services.html">Consultation services</a></li>
                <li><a href="../resources.html">Self-help resources</a></li>
                <li><a href="dashboard.php">My dashboard</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h3>Need immediate help?</h3>
            <p class="muted">If you are in crisis, contact local emergency services or a crisis helpline.</p>
        </div>
    </div>
</footer>

<script src="../js/script.js"></script>
</body>
</html>
