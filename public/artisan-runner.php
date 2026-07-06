<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

define('LARAVEL_START', microtime(true));

// Bootstrap Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/html; charset=utf-8');
echo "<html><body style='font-family: monospace; background: #0a0a0a; color: #ededec; padding: 20px;'>";
echo "<h2>Laravel Web Artisan Runner</h2><hr>";

try {
    // 1. Clear route cache
    echo "Clearing route cache... ";
    Artisan::call('route:clear');
    echo "OK. Output:<br><pre>" . Artisan::output() . "</pre><br>";

    // 2. Clear config cache
    echo "Clearing config cache... ";
    Artisan::call('config:clear');
    echo "OK. Output:<br><pre>" . Artisan::output() . "</pre><br>";

    // 3. Clear view cache
    echo "Clearing view cache... ";
    Artisan::call('view:clear');
    echo "OK. Output:<br><pre>" . Artisan::output() . "</pre><br>";

    // Pre-migration fix: Rename queue jobs table if it clashes
    if (Schema::hasTable('jobs') && Schema::hasColumn('jobs', 'queue')) {
        echo "Renaming existing queue 'jobs' table to 'queue_jobs' to avoid clash... ";
        Schema::rename('jobs', 'queue_jobs');
        echo "OK.<br><br>";
    }

    // 4. Run migrations
    echo "Running migrations... ";
    Artisan::call('migrate', ['--force' => true]);
    echo "OK. Output:<br><pre>" . Artisan::output() . "</pre><br>";

    // 5. Build assets
    echo "Running npm install... ";
    $npmInstall = shell_exec('npm install 2>&1');
    echo "OK. Output:<br><pre>" . htmlspecialchars($npmInstall) . "</pre><br>";

    echo "Building Vite assets... ";
    $npmOutput = shell_exec('npm run build 2>&1');
    echo "OK. Output:<br><pre>" . htmlspecialchars($npmOutput) . "</pre><br>";

    echo "<h3>All operations completed successfully!</h3>";
} catch (\Exception $e) {
    echo "<h3>Error during execution:</h3><pre>" . $e->getMessage() . "</pre>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "</body></html>";
