<?php
// session_start();

// Auto-load classes
spl_autoload_register(function ($class_name) {
    $directories = ['models/', 'controllers/', 'config/'];
    foreach ($directories as $directory) {
        $file = $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Get controller and action from URL
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'auth';
$action = isset($_GET['action']) ? $_GET['action'] : 'showLogin';

// Route to appropriate controller
try {
    switch($controller) {
        case 'auth':
            $auth = new AuthController();
            if(method_exists($auth, $action)) {
                $auth->$action();
            }
            break;
            
        case 'admin':
            $admin = new AdminController();
            if(method_exists($admin, $action)) {
                $admin->$action();
            }
            break;
            
        case 'student':
            $student = new StudentController();
            if(method_exists($student, $action)) {
                $student->$action();
            }
            break;
            
        default:
            header('Location: index.php');
            break;
    }
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>