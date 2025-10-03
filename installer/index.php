<?php
/**
 * KnowHub Community - Professional Installation Wizard
 * Envato style auto-installation script
 */

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('INSTALLER_VERSION', '1.0.0');
define('APP_NAME', 'KnowHub Community');
define('MIN_PHP_VERSION', '8.2.0');
define('ROOT_PATH', dirname(__DIR__));

$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$error = '';
$success = '';

class Installer {
    private $config = [];

    public function __construct() {
        if (!isset($_SESSION['install_token'])) {
            $_SESSION['install_token'] = bin2hex(random_bytes(32));
        }
    }

    public function checkRequirements() {
        $requirements = [
            'php_version' => [
                'name' => 'PHP Version',
                'required' => MIN_PHP_VERSION,
                'current' => PHP_VERSION,
                'passed' => version_compare(PHP_VERSION, MIN_PHP_VERSION, '>=')
            ],
            'extensions' => []
        ];

        $requiredExtensions = [
            'mysqli', 'pdo_mysql', 'openssl', 'mbstring', 'tokenizer',
            'xml', 'ctype', 'json', 'bcmath', 'curl', 'zip', 'fileinfo', 'gd'
        ];

        foreach ($requiredExtensions as $ext) {
            $requirements['extensions'][$ext] = extension_loaded($ext);
        }

        $requirements['permissions'] = [
            'storage' => $this->checkPermission(ROOT_PATH . '/storage'),
            'bootstrap_cache' => $this->checkPermission(ROOT_PATH . '/bootstrap/cache'),
            'public' => $this->checkPermission(ROOT_PATH . '/public'),
        ];

        return $requirements;
    }

    private function checkPermission($path) {
        return is_writable($path);
    }

    public function testDatabaseConnection($host, $username, $password, $database, $port = 3306) {
        try {
            $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->query("SHOW DATABASES LIKE '{$database}'");
            $dbExists = $stmt->rowCount() > 0;

            if (!$dbExists) {
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            }

            return ['success' => true, 'message' => 'Database connection successful'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function createEnvFile($data) {
        $envContent = "APP_NAME=\"" . APP_NAME . "\"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL={$data['app_url']}
APP_TIMEZONE=Asia/Tashkent
APP_LOCALE=uz

DB_CONNECTION=mysql
DB_HOST={$data['db_host']}
DB_PORT={$data['db_port']}
DB_DATABASE={$data['db_database']}
DB_USERNAME={$data['db_username']}
DB_PASSWORD={$data['db_password']}

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

VITE_APP_NAME=\"\${APP_NAME}\"

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=\"hello@example.com\"
MAIL_FROM_NAME=\"\${APP_NAME}\"
";

        $envPath = ROOT_PATH . '/.env';
        return file_put_contents($envPath, $envContent) !== false;
    }

    public function generateAppKey() {
        $key = 'base64:' . base64_encode(random_bytes(32));
        $envPath = ROOT_PATH . '/.env';
        $envContent = file_get_contents($envPath);
        $envContent = preg_replace('/APP_KEY=.*/', 'APP_KEY=' . $key, $envContent);
        return file_put_contents($envPath, $envContent);
    }

    public function runMigrations() {
        $output = [];
        $artisan = ROOT_PATH . '/artisan';

        if (file_exists($artisan)) {
            exec("cd " . ROOT_PATH . " && php artisan migrate --force 2>&1", $output);
            return implode("\n", $output);
        }

        return "Artisan file not found";
    }

    public function runSeeder() {
        $output = [];
        exec("cd " . ROOT_PATH . " && php artisan db:seed --force 2>&1", $output);
        return implode("\n", $output);
    }

    public function optimizeApp() {
        $commands = [
            'config:cache',
            'route:cache',
            'view:cache',
            'storage:link'
        ];

        $results = [];
        foreach ($commands as $command) {
            exec("cd " . ROOT_PATH . " && php artisan {$command} 2>&1", $output);
            $results[$command] = implode("\n", $output);
            $output = [];
        }

        return $results;
    }

    public function createAdminUser($name, $email, $username, $password) {
        try {
            $envPath = ROOT_PATH . '/.env';
            if (!file_exists($envPath)) {
                return ['success' => false, 'message' => '.env file not found'];
            }

            require ROOT_PATH . '/vendor/autoload.php';
            $app = require ROOT_PATH . '/bootstrap/app.php';
            $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

            $user = \App\Models\User::create([
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'password' => bcrypt($password),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]);

            return ['success' => true, 'message' => 'Admin user created successfully', 'user' => $user];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function finalizeInstallation() {
        $lockFile = ROOT_PATH . '/storage/installed.lock';
        return file_put_contents($lockFile, date('Y-m-d H:i:s')) !== false;
    }
}

$installer = new Installer();

if (file_exists(ROOT_PATH . '/storage/installed.lock') && $step !== 999) {
    header('Location: ../');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['install_token'])) {
    if ($_POST['install_token'] !== $_SESSION['install_token']) {
        die('Invalid security token');
    }
}

switch ($step) {
    case 1:
        showWelcome();
        break;
    case 2:
        showRequirements($installer);
        break;
    case 3:
        showDatabaseConfig($installer);
        break;
    case 4:
        handleDatabaseSetup($installer);
        break;
    case 5:
        showAdminConfig();
        break;
    case 6:
        handleAdminSetup($installer);
        break;
    case 7:
        showFinalize($installer);
        break;
    case 999:
        if (file_exists(ROOT_PATH . '/storage/installed.lock')) {
            unlink(ROOT_PATH . '/storage/installed.lock');
        }
        header('Location: index.php');
        break;
    default:
        showWelcome();
}

function showWelcome() {
    ?>
    <!DOCTYPE html>
    <html lang="uz">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= APP_NAME ?> - Installation Wizard</title>
        <?php includeStyles(); ?>
    </head>
    <body>
        <div class="installer-wrapper">
            <div class="installer-container">
                <div class="installer-header">
                    <div class="logo-section">
                        <div class="logo-icon">🚀</div>
                        <h1><?= APP_NAME ?></h1>
                        <p class="version">Version <?= INSTALLER_VERSION ?></p>
                    </div>
                </div>

                <div class="progress-bar">
                    <div class="progress-step active">
                        <div class="step-number">1</div>
                        <div class="step-label">Welcome</div>
                    </div>
                    <div class="progress-line"></div>
                    <div class="progress-step">
                        <div class="step-number">2</div>
                        <div class="step-label">Requirements</div>
                    </div>
                    <div class="progress-line"></div>
                    <div class="progress-step">
                        <div class="step-number">3</div>
                        <div class="step-label">Database</div>
                    </div>
                    <div class="progress-line"></div>
                    <div class="progress-step">
                        <div class="step-number">4</div>
                        <div class="step-label">Admin</div>
                    </div>
                    <div class="progress-line"></div>
                    <div class="progress-step">
                        <div class="step-number">5</div>
                        <div class="step-label">Finish</div>
                    </div>
                </div>

                <div class="installer-body">
                    <div class="welcome-content">
                        <h2>Welcome to <?= APP_NAME ?> Installation</h2>
                        <p class="subtitle">This wizard will guide you through the installation process</p>

                        <div class="feature-grid">
                            <div class="feature-card">
                                <div class="feature-icon">📊</div>
                                <h3>Server Check</h3>
                                <p>Verify PHP version and required extensions</p>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">🗄️</div>
                                <h3>Database Setup</h3>
                                <p>Configure database connection and run migrations</p>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">👤</div>
                                <h3>Admin Account</h3>
                                <p>Create your administrator account</p>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">✨</div>
                                <h3>Optimization</h3>
                                <p>Optimize and finalize the installation</p>
                            </div>
                        </div>

                        <div class="info-box">
                            <div class="info-icon">ℹ️</div>
                            <div>
                                <h4>Before You Begin</h4>
                                <ul>
                                    <li>Ensure you have PHP <?= MIN_PHP_VERSION ?>+ installed</li>
                                    <li>Prepare your MySQL database credentials</li>
                                    <li>Make sure storage and bootstrap/cache folders are writable</li>
                                    <li>Have Composer dependencies installed (vendor folder)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="installer-footer">
                    <a href="?step=2" class="btn btn-primary btn-lg">
                        Get Started
                        <span class="btn-icon">→</span>
                    </a>
                </div>
            </div>
        </div>
        <?php includeScripts(); ?>
    </body>
    </html>
    <?php
}

function showRequirements($installer) {
    $requirements = $installer->checkRequirements();
    $allPassed = $requirements['php_version']['passed'];
    foreach ($requirements['extensions'] as $passed) {
        if (!$passed) $allPassed = false;
    }
    foreach ($requirements['permissions'] as $passed) {
        if (!$passed) $allPassed = false;
    }
    ?>
    <!DOCTYPE html>
    <html lang="uz">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Server Requirements - <?= APP_NAME ?></title>
        <?php includeStyles(); ?>
    </head>
    <body>
        <div class="installer-wrapper">
            <div class="installer-container">
                <div class="installer-header">
                    <div class="logo-section">
                        <div class="logo-icon">🚀</div>
                        <h1><?= APP_NAME ?></h1>
                    </div>
                </div>

                <div class="progress-bar">
                    <div class="progress-step completed">
                        <div class="step-number">✓</div>
                        <div class="step-label">Welcome</div>
                    </div>
                    <div class="progress-line active"></div>
                    <div class="progress-step active">
                        <div class="step-number">2</div>
                        <div class="step-label">Requirements</div>
                    </div>
                    <div class="progress-line"></div>
                    <div class="progress-step">
                        <div class="step-number">3</div>
                        <div class="step-label">Database</div>
                    </div>
                    <div class="progress-line"></div>
                    <div class="progress-step">
                        <div class="step-number">4</div>
                        <div class="step-label">Admin</div>
                    </div>
                    <div class="progress-line"></div>
                    <div class="progress-step">
                        <div class="step-number">5</div>
                        <div class="step-label">Finish</div>
                    </div>
                </div>

                <div class="installer-body">
                    <h2>Server Requirements</h2>
                    <p class="subtitle">Checking your server configuration</p>

                    <div class="requirements-section">
                        <h3>PHP Version</h3>
                        <div class="requirement-item">
                            <span class="req-name">Required: PHP <?= $requirements['php_version']['required'] ?>+</span>
                            <span class="req-value"><?= $requirements['php_version']['current'] ?></span>
                            <span class="badge <?= $requirements['php_version']['passed'] ? 'badge-success' : 'badge-error' ?>">
                                <?= $requirements['php_version']['passed'] ? '✓ Passed' : '✗ Failed' ?>
                            </span>
                        </div>
                    </div>

                    <div class="requirements-section">
                        <h3>PHP Extensions</h3>
                        <?php foreach ($requirements['extensions'] as $ext => $passed): ?>
                        <div class="requirement-item">
                            <span class="req-name"><?= $ext ?></span>
                            <span class="badge <?= $passed ? 'badge-success' : 'badge-error' ?>">
                                <?= $passed ? '✓ Enabled' : '✗ Missing' ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="requirements-section">
                        <h3>Directory Permissions</h3>
                        <?php foreach ($requirements['permissions'] as $dir => $writable): ?>
                        <div class="requirement-item">
                            <span class="req-name"><?= $dir ?></span>
                            <span class="badge <?= $writable ? 'badge-success' : 'badge-error' ?>">
                                <?= $writable ? '✓ Writable' : '✗ Not Writable' ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if (!$allPassed): ?>
                    <div class="alert alert-error">
                        <strong>⚠️ Requirements Not Met</strong>
                        <p>Please fix the issues above before continuing. Contact your hosting provider if you need assistance.</p>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="installer-footer">
                    <a href="?step=1" class="btn btn-secondary">
                        <span class="btn-icon">←</span>
                        Back
                    </a>
                    <?php if ($allPassed): ?>
                    <a href="?step=3" class="btn btn-primary">
                        Continue
                        <span class="btn-icon">→</span>
                    </a>
                    <?php else: ?>
                    <a href="?step=2" class="btn btn-secondary">
                        <span class="btn-icon">🔄</span>
                        Check Again
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php includeScripts(); ?>
    </body>
    </html>
    <?php
}

function showDatabaseConfig($installer) {
    ?>
    <!DOCTYPE html>
    <html lang="uz">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Database Configuration - <?= APP_NAME ?></title>
        <?php includeStyles(); ?>
    </head>
    <body>
        <div class="installer-wrapper">
            <div class="installer-container">
                <div class="installer-header">
                    <div class="logo-section">
                        <div class="logo-icon">🚀</div>
                        <h1><?= APP_NAME ?></h1>
                    </div>
                </div>

                <div class="progress-bar">
                    <div class="progress-step completed">
                        <div class="step-number">✓</div>
                        <div class="step-label">Welcome</div>
                    </div>
                    <div class="progress-line completed"></div>
                    <div class="progress-step completed">
                        <div class="step-number">✓</div>
                        <div class="step-label">Requirements</div>
                    </div>
                    <div class="progress-line active"></div>
                    <div class="progress-step active">
                        <div class="step-number">3</div>
                        <div class="step-label">Database</div>
                    </div>
                    <div class="progress-line"></div>
                    <div class="progress-step">
                        <div class="step-number">4</div>
                        <div class="step-label">Admin</div>
                    </div>
                    <div class="progress-line"></div>
                    <div class="progress-step">
                        <div class="step-number">5</div>
                        <div class="step-label">Finish</div>
                    </div>
                </div>

                <div class="installer-body">
                    <h2>Database Configuration</h2>
                    <p class="subtitle">Enter your MySQL database credentials</p>

                    <form method="POST" action="?step=4" class="install-form" id="dbForm">
                        <input type="hidden" name="install_token" value="<?= $_SESSION['install_token'] ?>">

                        <div class="form-group">
                            <label for="app_url">Application URL</label>
                            <input type="url" id="app_url" name="app_url" class="form-control"
                                   value="<?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] ?>"
                                   required>
                            <small class="form-help">Your website URL (e.g., https://yourdomain.com)</small>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="db_host">Database Host</label>
                                <input type="text" id="db_host" name="db_host" class="form-control" value="localhost" required>
                            </div>
                            <div class="form-group">
                                <label for="db_port">Port</label>
                                <input type="text" id="db_port" name="db_port" class="form-control" value="3306" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="db_database">Database Name</label>
                            <input type="text" id="db_database" name="db_database" class="form-control" placeholder="knowhub_community" required>
                            <small class="form-help">If database doesn't exist, we'll create it automatically</small>
                        </div>

                        <div class="form-group">
                            <label for="db_username">Database Username</label>
                            <input type="text" id="db_username" name="db_username" class="form-control" placeholder="root" required>
                        </div>

                        <div class="form-group">
                            <label for="db_password">Database Password</label>
                            <input type="password" id="db_password" name="db_password" class="form-control" placeholder="••••••••">
                            <small class="form-help">Leave empty if no password</small>
                        </div>

                        <div class="info-box">
                            <div class="info-icon">ℹ️</div>
                            <div>
                                <h4>Database Requirements</h4>
                                <ul>
                                    <li>MySQL 5.7+ or MariaDB 10.3+</li>
                                    <li>Database user must have CREATE, ALTER, DROP privileges</li>
                                    <li>UTF8MB4 character set support</li>
                                </ul>
                            </div>
                        </div>

                        <div id="testResult"></div>
                    </form>
                </div>

                <div class="installer-footer">
                    <a href="?step=2" class="btn btn-secondary">
                        <span class="btn-icon">←</span>
                        Back
                    </a>
                    <button type="button" onclick="testConnection()" class="btn btn-secondary" id="testBtn">
                        <span class="btn-icon">🔍</span>
                        Test Connection
                    </button>
                    <button type="submit" form="dbForm" class="btn btn-primary" id="continueBtn">
                        Continue
                        <span class="btn-icon">→</span>
                    </button>
                </div>
            </div>
        </div>
        <?php includeScripts(); ?>
        <script>
        function testConnection() {
            const btn = document.getElementById('testBtn');
            const result = document.getElementById('testResult');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner"></span> Testing...';

            const formData = new FormData(document.getElementById('dbForm'));
            formData.append('test_connection', '1');

            fetch('ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    result.innerHTML = '<div class="alert alert-success">✓ Connection successful! Database is ready.</div>';
                } else {
                    result.innerHTML = '<div class="alert alert-error">✗ Connection failed: ' + data.message + '</div>';
                }
                btn.disabled = false;
                btn.innerHTML = '<span class="btn-icon">🔍</span> Test Connection';
            })
            .catch(error => {
                result.innerHTML = '<div class="alert alert-error">✗ Test failed: ' + error.message + '</div>';
                btn.disabled = false;
                btn.innerHTML = '<span class="btn-icon">🔍</span> Test Connection';
            });
        }
        </script>
    </body>
    </html>
    <?php
}

function handleDatabaseSetup($installer) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ?step=3');
        exit;
    }

    $data = [
        'app_url' => $_POST['app_url'],
        'db_host' => $_POST['db_host'],
        'db_port' => $_POST['db_port'],
        'db_database' => $_POST['db_database'],
        'db_username' => $_POST['db_username'],
        'db_password' => $_POST['db_password']
    ];

    $_SESSION['db_config'] = $data;

    $testResult = $installer->testDatabaseConnection(
        $data['db_host'],
        $data['db_username'],
        $data['db_password'],
        $data['db_database'],
        $data['db_port']
    );

    if (!$testResult['success']) {
        $_SESSION['db_error'] = $testResult['message'];
        header('Location: ?step=3&error=1');
        exit;
    }

    if ($installer->createEnvFile($data)) {
        $installer->generateAppKey();
        header('Location: ?step=5');
    } else {
        $_SESSION['db_error'] = 'Failed to create .env file';
        header('Location: ?step=3&error=1');
    }
    exit;
}

function showAdminConfig() {
    ?>
    <!DOCTYPE html>
    <html lang="uz">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Account - <?= APP_NAME ?></title>
        <?php includeStyles(); ?>
    </head>
    <body>
        <div class="installer-wrapper">
            <div class="installer-container">
                <div class="installer-header">
                    <div class="logo-section">
                        <div class="logo-icon">🚀</div>
                        <h1><?= APP_NAME ?></h1>
                    </div>
                </div>

                <div class="progress-bar">
                    <div class="progress-step completed">
                        <div class="step-number">✓</div>
                        <div class="step-label">Welcome</div>
                    </div>
                    <div class="progress-line completed"></div>
                    <div class="progress-step completed">
                        <div class="step-number">✓</div>
                        <div class="step-label">Requirements</div>
                    </div>
                    <div class="progress-line completed"></div>
                    <div class="progress-step completed">
                        <div class="step-number">✓</div>
                        <div class="step-label">Database</div>
                    </div>
                    <div class="progress-line active"></div>
                    <div class="progress-step active">
                        <div class="step-number">4</div>
                        <div class="step-label">Admin</div>
                    </div>
                    <div class="progress-line"></div>
                    <div class="progress-step">
                        <div class="step-number">5</div>
                        <div class="step-label">Finish</div>
                    </div>
                </div>

                <div class="installer-body">
                    <h2>Create Admin Account</h2>
                    <p class="subtitle">Set up your administrator account</p>

                    <form method="POST" action="?step=6" class="install-form" id="adminForm">
                        <input type="hidden" name="install_token" value="<?= $_SESSION['install_token'] ?>">

                        <div class="form-group">
                            <label for="admin_name">Full Name</label>
                            <input type="text" id="admin_name" name="admin_name" class="form-control" placeholder="John Doe" required>
                        </div>

                        <div class="form-group">
                            <label for="admin_username">Username</label>
                            <input type="text" id="admin_username" name="admin_username" class="form-control" placeholder="admin" required>
                            <small class="form-help">Use lowercase letters, numbers, and underscores only</small>
                        </div>

                        <div class="form-group">
                            <label for="admin_email">Email Address</label>
                            <input type="email" id="admin_email" name="admin_email" class="form-control" placeholder="admin@example.com" required>
                        </div>

                        <div class="form-group">
                            <label for="admin_password">Password</label>
                            <input type="password" id="admin_password" name="admin_password" class="form-control" placeholder="••••••••" required minlength="8">
                            <small class="form-help">Minimum 8 characters</small>
                        </div>

                        <div class="form-group">
                            <label for="admin_password_confirm">Confirm Password</label>
                            <input type="password" id="admin_password_confirm" name="admin_password_confirm" class="form-control" placeholder="••••••••" required>
                        </div>

                        <div class="info-box">
                            <div class="info-icon">🔒</div>
                            <div>
                                <h4>Security Tip</h4>
                                <p>Use a strong password with a mix of uppercase, lowercase, numbers, and special characters.</p>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="installer-footer">
                    <a href="?step=3" class="btn btn-secondary">
                        <span class="btn-icon">←</span>
                        Back
                    </a>
                    <button type="submit" form="adminForm" class="btn btn-primary">
                        Continue
                        <span class="btn-icon">→</span>
                    </button>
                </div>
            </div>
        </div>
        <?php includeScripts(); ?>
        <script>
        document.getElementById('adminForm').addEventListener('submit', function(e) {
            const password = document.getElementById('admin_password').value;
            const confirm = document.getElementById('admin_password_confirm').value;

            if (password !== confirm) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
        });
        </script>
    </body>
    </html>
    <?php
}

function handleAdminSetup($installer) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ?step=5');
        exit;
    }

    $password = $_POST['admin_password'];
    $confirm = $_POST['admin_password_confirm'];

    if ($password !== $confirm) {
        $_SESSION['admin_error'] = 'Passwords do not match';
        header('Location: ?step=5&error=1');
        exit;
    }

    $_SESSION['admin_data'] = [
        'name' => $_POST['admin_name'],
        'username' => $_POST['admin_username'],
        'email' => $_POST['admin_email'],
        'password' => $password
    ];

    header('Location: ?step=7');
    exit;
}

function showFinalize($installer) {
    ?>
    <!DOCTYPE html>
    <html lang="uz">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Finalize Installation - <?= APP_NAME ?></title>
        <?php includeStyles(); ?>
    </head>
    <body>
        <div class="installer-wrapper">
            <div class="installer-container">
                <div class="installer-header">
                    <div class="logo-section">
                        <div class="logo-icon">🚀</div>
                        <h1><?= APP_NAME ?></h1>
                    </div>
                </div>

                <div class="progress-bar">
                    <div class="progress-step completed">
                        <div class="step-number">✓</div>
                        <div class="step-label">Welcome</div>
                    </div>
                    <div class="progress-line completed"></div>
                    <div class="progress-step completed">
                        <div class="step-number">✓</div>
                        <div class="step-label">Requirements</div>
                    </div>
                    <div class="progress-line completed"></div>
                    <div class="progress-step completed">
                        <div class="step-number">✓</div>
                        <div class="step-label">Database</div>
                    </div>
                    <div class="progress-line completed"></div>
                    <div class="progress-step completed">
                        <div class="step-number">✓</div>
                        <div class="step-label">Admin</div>
                    </div>
                    <div class="progress-line active"></div>
                    <div class="progress-step active">
                        <div class="step-number">5</div>
                        <div class="step-label">Finish</div>
                    </div>
                </div>

                <div class="installer-body">
                    <h2>Finalizing Installation</h2>
                    <p class="subtitle">Please wait while we set up your application</p>

                    <div class="install-progress">
                        <div class="progress-item" id="step-migration">
                            <div class="progress-icon">⏳</div>
                            <div class="progress-text">Running database migrations...</div>
                        </div>
                        <div class="progress-item" id="step-admin">
                            <div class="progress-icon">⏳</div>
                            <div class="progress-text">Creating admin account...</div>
                        </div>
                        <div class="progress-item" id="step-optimize">
                            <div class="progress-icon">⏳</div>
                            <div class="progress-text">Optimizing application...</div>
                        </div>
                        <div class="progress-item" id="step-finalize">
                            <div class="progress-icon">⏳</div>
                            <div class="progress-text">Finalizing installation...</div>
                        </div>
                    </div>

                    <div id="installResult" style="display: none;"></div>
                </div>

                <div class="installer-footer" id="footer" style="display: none;">
                    <a href="../" class="btn btn-primary btn-lg">
                        <span class="btn-icon">🎉</span>
                        Visit Your Website
                    </a>
                </div>
            </div>
        </div>
        <?php includeScripts(); ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            runInstallation();
        });

        async function runInstallation() {
            try {
                await updateStep('step-migration', 'Running migrations...');
                await fetch('ajax.php?action=migrate', {method: 'POST', body: new FormData()});
                await completeStep('step-migration', 'Migrations completed');

                await updateStep('step-admin', 'Creating admin...');
                const adminData = new FormData();
                adminData.append('action', 'create_admin');
                <?php if (isset($_SESSION['admin_data'])): ?>
                adminData.append('name', '<?= $_SESSION['admin_data']['name'] ?>');
                adminData.append('username', '<?= $_SESSION['admin_data']['username'] ?>');
                adminData.append('email', '<?= $_SESSION['admin_data']['email'] ?>');
                adminData.append('password', '<?= $_SESSION['admin_data']['password'] ?>');
                <?php endif; ?>
                await fetch('ajax.php', {method: 'POST', body: adminData});
                await completeStep('step-admin', 'Admin account created');

                await updateStep('step-optimize', 'Optimizing...');
                await fetch('ajax.php?action=optimize', {method: 'POST'});
                await completeStep('step-optimize', 'Optimization completed');

                await updateStep('step-finalize', 'Finalizing...');
                await fetch('ajax.php?action=finalize', {method: 'POST'});
                await completeStep('step-finalize', 'Installation completed!');

                document.getElementById('installResult').innerHTML =
                    '<div class="alert alert-success">' +
                    '<h3>🎉 Installation Completed Successfully!</h3>' +
                    '<p>Your application is ready to use.</p>' +
                    '</div>';
                document.getElementById('installResult').style.display = 'block';
                document.getElementById('footer').style.display = 'flex';

            } catch (error) {
                document.getElementById('installResult').innerHTML =
                    '<div class="alert alert-error">' +
                    '<h3>❌ Installation Failed</h3>' +
                    '<p>' + error.message + '</p>' +
                    '<a href="?step=1" class="btn btn-secondary">Start Over</a>' +
                    '</div>';
                document.getElementById('installResult').style.display = 'block';
            }
        }

        function updateStep(id, text) {
            const el = document.getElementById(id);
            el.querySelector('.progress-icon').textContent = '⏳';
            el.querySelector('.progress-text').textContent = text;
            el.classList.add('active');
            return new Promise(resolve => setTimeout(resolve, 500));
        }

        function completeStep(id, text) {
            const el = document.getElementById(id);
            el.querySelector('.progress-icon').textContent = '✓';
            el.querySelector('.progress-text').textContent = text;
            el.classList.remove('active');
            el.classList.add('completed');
            return new Promise(resolve => setTimeout(resolve, 500));
        }
        </script>
    </body>
    </html>
    <?php
}

function includeStyles() {
    ?>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .installer-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 40px);
        }
        .installer-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 900px;
            width: 100%;
            overflow: hidden;
        }
        .installer-header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .logo-section { }
        .logo-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .installer-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .version {
            opacity: 0.9;
            font-size: 14px;
        }
        .progress-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
            background: #f9fafb;
        }
        .progress-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #6b7280;
            transition: all 0.3s;
        }
        .progress-step.active .step-number {
            background: #4f46e5;
            color: white;
            transform: scale(1.1);
        }
        .progress-step.completed .step-number {
            background: #10b981;
            color: white;
        }
        .step-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 500;
        }
        .progress-line {
            width: 60px;
            height: 2px;
            background: #e5e7eb;
            margin: 0 10px;
        }
        .progress-line.active { background: #4f46e5; }
        .progress-line.completed { background: #10b981; }
        .installer-body {
            padding: 40px;
        }
        .installer-body h2 {
            font-size: 24px;
            margin-bottom: 8px;
            color: #111827;
        }
        .subtitle {
            color: #6b7280;
            margin-bottom: 30px;
        }
        .welcome-content { }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .feature-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            transition: all 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .feature-icon {
            font-size: 32px;
            margin-bottom: 12px;
        }
        .feature-card h3 {
            font-size: 16px;
            margin-bottom: 8px;
            color: #111827;
        }
        .feature-card p {
            font-size: 14px;
            color: #6b7280;
        }
        .info-box, .alert {
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            display: flex;
            gap: 15px;
        }
        .info-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
        }
        .alert-success {
            background: #d1fae5;
            border: 1px solid #6ee7b7;
        }
        .alert-error {
            background: #fee2e2;
            border: 1px solid #fca5a5;
        }
        .info-icon {
            font-size: 24px;
            flex-shrink: 0;
        }
        .info-box h4, .alert h3 {
            font-size: 16px;
            margin-bottom: 8px;
            color: #111827;
        }
        .info-box ul {
            margin-left: 20px;
        }
        .info-box li, .alert p {
            font-size: 14px;
            color: #374151;
            margin-bottom: 4px;
        }
        .requirements-section {
            margin-bottom: 30px;
        }
        .requirements-section h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #111827;
        }
        .requirement-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 8px;
        }
        .req-name {
            font-size: 14px;
            color: #374151;
            flex: 1;
        }
        .req-value {
            font-size: 14px;
            color: #6b7280;
            margin-right: 12px;
        }
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }
        .badge-error {
            background: #fee2e2;
            color: #991b1b;
        }
        .install-form { }
        .form-group {
            margin-bottom: 20px;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
            font-size: 14px;
        }
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }
        .form-control:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        .form-help {
            display: block;
            margin-top: 4px;
            font-size: 12px;
            color: #6b7280;
        }
        .install-progress {
            margin: 30px 0;
        }
        .progress-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 12px;
            background: #f9fafb;
        }
        .progress-item.active {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
        }
        .progress-item.completed {
            background: #d1fae5;
        }
        .progress-icon {
            font-size: 24px;
        }
        .progress-text {
            font-size: 14px;
            color: #374151;
        }
        .installer-footer {
            padding: 30px 40px;
            background: #f9fafb;
            display: flex;
            justify-content: space-between;
            gap: 12px;
        }
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }
        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
        }
        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
        }
        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }
        .btn-secondary:hover:not(:disabled) {
            background: #d1d5db;
        }
        .btn-lg {
            padding: 16px 32px;
            font-size: 16px;
        }
        .btn-icon { }
        .spinner {
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            display: inline-block;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        @media (max-width: 768px) {
            .feature-grid { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
            .installer-footer { flex-direction: column; }
            .progress-bar { flex-wrap: wrap; }
            .progress-line { display: none; }
        }
    </style>
    <?php
}

function includeScripts() {
    ?>
    <script>
    console.log('<?= APP_NAME ?> Installer v<?= INSTALLER_VERSION ?>');
    </script>
    <?php
}
