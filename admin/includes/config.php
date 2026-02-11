<?php
// Base application configuration and helper utilities.

if (!defined('APP_NAME')) {
    define('APP_NAME', 'Imhrc');
}

if (!defined('APP_LOGO_URL')) {
    define('APP_LOGO_URL', 'https://imhrc.org/IMHRC-LOGO.jpg');
}

// Derive a base URL from the current script path so assets resolve correctly when
// the project is served from a sub-folder.
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
$baseUrl = rtrim($scriptDir, '/');
if ($baseUrl === '' || $baseUrl === '.') {
    $baseUrl = '';
} elseif ($baseUrl !== '/') {
    $baseUrl .= '/';
}

if (!defined('BASE_URL')) {
    define('BASE_URL', $baseUrl === '/' ? '/' : $baseUrl);
}

if (!defined('ASSET_URL')) {
    define('ASSET_URL', BASE_URL);
}

// Build a public asset URL from a relative path.
function asset(string $path): string
{
    return rtrim(ASSET_URL, '/') . '/' . ltrim($path, '/');
}
