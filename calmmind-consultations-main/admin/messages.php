<?php
/**
 * CalmMind Consultation - Admin Contact Messages
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

$pdo = getDbConnection();
$stmt = $pdo->query('SELECT * FROM contact_messages ORDER BY created_at DESC');
$messages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Messages – CalmMind Consultation</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header class="site-header">
    <div class="site-header-inner">
        <nav class="main-nav">
            <a href="dashboard.php">Dashboard</a>
            <a href="messages.php" aria-current="page">Messages</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </div>
</header>
<main id="main">
    <div class="wrapper">
        <h2 style="margin-bottom: 2rem;">Contact Messages</h2>
        
        <section class="section-card">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Received</th>
                            <th>Sender</th>
                            <th>Topic</th>
                            <th>Message Preview</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $msg): ?>
                            <tr>
                                <td style="white-space: nowrap;"><?= date('M j, Y', strtotime($msg['created_at'])) ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($msg['full_name']) ?></strong><br>
                                    <small class="muted"><?= htmlspecialchars($msg['email']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($msg['topic']) ?></td>
                                <td style="max-width: 400px;">
                                    <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <?= htmlspecialchars($msg['message']) ?>
                                    </div>
                                    <details style="font-size: 0.85rem; margin-top: 0.5rem;">
                                        <summary style="cursor: pointer; color: var(--primary);">View Full Message</summary>
                                        <div style="padding: 1rem; background: #f8fafc; border-radius: 0.4rem; margin-top: 0.5rem; white-space: pre-wrap;"><?= htmlspecialchars($msg['message']) ?></div>
                                    </details>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($messages)): ?>
                            <tr><td colspan="4" style="text-align: center; padding: 2rem;" class="muted">No messages found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>
</body>
</html>