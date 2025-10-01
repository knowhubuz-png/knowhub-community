<?php
/**
 * KnowHub Community - Shared Hosting Deployment Script
 * Bu skript loyihani shared hostingga avtomatik deploy qiladi
 */

set_time_limit(300); // 5 daqiqa
ini_set('memory_limit', '512M');

$step = $_GET['step'] ?? 1;
$error = '';
$success = '';

// Deployment steps
$steps = [
    1 => 'Tayyorgarlik',
    2 => 'Backend Deploy',
    3 => 'Frontend Build',
    4 => 'Database Setup',
    5 => 'Yakunlash'
];

function checkRequirements() {
    return [
        'php_version' => version_compare(PHP_VERSION, '8.2.0', '>='),
        'extensions' => [
            'mysqli' => extension_loaded('mysqli'),
            'pdo_mysql' => extension_loaded('pdo_mysql'),
            'openssl' => extension_loaded('openssl'),
            'mbstring' => extension_loaded('mbstring'),
            'zip' => extension_loaded('zip'),
            'curl' => extension_loaded('curl'),
        ],
        'directories' => [
            'storage_writable' => is_writable(__DIR__ . '/storage'),
            'cache_writable' => is_writable(__DIR__ . '/bootstrap/cache'),
        ]
    ];
}

function deployBackend() {
    $commands = [
        'composer install --no-dev --optimize-autoloader',
        'php artisan key:generate --force',
        'php artisan config:cache',
        'php artisan route:cache',
        'php artisan view:cache',
        'php artisan storage:link',
    ];
    
    $output = [];
    foreach ($commands as $cmd) {
        exec($cmd . ' 2>&1', $cmdOutput, $returnVar);
        $output[] = [
            'command' => $cmd,
            'output' => implode("\n", $cmdOutput),
            'success' => $returnVar === 0
        ];
        $cmdOutput = [];
    }
    
    return $output;
}

function buildFrontend() {
    $commands = [
        'cd frontend && npm install',
        'cd frontend && npm run build',
        'cp -r frontend/out/* public/',
    ];
    
    $output = [];
    foreach ($commands as $cmd) {
        exec($cmd . ' 2>&1', $cmdOutput, $returnVar);
        $output[] = [
            'command' => $cmd,
            'output' => implode("\n", $cmdOutput),
            'success' => $returnVar === 0
        ];
        $cmdOutput = [];
    }
    
    return $output;
}

function setupDatabase() {
    try {
        // Run migrations
        exec('php artisan migrate --force 2>&1', $output, $returnVar);
        
        if ($returnVar !== 0) {
            return ['success' => false, 'error' => implode("\n", $output)];
        }
        
        // Run seeders
        exec('php artisan db:seed --force 2>&1', $output, $returnVar);
        
        return [
            'success' => $returnVar === 0,
            'output' => implode("\n", $output)
        ];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

// Handle deployment steps
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($step) {
        case 2: // Backend Deploy
            $result = deployBackend();
            if (all(array_column($result, 'success'))) {
                header('Location: ?step=3');
                exit;
            } else {
                $error = 'Backend deploy xatoligi: ' . json_encode($result);
            }
            break;
            
        case 3: // Frontend Build
            $result = buildFrontend();
            if (all(array_column($result, 'success'))) {
                header('Location: ?step=4');
                exit;
            } else {
                $error = 'Frontend build xatoligi: ' . json_encode($result);
            }
            break;
            
        case 4: // Database Setup
            $result = setupDatabase();
            if ($result['success']) {
                header('Location: ?step=5');
                exit;
            } else {
                $error = 'Database setup xatoligi: ' . $result['error'];
            }
            break;
    }
}

function all($array) {
    return array_reduce($array, fn($carry, $item) => $carry && $item, true);
}

?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KnowHub Community - Shared Hosting Deploy</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto py-12 px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-white font-bold text-xl">KH</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">KnowHub Community</h1>
            <p class="text-gray-600">Shared Hosting Deploy</p>
        </div>

        <!-- Progress -->
        <div class="flex justify-center mb-8">
            <?php foreach ($steps as $num => $title): ?>
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center <?= $step >= $num ? 'bg-indigo-600 text-white' : 'bg-gray-300 text-gray-600' ?>">
                        <?= $num ?>
                    </div>
                    <?php if ($num < count($steps)): ?>
                        <div class="w-16 h-1 <?= $step > $num ? 'bg-indigo-600' : 'bg-gray-300' ?>"></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php switch ($step): 
                case 1: ?>
                    <h2 class="text-2xl font-bold mb-4">Shared Hosting Deploy</h2>
                    <p class="text-gray-600 mb-6">
                        Bu skript KnowHub Community ni shared hostingga deploy qiladi.
                    </p>
                    
                    <?php $requirements = checkRequirements(); ?>
                    <div class="space-y-4 mb-8">
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <span>PHP 8.2+</span>
                            <span class="<?= $requirements['php_version'] ? 'text-green-600' : 'text-red-600' ?>">
                                <?= $requirements['php_version'] ? '✅ ' . PHP_VERSION : '❌ 8.2+ kerak' ?>
                            </span>
                        </div>
                        <?php foreach ($requirements['extensions'] as $ext => $loaded): ?>
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                <span><?= $ext ?></span>
                                <span class="<?= $loaded ? 'text-green-600' : 'text-red-600' ?>">
                                    <?= $loaded ? '✅ OK' : '❌ Yo\'q' ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="text-center">
                        <a href="?step=2" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                            Deploy Boshlash
                        </a>
                    </div>
                    <?php break;

                case 2: ?>
                    <h2 class="text-2xl font-bold mb-4">Backend Deploy</h2>
                    <p class="text-gray-600 mb-6">Laravel backend deploy qilinmoqda...</p>
                    
                    <form method="POST">
                        <div class="text-center">
                            <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                                Backend Deploy Qilish
                            </button>
                        </div>
                    </form>
                    <?php break;

                case 3: ?>
                    <h2 class="text-2xl font-bold mb-4">Frontend Build</h2>
                    <p class="text-gray-600 mb-6">Next.js frontend build qilinmoqda...</p>
                    
                    <form method="POST">
                        <div class="text-center">
                            <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                                Frontend Build Qilish
                            </button>
                        </div>
                    </form>
                    <?php break;

                case 4: ?>
                    <h2 class="text-2xl font-bold mb-4">Database Setup</h2>
                    <p class="text-gray-600 mb-6">Database migratsiya va seed...</p>
                    
                    <form method="POST">
                        <div class="text-center">
                            <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                                Database Setup
                            </button>
                        </div>
                    </form>
                    <?php break;

                case 5: ?>
                    <div class="text-center">
                        <div class="text-6xl mb-4">🎉</div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Deploy Yakunlandi!</h2>
                        <p class="text-gray-600 mb-8">
                            KnowHub Community muvaffaqiyatli deploy qilindi.
                        </p>
                        
                        <div class="bg-yellow-50 p-4 rounded-lg mb-6">
                            <h3 class="font-semibold text-yellow-900 mb-2">⚠️ Muhim:</h3>
                            <p class="text-yellow-800">
                                Xavfsizlik uchun ushbu deploy skriptini o'chiring!
                            </p>
                        </div>
                        
                        <div class="space-y-4">
                            <a href="/" class="block w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                                Saytga O'tish
                            </a>
                            <a href="/admin" class="block w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors">
                                Admin Panel
                            </a>
                        </div>
                    </div>
                    <?php break;
            endswitch; ?>
        </div>
    </div>
</body>
</html>