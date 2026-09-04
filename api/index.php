<?php

// Check if debug mode is explicitly enabled
$isDebug = (getenv('APP_DEBUG') === 'true' || ($_ENV['APP_DEBUG'] ?? '') === 'true');
if ($isDebug) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(0);
}

// 1. Ensure required storage directories exist in /tmp (the only writable directory in Vercel)
$storageDirs = [
    '/tmp/storage/app/public',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 2. Direct compiled views and cache files to /tmp
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';

putenv('APP_CONFIG_CACHE=/tmp/config.php');
$_ENV['APP_CONFIG_CACHE'] = '/tmp/config.php';

putenv('APP_SERVICES_CACHE=/tmp/services.php');
$_ENV['APP_SERVICES_CACHE'] = '/tmp/services.php';

putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/packages.php';

putenv('APP_ROUTES_CACHE=/tmp/routes.php');
$_ENV['APP_ROUTES_CACHE'] = '/tmp/routes.php';

putenv('APP_MAINTENANCE_DRIVER=file');
$_ENV['APP_MAINTENANCE_DRIVER'] = 'file';

if (empty($_ENV['CACHE_STORE']) || empty(getenv('CACHE_STORE'))) {
    putenv('CACHE_STORE=array');
    $_ENV['CACHE_STORE'] = 'array';
}

if (empty($_ENV['SESSION_DRIVER']) || empty(getenv('SESSION_DRIVER'))) {
    putenv('SESSION_DRIVER=cookie');
    $_ENV['SESSION_DRIVER'] = 'cookie';
}

putenv('SESSION_LIFETIME=120');
$_ENV['SESSION_LIFETIME'] = '120';
putenv('SESSION_COOKIE=nearjob_session');
$_ENV['SESSION_COOKIE'] = 'nearjob_session';

if (empty($_ENV['BCRYPT_ROUNDS']) || (int) getenv('BCRYPT_ROUNDS') < 4) {
    putenv('BCRYPT_ROUNDS=12');
    $_ENV['BCRYPT_ROUNDS'] = '12';
}

// 3. Handle database connection (auto-detect Supabase/PostgreSQL if present, otherwise SQLite)
$rawConn = getenv('DB_CONNECTION') ?: ($_ENV['DB_CONNECTION'] ?? '');
if (empty($rawConn)) {
    if (!empty(getenv('POSTGRES_URL')) || !empty($_ENV['POSTGRES_URL']) || !empty(getenv('POSTGRES_HOST')) || !empty($_ENV['POSTGRES_HOST'])) {
        $rawConn = 'pgsql';
    } else {
        $rawConn = 'sqlite';
    }
}
$dbConnection = $rawConn;
putenv("DB_CONNECTION={$dbConnection}");
$_ENV['DB_CONNECTION'] = $dbConnection;

if ($dbConnection === 'sqlite') {
    $dbPath = '/tmp/database.sqlite';
    $sourceDb = __DIR__ . '/../database/database.sqlite';
    if (!file_exists($dbPath) || filesize($dbPath) === 0) {
        if (file_exists($sourceDb) && filesize($sourceDb) > 0) {
            @copy($sourceDb, $dbPath);
        } else {
            @touch($dbPath);
        }
    }
    putenv("DB_DATABASE={$dbPath}");
    $_ENV['DB_DATABASE'] = $dbPath;
}

// 4. Forward to Laravel public entrypoint
try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    http_response_code(500);
    if ($isDebug) {
        echo "<div style='font-family:sans-serif;padding:30px;max-width:800px;margin:auto;'>";
        echo "<h2 style='color:#dc2626;'>Laravel Error on Vercel</h2>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . ":" . $e->getLine() . "</p>";
        echo "<pre style='background:#f1f5f9;padding:15px;border-radius:8px;overflow:auto;font-size:12px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        echo "</div>";
    } else {
        echo "<div style='font-family:sans-serif;padding:50px 20px;max-width:600px;margin:auto;text-align:center;'>";
        echo "<h1 style='color:#1e293b;font-size:24px;font-weight:700;'>Terjadi Kendala pada Server</h1>";
        echo "<p style='color:#64748b;font-size:14px;line-height:1.6;'>Sistem sedang mengalami kendala teknis sementara. Silakan coba beberapa saat lagi atau hubungi tim pengelola.</p>";
        echo "</div>";
    }
}
