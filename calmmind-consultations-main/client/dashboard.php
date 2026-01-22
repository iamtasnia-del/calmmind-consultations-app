<?php
/**
 * CalmMind Consultation - Client Dashboard
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

requireLogin();

$pdo = getDbConnection();
$stmt = $pdo->prepare('SELECT * FROM consultations WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([getCurrentUserId()]);
$consultations = $stmt->fetchAll();

function getStatusBadge(string $status): string {
    $classes = [
        'approved' => 'badge-success',
        'rejected' => 'badge-error',
        'pending' => 'badge-pending'
    ];
    $class = $classes[$status] ?? 'badge-pending';
    return '<span class="badge-status ' . $class . '">' . htmlspecialchars($status) . '</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Dashboard – CalmMind Consultation</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header class="site-header">
    <div class="site-header-inner">
        <nav class="main-nav">
            <a href="../index.php">Home</a>
            <a href="dashboard.php" aria-current="page">Dashboard</a>
            <a href="../logout.php" class="nav-cta">Logout</a>
        </nav>
    </div>
</header>
<main id="main">
    <div class="wrapper">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2>My Consultations</h2>
            <a href="submit.php" class="btn btn-primary">Request New Consultation</a>
        </div>

        <section class="section-card">
            <h3>Consultation History</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Topic</th>
                            <th>Status</th>
                            <th>Appointment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($consultations as $c): ?>
                            <tr>
                                <td><?= date('M j, Y', strtotime($c['created_at'])) ?></td>
                                <td><?= htmlspecialchars($c['topic']) ?></td>
                                <td><?= getStatusBadge($c['status']) ?></td>
                                <td>
                                    <?php if ($c['appointment_datetime']): ?>
                                        <strong><?= date('M j, Y', strtotime($c['appointment_datetime'])) ?></strong><br>
                                        <small class="muted"><?= date('g:i A', strtotime($c['appointment_datetime'])) ?></small>
                                    <?php else: ?>
                                        <span class="muted">TBD</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($consultations)): ?>
                            <tr><td colspan="4" style="text-align: center; padding: 2rem;" class="muted">You haven't requested any consultations yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <?php if (!empty($consultations)): ?>
        <div style="margin-top: 3rem;">
            <h3>Review Notes</h3>
            <?php foreach ($consultations as $c): ?>
                <?php if ($c['admin_notes'] || $c['status'] === 'approved'): ?>
                <article class="section-card">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <h4><?= htmlspecialchars($c['topic']) ?></h4>
                        <?= getStatusBadge($c['status']) ?>
                    </div>
                    
                    <?php if ($c['admin_notes']): ?>
                        <div style="padding: 1rem; background-color: #f8fafc; border-radius: 0.5rem; margin-bottom: 1rem; border-left: 4px solid var(--primary);">
                            <p style="margin: 0; font-size: 0.9rem;"><strong>Advisor Message:</strong></p>
                            <p style="margin: 0.5rem 0 0;"><?= nl2br(htmlspecialchars($c['admin_notes'])) ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if ($c['appointment_datetime']): ?>
                        <p class="muted">Your session is confirmed for <?= date('F j, Y \a\t g:i A', strtotime($c['appointment_datetime'])) ?>.</p>
                    <?php endif; ?>
                </article>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</main>
</body>
</html>