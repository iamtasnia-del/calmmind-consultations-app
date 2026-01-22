<?php
/**
 * CalmMind Consultation - Authentication Helper
 * ICT726 Assignment 4
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

/**
 * Get current user ID
 * @return int|null
 */
function getCurrentUserId(): ?int {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user role
 * @return string|null
 */
function getCurrentUserRole(): ?string {
    return $_SESSION['user_role'] ?? null;
}

/**
 * Get current user name
 * @return string|null
 */
function getCurrentUserName(): ?string {
    return $_SESSION['user_name'] ?? null;
}

/**
 * Check if current user is admin
 * @return bool
 */
function isAdmin(): bool {
    return getCurrentUserRole() === 'admin';
}

/**
 * Check if current user is client
 * @return bool
 */
function isClient(): bool {
    return getCurrentUserRole() === 'client';
}

/**
 * Require user to be logged in, redirect to login if not
 * @param string $redirect URL to redirect to after login
 */
function requireLogin(string $redirect = ''): void {
    if (!isLoggedIn()) {
        header('Location: /login.php');
        exit;
    }
}

/**
 * Require user to be admin, redirect if not
 */
function requireAdmin(): void {
    requireLogin();
    if (!isAdmin()) {
        header('Location: /client/dashboard.php');
        exit;
    }
}

/**
 * Require user to be client, redirect if not
 */
function requireClient(): void {
    requireLogin();
    if (!isClient()) {
        header('Location: /admin/dashboard.php');
        exit;
    }
}

/**
 * Log in a user by setting session variables
 * @param int $userId
 * @param string $name
 * @param string $role
 */
function loginUser(int $userId, string $name, string $role): void {
    $_SESSION['user_id'] = $userId;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_role'] = $role;
}

/**
 * Log out current user
 */
function logoutUser(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}

/**
 * Escape output for HTML display
 * @param string|null $string
 * @return string
 */
function h(?string $string): string {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}
