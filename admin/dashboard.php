<?php
/**
 * CalmMind Consultation - Admin Dashboard
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

requireAdmin();

$statusFilter = $_GET['status'] ?? '';
$searchTerm = trim($_GET['search'] ?? '');
$validStatuses = ['pending', 'approved', 'rejected'];

$pdo = getDbConnection();
$sql = 'SELECT c.*, u.full_name, u.email
        FROM consultations c
        JOIN users u ON c.user_id = u.id';
$params = [];
$whereClauses = [];

if ($statusFilter && in_array($statusFilter, $validStatuses)) {
    $whereClauses[] = 'c.status = ?';
    $params[] = $statusFilter;
}

if ($searchTerm) {
    $whereClauses[] = '(u.full_name LIKE ? OR u.email LIKE ?)';
    $params[] = "%$searchTerm%";
    $params[] = "%$searchTerm%";
}

if ($whereClauses) {
    $sql .= ' WHERE ' . implode(' AND ', $whereClauses);
}

$sql .= ' ORDER BY c.created_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
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

$successMessage = '';
if (isset($_GET['updated'])) {
    $successMessage = 'Consultation updated successfully.';
}
if (isset($_GET['error']) && $_GET['error'] === 'future_date') {
    $error = 'Appointment date must be in the future.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard – CalmMind Consultation</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header class="site-header">
    <div class="site-header-inner">
        <nav class="main-nav">
            <a href="../index.php">Home</a>
            <a href="dashboard.php" aria-current="page">Admin</a>
            <a href="manage_content.php">Manage Content</a>
            <a href="messages.php">Messages</a>
            <a href="../logout.php" class="nav-cta">Logout</a>
        </nav>
    </div>
</header>
<main id="main">
    <div class="wrapper">
        <h2 style="margin-bottom: 2rem;">Admin Dashboard</h2>
        
        <?php if ($successMessage): ?>
            <div class="feedback feedback--success"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="feedback feedback--error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <section class="section-card">
            <h3>Filters & Search</h3>
            <form method="get" action="dashboard.php" style="background: none; border: none; padding: 0; box-shadow: none;">
                <div class="form-grid" style="grid-template-columns: 1fr 1fr auto;">
                    <div class="form-row">
                        <label>Search Client</label>
                        <input type="text" name="search" placeholder="Name or email..." value="<?= htmlspecialchars($searchTerm) ?>">
                    </div>
                    <div class="form-row">
                        <label>Status</label>
                        <select name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="approved" <?= $statusFilter === 'approved' ? 'selected' : '' ?>>Approved</option>
                            <option value="rejected" <?= $statusFilter === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
                    </div>
                    <div style="display: flex; align-items: flex-end; padding-bottom: 1rem;">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
        </section>

        <section class="section-card">
            <h3>Consultation Requests</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Topic</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($consultations as $c): ?>
                            <tr>
                                <td><?= date('M j, Y', strtotime($c['created_at'])) ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($c['full_name']) ?></strong><br>
                                    <small class="muted"><?= htmlspecialchars($c['email']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($c['topic']) ?></td>
                                <td><?= getStatusBadge($c['status']) ?></td>
                                <td>
                                    <a href="#edit-<?= $c['id'] ?>" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Review</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($consultations)): ?>
                            <tr><td colspan="5" style="text-align: center; padding: 2rem;" class="muted">No consultations found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <div style="margin-top: 3rem;">
            <h3>Update Consultations</h3>
            <?php foreach ($consultations as $c): ?>
                <article class="section-card" id="edit-<?= $c['id'] ?>">
                    <div class="form-grid" style="grid-template-columns: 1fr 1fr;">
                        <div>
                            <h4>Request Details</h4>
                            <table class="detail-table">
                                <tr>
                                    <th>Submitted</th>
                                    <td><?= $c['created_at'] ?></td>
                                </tr>
                                <tr>
                                    <th>Client</th>
                                    <td><?= htmlspecialchars($c['full_name']) ?> (<?= htmlspecialchars($c['email']) ?>)</td>
                                </tr>
                                <tr>
                                    <th>Topic</th>
                                    <td><?= htmlspecialchars($c['topic']) ?></td>
                                </tr>
                                <tr>
                                    <th>Message</th>
                                    <td style="white-space: pre-wrap;"><?= htmlspecialchars($c['message']) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div>
                            <h4>Management Actions</h4>
                            <form method="post" action="update-status.php">
                                <input type="hidden" name="consultation_id" value="<?= $c['id'] ?>">
                                <div class="form-row">
                                    <label>Status</label>
                                    <select name="status">
                                        <option value="pending" <?= $c['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="approved" <?= $c['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                                        <option value="rejected" <?= $c['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label>Appointment Time (if approved)</label>
                                    <input type="datetime-local" name="appointment_datetime" value="<?= $c['appointment_datetime'] ? date('Y-m-d\TH:i', strtotime($c['appointment_datetime'])) : '' ?>">
                                </div>
                                <div class="form-row">
                                    <label>Admin Notes</label>
                                    <textarea name="admin_notes" rows="3"><?= htmlspecialchars($c['admin_notes'] ?? '') ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary" style="width: 100%;">Update Request</button>
                            </form>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</main>
</body>
</html>