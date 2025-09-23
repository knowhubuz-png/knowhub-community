<?php
/**
 * KnowHub Community - Installation Script
 * Shared Hosting uchun o'rnatish skripti
 */

session_start();
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$error = '';
$success = '';

// Step 1: Welcome
function showWelcome() {
    ?>
    <!DOCTYPE html>
    <html lang="uz">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>KnowHub Community - O'rnatish</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <style>
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            .install-container {
                max-width: 800px;
                margin: 50px auto;
                background: white;
                border-radius: 15px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                overflow: hidden;
            }
            .install-header {
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
                color: white;
                padding: 40px;
                text-align: center;
            }
            .install-header h1 {
                margin: 0;
                font-size: 2.5rem;
                font-weight: 700;
            }
            .install-header p {
                margin: 10px 0 0 0;
                font-size: 1.1rem;
                opacity: 0.9;
            }
            .install-body {
                padding: 40px;
            }
            .feature-card {
                border: 1px solid #e5e7eb;
                border-radius: 10px;
                padding: 20px;
                margin-bottom: 20px;
                transition: all 0.3s ease;
            }
            .feature-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            }
            .feature-icon {
                font-size: 2rem;
                color: #4f46e5;
                margin-bottom: 15px;
            }
            .btn-next {
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
                border: none;
                padding: 15px 40px;
                font-size: 1.1rem;
                font-weight: 600;
                border-radius: 50px;
                color: white;
                transition: all 0.3s ease;
            }
            .btn-next:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
                color: white;
            }
            .progress-steps {
                display: flex;
                justify-content: center;
                margin-bottom: 30px;
            }
            .step {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: #e5e7eb;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 10px;
                font-weight: bold;
                color: #6b7280;
            }
            .step.active {
                background: #4f46e5;
                color: white;
            }
        </style>
    </head>
    <body>
        <div class="install-container">
            <div class="install-header">
                <h1><i class="fas fa-rocket"></i> KnowHub Community</h1>
                <p>Shared Hosting uchun o'rnatish skripti</p>
            </div>
            <div class="install-body">
                <div class="progress-steps">
                    <div class="step active">1</div>
                    <div class="step">2</div>
                    <div class="step">3</div>
                    <div class="step">4</div>
                    <div class="step">5</div>
                </div>
                
                <h2 class="text-center mb-4">Xush kelibsiz!</h2>
                <p class="text-center text-muted mb-4">
                    Bu skript sizga KnowHub Community ni shared hostingga oson o'rnatishga yordam beradi.
                    Iltimos, quyidagi talablarni tekshiring va keyingi qadamga o'ting.
                </p>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="feature-card text-center">
                            <div class="feature-icon">
                                <i class="fas fa-server"></i>
                            </div>
                            <h5>Server Tekshiruvi</h5>
                            <p class="text-muted">PHP versiyasi, extensions va server konfiguratsiyasini tekshirish</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card text-center">
                            <div class="feature-icon">
                                <i class="fas fa-folder-open"></i>
                            </div>
                            <h5>Ruxsatlar</h5>
                            <p class="text-muted">Papka va fayllar uchun kerakli ruxsatlarni sozlash</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card text-center">
                            <div class="feature-icon">
                                <i class="fas fa-database"></i>
                            </div>
                            <h5>Database</h5>
                            <p class="text-muted">Ma'lumotlar bazasi sozlamalari va migratsiyalar</p>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="feature-card text-center">
                            <div class="feature-icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <h5>Admin Hisobi</h5>
                            <p class="text-muted">Administrator hisobini yaratish</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="feature-card text-center">
                            <div class="feature-icon">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <h5>Yakunlash</h5>
                            <p class="text-muted">O'rnatishni yakunlash va konfiguratsiya</p>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-5">
                    <a href="?step=2" class="btn btn-next">
                        <i class="fas fa-arrow-right"></i> Boshlash
                    </a>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
}

// Step 2: Server Requirements Check
function checkServerRequirements() {
    $requirements = [
        'php_version' => [
            'required' => '8.2.0',
            'current' => PHP_VERSION,
            'passed' => version_compare(PHP_VERSION, '8.2.0', '>=')
        ],
        'extensions' => [
            'mysqli' => extension_loaded('mysqli'),
            'pdo_mysql' => extension_loaded('pdo_mysql'),
            'openssl' => extension_loaded('openssl'),
            'mbstring' => extension_loaded('mbstring'),
            'tokenizer' => extension_loaded('tokenizer'),
            'xml' => extension_loaded('xml'),
            'ctype' => extension_loaded('ctype'),
            'json' => extension_loaded('json'),
            'bcmath' => extension_loaded('bcmath'),
            'curl' => extension_loaded('curl'),
            'zip' => extension_loaded('zip'),
            'fileinfo' => extension_loaded('fileinfo'),
        ]
    ];
    
    $php_ini = [
        'memory_limit' => ini_get('memory_limit'),
        'max_execution_time' => ini_get('max_execution_time'),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
    ];
    
    ?>
    <!DOCTYPE html>
    <html lang="uz">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Server Talablari - KnowHub Community</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <style>
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            .install-container {
                max-width: 900px;
                margin: 50px auto;
                background: white;
                border-radius: 15px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                overflow: hidden;
            }
            .install-header {
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
                color: white;
                padding: 30px;
                text-align: center;
            }
            .install-body {
                padding: 30px;
            }
            .progress-steps {
                display: flex;
                justify-content: center;
                margin-bottom: 30px;
            }
            .step {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: #e5e7eb;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 10px;
                font-weight: bold;
                color: #6b7280;
            }
            .step.active {
                background: #4f46e5;
                color: white;
            }
            .step.completed {
                background: #10b981;
                color: white;
            }
            .requirement-card {
                border: 1px solid #e5e7eb;
                border-radius: 10px;
                padding: 20px;
                margin-bottom: 20px;
            }
            .requirement-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 0;
                border-bottom: 1px solid #f3f4f6;
            }
            .requirement-item:last-child {
                border-bottom: none;
            }
            .status-badge {
                padding: 5px 12px;
                border-radius: 20px;
                font-size: 0.85rem;
                font-weight: 600;
            }
            .status-success {
                background: #d1fae5;
                color: #065f46;
            }
            .status-error {
                background: #fee2e2;
                color: #991b1b;
            }
            .btn-nav {
                padding: 12px 30px;
                border-radius: 25px;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.3s ease;
            }
            .btn-prev {
                background: #6b7280;
                color: white;
            }
            .btn-prev:hover {
                background: #4b5563;
                color: white;
            }
            .btn-next {
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
                color: white;
            }
            .btn-next:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
                color: white;
            }
            .btn-next:disabled {
                background: #9ca3af;
                cursor: not-allowed;
                transform: none;
                box-shadow
