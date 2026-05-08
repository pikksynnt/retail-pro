<?php

// Pindah folder cache ke /tmp khusus buat Vercel (karena read-only)
$cachePath = '/tmp/bootstrap/cache';
if (!is_dir($cachePath)) {
    mkdir($cachePath, 0755, true);
}

// Tambah folder storage/framework/views di /tmp
$viewPath = '/tmp/storage/framework/views';
if (!is_dir($viewPath)) {
    mkdir($viewPath, 0755, true);
}

putenv("APP_CONFIG_CACHE={$cachePath}/config.php");
putenv("APP_ROUTES_CACHE={$cachePath}/routes.php");
putenv("APP_SERVICES_CACHE={$cachePath}/services.php");
putenv("APP_PACKAGES_CACHE={$cachePath}/packages.php");
putenv("VIEW_COMPILED_PATH={$viewPath}");

require __DIR__ . '/../public/index.php';
