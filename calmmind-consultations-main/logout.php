<?php
/**
 * CalmMind Consultation - User Logout
 * ICT726 Assignment 4
 */

require_once __DIR__ . '/includes/auth.php';

// Log out the user
logoutUser();

// Redirect to home page
header('Location: /index.php');
exit;
