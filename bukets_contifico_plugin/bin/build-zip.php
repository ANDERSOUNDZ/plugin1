<?php
declare(strict_types=1);

$root = dirname(__DIR__);
$slug = 'bukets-contifico-sync';
$zipPath = $root . '/build/' . $slug . '.zip';

$includePaths = [
    'bukets-contifico-sync.php',
    'uninstall.php',
    'readme.txt',
    'LICENSE',
    'includes/',
    'admin/',
    'languages/',
    'vendor/',
];

$excludePatterns = [
    '#\.git#',
    '#\.github#',
    '#node_modules#',
    '#tests#',
    '#bin/#',
    '#build/#',
    '#_docs-reference#',
];

if (!extension_loaded('zip')) {
    fwrite(STDERR, "ERROR: Se requiere la extension PHP zip.\n");
    exit(1);
}

$buildDir = dirname($zipPath);
if (!is_dir($buildDir)) {
    mkdir($buildDir, 0755, true);
}

if (file_exists($zipPath)) {
    unlink($zipPath);
}

$zip = new ZipArchive();
$res = $zip->open($zipPath, ZipArchive::CREATE);
if ($res !== true) {
    fwrite(STDERR, "ERROR: No se pudo crear ZIP (codigo: $res).\n");
    exit(1);
}

$added = 0;
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($iterator as $file) {
    $realPath = $file->getRealPath();
    $relativePath = str_replace($root . DIRECTORY_SEPARATOR, '', $realPath);
    $relativePath = str_replace('\\', '/', $relativePath);

    $included = false;
    foreach ($includePaths as $includePath) {
        $normalized = str_replace('\\', '/', $includePath);
        if ($relativePath === $normalized || str_starts_with($relativePath, $normalized)) {
            $included = true;
            break;
        }
    }

    if (!$included) {
        continue;
    }

    $excluded = false;
    foreach ($excludePatterns as $pattern) {
        if (preg_match($pattern, $relativePath)) {
            $excluded = true;
            break;
        }
    }

    if ($excluded) {
        continue;
    }

    $zip->addFile($realPath, $slug . '/' . $relativePath);
    $added++;
}

$zip->close();

echo "ZIP creado: " . realpath($zipPath) . PHP_EOL;
echo "Archivos: $added" . PHP_EOL;
echo "Tamano: " . number_format(filesize($zipPath) / 1024, 1) . " KB" . PHP_EOL;

$verify = new ZipArchive();
if ($verify->open($zipPath) === true) {
    $bad = 0;
    for ($i = 0; $i < $verify->numFiles; $i++) {
        $stat = $verify->statIndex($i);
        if (str_contains($stat['name'], '\\')) {
            echo " backslash en: {$stat['name']}" . PHP_EOL;
            $bad++;
        }
    }
    $verify->close();
    if ($bad === 0) {
        echo "Verificacion: 0 backslashes, todas las rutas correctas." . PHP_EOL;
    } else {
        echo "ERROR: Se encontraron $bad rutas con backslash." . PHP_EOL;
        exit(1);
    }
}
