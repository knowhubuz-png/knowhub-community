<?php
/**
 * KnowHub Community - Shared Hosting Installation Script
 * Shared hosting uchun avtomatik o'rnatish skripti
 */

session_start();
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$error = '';
$success = '';

// Installation steps
$steps = [
    1 => 'Xush kelibsiz',
    2 => 'Server tekshiruvi',
    3 => 'Database sozlash',
    4 => 'Fayllar sozlash',
    5 => 'Yakunlash'
];

function checkRequirements() {
    $requirements = [
        'php_version' => version_compare(PHP_VERSION, '8.2.0', '>='),
        'extensions' => [
            'mysqli' => extension_loaded('mysqli'),
            'pdo_mysql' => extension_loaded('pdo_mysql'),
            'openssl' => extension_loaded('openssl'),
            'mbstring' => extension_loaded('mbstring'),
            'tokenizer' => extension_loaded('tokenizer'),
            'xml' => extension_loaded('xml'),
            'ctype' => extension_loaded('ctype'),
            'json' => extension_loaded('json'),
            'curl' => extension_loaded('curl'),
            'zip' => extension_loaded('zip'),
            'fileinfo' => extension_loaded('fileinfo'),
        ],
        'directories' => [
            'storage_writable' => is_writable(__DIR__ . '/storage'),
            'cache_writable' => is_writable(__DIR__ . '/bootstrap/cache'),
        ]
    ];
    
    return $requirements;
}

function createEnvFile($dbData) {
    $envContent = "APP_NAME=\"KnowHub Community\"
APP_ENV=production
APP_KEY=" . generateAppKey() . "
APP_DEBUG=false
APP_URL=" . getCurrentUrl() . "

DB_CONNECTION=mysql
DB_HOST={$dbData['host']}
DB_PORT={$dbData['port']}
DB_DATABASE={$dbData['database']}
DB_USERNAME={$dbData['username']}
DB_PASSWORD={$dbData['password']}

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@" . parse_url(getCurrentUrl(), PHP_URL_HOST) . "
MAIL_FROM_NAME=\"KnowHub Community\"

SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
";

    return file_put_contents(__DIR__ . '/.env', $envContent);
}

function generateAppKey() {
    return 'base64:' . base64_encode(random_bytes(32));
}

function getCurrentUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['REQUEST_URI']);
    return $protocol . '://' . $host . $path;
}

function runMigrations($dbData) {
    try {
        $pdo = new PDO(
            "mysql:host={$dbData['host']};port={$dbData['port']};dbname={$dbData['database']}",
            $dbData['username'],
            $dbData['password']
        );
        
        // Basic tables creation
        $sql = "
        CREATE TABLE IF NOT EXISTS users (
            id bigint unsigned NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            username varchar(255) NOT NULL UNIQUE,
            email varchar(255) UNIQUE,
            password varchar(255),
            avatar_url varchar(255),
            xp int DEFAULT 0,
            level_id bigint unsigned,
            bio text,
            is_admin boolean DEFAULT false,
            is_banned boolean DEFAULT false,
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY (id)
        );
        
        CREATE TABLE IF NOT EXISTS categories (
            id bigint unsigned NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL UNIQUE,
            slug varchar(255) NOT NULL UNIQUE,
            description text,
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY (id)
        );
        
        CREATE TABLE IF NOT EXISTS posts (
            id bigint unsigned NOT NULL AUTO_INCREMENT,
            user_id bigint unsigned NOT NULL,
            category_id bigint unsigned,
            title varchar(255) NOT NULL,
            slug varchar(255) NOT NULL UNIQUE,
            content_markdown text NOT NULL,
            status enum('draft','published') DEFAULT 'published',
            score int DEFAULT 0,
            answers_count int DEFAULT 0,
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY (id),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
        );
        ";
        
        $pdo->exec($sql);
        
        // Insert default data
        $pdo->exec("INSERT IGNORE INTO categories (name, slug, description) VALUES 
            ('Dasturlash', 'dasturlash', 'Umumiy dasturlash mavzulari'),
            ('AI', 'ai', 'Sun\\'iy intellekt va machine learning'),
            ('Web Development', 'web-development', 'Veb-sayt va veb-ilova yaratish'),
            ('Mobile', 'mobile', 'Mobil ilova yaratish')
        ");
        
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($step) {
        case 3: // Database setup
            $dbData = [
                'host' => $_POST['db_host'] ?? 'localhost',
                'port' => $_POST['db_port'] ?? '3306',
                'database' => $_POST['db_database'] ?? '',
                'username' => $_POST['db_username'] ?? '',
                'password' => $_POST['db_password'] ?? '',
            ];
            
            try {
                $pdo = new PDO(
                    "mysql:host={$dbData['host']};port={$dbData['port']};dbname={$dbData['database']}",
                    $dbData['username'],
                    $dbData['password']
                );
                
                $_SESSION['db_data'] = $dbData;
                header('Location: ?step=4');
                exit;
            } catch (Exception $e) {
                $error = 'Database ga ulanib bo\'lmadi: ' . $e->getMessage();
            }
            break;
            
        case 4: // File setup
            if (isset($_SESSION['db_data'])) {
                if (createEnvFile($_SESSION['db_data'])) {
                    if (runMigrations($_SESSION['db_data'])) {
                        header('Location: ?step=5');
                        exit;
                    } else {
                        $error = 'Database migratsiya xatoligi';
                    }
                } else {
                    $error = '.env fayl yaratib bo\'lmadi';
                }
            }
            break;
    }
}

?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KnowHub Community - O'rnatish</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto py-12 px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-white font-bold text-xl">KH</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">KnowHub Community</h1>
            <p class="text-gray-600">Shared Hosting O'rnatish</p>
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

            <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <?php switch ($step): 
                case 1: ?>
                    <h2 class="text-2xl font-bold mb-4">Xush kelibsiz!</h2>
                    <p class="text-gray-600 mb-6">
                        Bu skript sizga KnowHub Community ni shared hostingga oson o'rnatishga yordam beradi.
                    </p>
                    <div class="grid md:grid-cols-3 gap-6 mb-8">
                        <div class="text-center p-4 border border-gray-200 rounded-lg">
                            <div class="text-3xl mb-2">🔧</div>
                            <h3 class="font-semibold">Server Tekshiruvi</h3>
                            <p class="text-sm text-gray-600">PHP va extensions</p>
                        </div>
                        <div class="text-center p-4 border border-gray-200 rounded-lg">
                            <div class="text-3xl mb-2">🗄️</div>
                            <h3 class="font-semibold">Database</h3>
                            <p class="text-sm text-gray-600">MySQL sozlash</p>
                        </div>
                        <div class="text-center p-4 border border-gray-200 rounded-lg">
                            <div class="text-3xl mb-2">✅</div>
                            <h3 class="font-semibold">Yakunlash</h3>
                            <p class="text-sm text-gray-600">Konfiguratsiya</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <a href="?step=2" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                            Boshlash
                        </a>
                    </div>
                    <?php break;

                case 2: 
                    $requirements = checkRequirements(); ?>
                    <h2 class="text-2xl font-bold mb-4">Server Tekshiruvi</h2>
                    
                    <!-- PHP Version -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3">PHP Versiyasi</h3>
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <span>PHP <?= PHP_VERSION ?></span>
                            <span class="<?= $requirements['php_version'] ? 'text-green-600' : 'text-red-600' ?>">
                                <?= $requirements['php_version'] ? '✅ OK' : '❌ 8.2+ kerak' ?>
                            </span>
                        </div>
                    </div>

                    <!-- Extensions -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3">PHP Extensions</h3>
                        <div class="space-y-2">
                            <?php foreach ($requirements['extensions'] as $ext => $loaded): ?>
                                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                    <span><?= $ext ?></span>
                                    <span class="<?= $loaded ? 'text-green-600' : 'text-red-600' ?>">
                                        <?= $loaded ? '✅ OK' : '❌ Yo\'q' ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Directories -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3">Papka Ruxsatlari</h3>
                        <div class="space-y-2">
                            <?php foreach ($requirements['directories'] as $dir => $writable): ?>
                                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                    <span><?= str_replace('_', ' ', $dir) ?></span>
                                    <span class="<?= $writable ? 'text-green-600' : 'text-red-600' ?>">
                                        <?= $writable ? '✅ Yozish mumkin' : '❌ Ruxsat yo\'q' ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <a href="?step=1" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600">
                            Orqaga
                        </a>
                        <?php 
                        $canContinue = $requirements['php_version'] && 
                                      array_reduce($requirements['extensions'], fn($carry, $item) => $carry && $item, true) &&
                                      array_reduce($requirements['directories'], fn($carry, $item) => $carry && $item, true);
                        ?>
                        <a href="?step=3" 
                           class="<?= $canContinue ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-400 cursor-not-allowed' ?> text-white px-6 py-3 rounded-lg transition-colors">
                            Davom etish
                        </a>
                    </div>
                    <?php break;

                case 3: ?>
                    <h2 class="text-2xl font-bold mb-4">Database Sozlash</h2>
                    <p class="text-gray-600 mb-6">
                        MySQL database ma'lumotlarini kiriting. Bu ma'lumotlarni hosting provideringizdan olishingiz mumkin.
                    </p>
                    
                    <form method="POST" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Database Host</label>
                            <input type="text" name="db_host" value="localhost" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Database Port</label>
                            <input type="text" name="db_port" value="3306" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Database Nomi</label>
                            <input type="text" name="db_database" required
                                   placeholder="username_knowhub"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Database Username</label>
                            <input type="text" name="db_username" required
                                   placeholder="username_dbuser"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Database Password</label>
                            <input type="password" name="db_password" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        
                        <div class="flex justify-between pt-4">
                            <a href="?step=2" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600">
                                Orqaga
                            </a>
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                                Database Tekshirish
                            </button>
                        </div>
                    </form>
                    <?php break;

                case 4: ?>
                    <h2 class="text-2xl font-bold mb-4">Fayllar Sozlash</h2>
                    <p class="text-gray-600 mb-6">
                        .env fayl yaratilmoqda va database jadvallar o'rnatilmoqda...
                    </p>
                    
                    <div class="bg-blue-50 p-4 rounded-lg mb-6">
                        <h3 class="font-semibold text-blue-900 mb-2">Keyingi qadamlar:</h3>
                        <ol class="list-decimal list-inside text-blue-800 space-y-1">
                            <li>File permissions ni to'g'ri sozlang (755/777)</li>
                            <li>Composer dependencies ni o'rnating</li>
                            <li>Laravel optimizatsiya buyruqlarini bajaring</li>
                        </ol>
                    </div>
                    
                    <form method="POST">
                        <div class="flex justify-between">
                            <a href="?step=3" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600">
                                Orqaga
                            </a>
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                                Yakunlash
                            </button>
                        </div>
                    </form>
                    <?php break;

                case 5: ?>
                    <div class="text-center">
                        <div class="text-6xl mb-4">🎉</div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">O'rnatish Yakunlandi!</h2>
                        <p class="text-gray-600 mb-8">
                            KnowHub Community muvaffaqiyatli o'rnatildi. Endi platformadan foydalanishingiz mumkin.
                        </p>
                        
                        <div class="bg-yellow-50 p-4 rounded-lg mb-6">
                            <h3 class="font-semibold text-yellow-900 mb-2">⚠️ Muhim:</h3>
                            <p class="text-yellow-800">
                                Xavfsizlik uchun ushbu install.php faylni o'chiring yoki nomini o'zgartiring!
                            </p>
                        </div>
                        
                        <div class="space-y-4">
                            <a href="/" class="block w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                                Saytga O'tish
                            </a>
                            <a href="/register" class="block w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors">
                                Admin Hisob Yaratish
                            </a>
                        </div>
                    </div>
                    <?php break;
            endswitch; ?>
        </div>
    </div>
</body>
</html>