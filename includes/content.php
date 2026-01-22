<?php
/**
 * CalmMind Consultation - Content Loader Helper
 */
function getSiteContent(string $page, string $section): ?array {
    static $pdo = null;
    if ($pdo === null) {
        require_once __DIR__ . '/db.php';
        $pdo = getDbConnection();
    }
    
    $stmt = $pdo->prepare("SELECT content, image_path FROM site_content WHERE page_name = ? AND section_name = ?");
    $stmt->execute([$page, $section]);
    return $stmt->fetch() ?: null;
}

function renderContent(string $page, string $section, string $defaultText = ''): void {
    $data = getSiteContent($page, $section);
    echo htmlspecialchars($data['content'] ?? $defaultText);
}

function renderImage(string $page, string $section, string $defaultSrc = '', string $alt = ''): void {
    $data = getSiteContent($page, $section);
    $src = !empty($data['image_path']) ? '/' . $data['image_path'] : $defaultSrc;
    if ($src) {
        echo '<img src="' . htmlspecialchars($src) . '" alt="' . htmlspecialchars($alt) . '">';
    }
}
