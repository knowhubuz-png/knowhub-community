<?php
/**
 * AJAX Handler for Installation Wizard
 */

session_start();
header('Content-Type: application/json');

define('ROOT_PATH', dirname(__DIR__));

require_once 'index.php';

$installer = new Installer();

if (isset($_POST['test_connection'])) {
    $result = $installer->testDatabaseConnection(
        $_POST['db_host'],
        $_POST['db_username'],
        $_POST['db_password'],
        $_POST['db_database'],
        $_POST['db_port']
    );
    echo json_encode($result);
    exit;
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    try {
        switch ($action) {
            case 'migrate':
                $output = $installer->runMigrations();
                echo json_encode(['success' => true, 'output' => $output]);
                break;

            case 'seed':
                $output = $installer->runSeeder();
                echo json_encode(['success' => true, 'output' => $output]);
                break;

            case 'optimize':
                $results = $installer->optimizeApp();
                echo json_encode(['success' => true, 'results' => $results]);
                break;

            case 'finalize':
                $success = $installer->finalizeInstallation();
                echo json_encode(['success' => $success]);
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Unknown action']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    try {
        switch ($action) {
            case 'create_admin':
                if (isset($_SESSION['admin_data'])) {
                    $data = $_SESSION['admin_data'];
                } else {
                    $data = [
                        'name' => $_POST['name'],
                        'username' => $_POST['username'],
                        'email' => $_POST['email'],
                        'password' => $_POST['password']
                    ];
                }

                $result = $installer->createAdminUser(
                    $data['name'],
                    $data['email'],
                    $data['username'],
                    $data['password']
                );
                echo json_encode($result);
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Unknown action']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'No action specified']);
