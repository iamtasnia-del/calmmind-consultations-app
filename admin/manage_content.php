<?php
/**
 * CalmMind Consultation - Manage Content (CMS)
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

$cmsConfig = require __DIR__ . '/../includes/cms_config.php';
$pdo = getDbConnection();
$message = '';
$error = '';

$selectedPage = $_GET['page'] ?? array_key_first($cmsConfig);
if (!isset($cmsConfig[$selectedPage])) {
    $selectedPage = array_key_first($cmsConfig);
}

$pageSections = $cmsConfig[$selectedPage]['sections'];
$selectedSection = $_GET['section'] ?? array_key_first($pageSections);
if (!isset($pageSections[$selectedSection])) {
    $selectedSection = array_key_first($pageSections);
}

$sectionConfig = $pageSections[$selectedSection];

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page = $_POST['page_name'] ?? '';
    $section = $_POST['section_name'] ?? '';
    $content = $_POST['content'] ?? '';
    
    if (isset($cmsConfig[$page]['sections'][$section])) {
        $imagePath = null;
        
        // Handle Image Upload if section allows it
        if ($cmsConfig[$page]['sections'][$section]['has_image'] && !empty($_FILES['image']['name'])) {
            $allowed = ['jpg', 'jpeg', 'png'];
            $filename = $_FILES['image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (!in_array($ext, $allowed)) {
                $error = "Invalid file type. Only JPG and PNG allowed.";
            } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                $error = "File size exceeds 2MB limit.";
            } elseif (!getimagesize($_FILES['image']['tmp_name'])) {
                $error = "Invalid image file.";
            } else {
                $uploadDir = __DIR__ . "/../uploads/" . preg_replace('/[^a-z0-9]/', '', $page) . "/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $newFilename = uniqid() . "." . $ext;
                $targetPath = $uploadDir . $newFilename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $imagePath = "uploads/" . preg_replace('/[^a-z0-9]/', '', $page) . "/" . $newFilename;
                } else {
                    $error = "Failed to upload image.";
                }
            }
        }

        if (empty($error)) {
            $checkStmt = $pdo->prepare("SELECT id FROM site_content WHERE page_name = ? AND section_name = ?");
            $checkStmt->execute([$page, $section]);
            $exists = $checkStmt->fetch();

            if ($exists) {
                $sql = "UPDATE site_content SET content = ?, updated_at = CURRENT_TIMESTAMP";
                $params = [$content];
                if ($imagePath) {
                    $sql .= ", image_path = ?";
                    $params[] = $imagePath;
                }
                $sql .= " WHERE page_name = ? AND section_name = ?";
                $params[] = $page;
                $params[] = $section;
            } else {
                $sql = "INSERT INTO site_content (page_name, section_name, content, image_path) VALUES (?, ?, ?, ?)";
                $params = [$page, $section, $content, $imagePath];
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $message = "Content updated successfully.";
            
            // Refresh current data
            $stmt = $pdo->prepare("SELECT * FROM site_content WHERE page_name = ? AND section_name = ?");
            $stmt->execute([$page, $section]);
            $currentData = $stmt->fetch();
        }
    }
} else {
    $stmt = $pdo->prepare("SELECT * FROM site_content WHERE page_name = ? AND section_name = ?");
    $stmt->execute([$selectedPage, $selectedSection]);
    $currentData = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Content – CalmMind Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Specific override for CMS input to match user's visual preference for login-style fields */
        .cms-input {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.05);
        }
        .cms-input:focus {
            background-color: #ffffff;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1), inset 0 2px 4px 0 rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body>
<header class="site-header">
    <div class="site-header-inner">
        <nav class="main-nav">
            <a href="dashboard.php">Dashboard</a>
            <a href="manage_content.php" aria-current="page">Manage Content</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </div>
</header>
<main id="main">
    <div class="wrapper">
        <h2 style="margin-bottom: 2rem;">Manage Website Content</h2>
        
        <?php if ($message): ?><div class="feedback feedback--success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="feedback feedback--error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <section class="section-card">
            <h3>Select Content Section</h3>
            <form method="get" action="manage_content.php" style="background: none; border: none; padding: 0; box-shadow: none;">
                <div class="form-grid">
                    <div class="form-row">
                        <label>Page</label>
                        <select name="page" onchange="this.form.submit()">
                            <?php foreach ($cmsConfig as $pName => $pConfig): ?>
                                <option value="<?= $pName ?>" <?= $selectedPage == $pName ? 'selected' : '' ?>><?= htmlspecialchars($pConfig['label']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-row">
                        <label>Section</label>
                        <select name="section" onchange="this.form.submit()">
                            <?php foreach ($pageSections as $sName => $sConfig): ?>
                                <option value="<?= $sName ?>" <?= $selectedSection == $sName ? 'selected' : '' ?>><?= htmlspecialchars($sConfig['label']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </form>
        </section>

        <section class="section-card">
            <h3>Edit: <?= htmlspecialchars($sectionConfig['label']) ?></h3>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="page_name" value="<?= htmlspecialchars($selectedPage) ?>">
                <input type="hidden" name="section_name" value="<?= htmlspecialchars($selectedSection) ?>">
                
                <div class="form-row">
                    <label>Content Text</label>
                    <?php if ($sectionConfig['type'] === 'text'): ?>
                        <input type="text" name="content" class="cms-input" value="<?= htmlspecialchars($currentData['content'] ?? '') ?>" required>
                    <?php else: ?>
                        <textarea name="content" class="cms-input" rows="8" required><?= htmlspecialchars($currentData['content'] ?? '') ?></textarea>
                    <?php endif; ?>
                </div>
                
                <?php if ($sectionConfig['has_image']): ?>
                <div class="form-row" style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border);">
                    <label>Media Asset</label>
                    <div style="display: flex; gap: 2rem; align-items: flex-start;">
                        <?php if (!empty($currentData['image_path'])): ?>
                            <div style="flex: 0 0 200px;">
                                <img src="/<?= htmlspecialchars($currentData['image_path']) ?>" alt="Current" style="width: 100%; border-radius: 0.5rem; border: 1px solid var(--border);">
                                <p style="font-size: 0.75rem; margin-top: 0.5rem;" class="muted text-center">Current: <?= basename($currentData['image_path']) ?></p>
                            </div>
                        <?php endif; ?>
                        <div style="flex: 1;">
                            <label style="font-size: 0.8rem; color: var(--muted);">Upload New Image</label>
                            <input type="file" name="image" accept=".jpg,.jpeg,.png">
                            <p style="font-size: 0.75rem; margin-top: 0.5rem;" class="muted">Supported: JPG, PNG. Max 2MB.</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">Update Content</button>
                </div>
            </form>
        </section>
    </div>
</main>
</body>
</html>