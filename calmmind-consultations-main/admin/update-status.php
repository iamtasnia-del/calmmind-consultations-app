<?php
/**
 * CalmMind Consultation - Update Consultation Status
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $consultationId = (int)($_POST['consultation_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    $adminNotes = $_POST['admin_notes'] ?? '';
    $appointmentDatetime = !empty($_POST['appointment_datetime']) ? $_POST['appointment_datetime'] : null;

    $validStatuses = ['pending', 'approved', 'rejected'];
    if (in_array($status, $validStatuses) && $consultationId > 0) {
        $pdo = getDbConnection();
        
        if ($appointmentDatetime && $status === 'approved') {
            if (strtotime($appointmentDatetime) < time()) {
                header('Location: dashboard.php?error=future_date');
                exit;
            }
        }

        $sql = 'UPDATE consultations SET status = ?, admin_notes = ?';
        $params = [$status, $adminNotes];
        
        if ($status === 'approved' && $appointmentDatetime) {
            $sql .= ', appointment_datetime = ?';
            $params[] = $appointmentDatetime;
        } else {
            // Only admins can assign appointments to approved consultations.
            // Requirement says "Assign an appointment date and time to an approved consultation only"
            // We should clear it if not approved or if explicitly desired? Requirement doesn't say clear.
            // But we'll follow the rule strictly.
        }
        
        $sql .= ', updated_at = NOW() WHERE id = ?';
        $params[] = $consultationId;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        header('Location: dashboard.php?updated=1');
        exit;
    }
}
header('Location: dashboard.php');
exit;