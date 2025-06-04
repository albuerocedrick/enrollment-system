<?php
class AuthController {
    private $db;
    private $user;
    private $department;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
        $this->department = new Department($this->db);
    }

    public function showLogin() {
        include_once 'views/auth/login.php';
    }

    public function login() {
        if($_POST) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $userType = $_POST['user_type'];

            $user = $this->user->login($username, $password, $userType);
            
            if($user) {
                session_start();
                $_SESSION['user_id'] = $user[$userType === 'admin' ? 'admin_id' : 'student_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_type'] = $userType;

                if($userType === 'admin') {
                    header('Location: index.php?controller=admin&action=dashboard');
                } else {
                    header('Location: index.php?controller=student&action=dashboard');
                }
                exit();
            } else {
                $error = "Invalid username or password";
                include_once 'views/auth/login.php';
            }
        }
    }

    public function showSignup() {
        $departments = $this->department->getAllDepartments();
        include_once 'views/auth/signup.php';
    }

    public function signup() {
        if($_POST) {
            $data = [
                'username' => $_POST['username'],
                'password' => $_POST['password'],
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'date_of_birth' => !empty($_POST['date_of_birth']) ? $_POST['date_of_birth'] : null,
                'phone_number' => !empty($_POST['phone_number']) ? $_POST['phone_number'] : null,
                'address' => !empty($_POST['address']) ? $_POST['address'] : null,
                'major_department_id' => !empty($_POST['major_department_id']) ? (int)$_POST['major_department_id'] : null
            ];

            $result = $this->user->signup($data);
            $departments = $this->department->getAllDepartments();

            if($result['success']) {
                $success = $result['message'];
                include_once 'views/auth/signup.php';
            } else {
                $error = $result['error'];
                include_once 'views/auth/signup.php';
            }
        } else {
            $this->showSignup();
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: index.php');
        exit();
    }
}
?>