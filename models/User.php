<?php
class User {
    private $conn;
    private $table_admin = 'admin';
    private $table_students = 'students';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($username, $password, $userType) {
        $table = ($userType === 'admin') ? $this->table_admin : $this->table_students;
        $id_field = ($userType === 'admin') ? 'admin_id' : 'student_id';
        
        $query = "SELECT {$id_field}, username, password, first_name, last_name FROM {$table} WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $username);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if(password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }

    public function signup($data) {
        // Check if username exists
        $query = "SELECT student_id FROM {$this->table_students} WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $data['username']);
        $stmt->execute();
        if($stmt->rowCount() > 0) {
            return ['success' => false, 'error' => 'Username already exists'];
        }

        // Check if email exists
        $query = "SELECT student_id FROM {$this->table_students} WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $data['email']);
        $stmt->execute();
        if($stmt->rowCount() > 0) {
            return ['success' => false, 'error' => 'Email already exists'];
        }

        // Insert new student
        $query = "INSERT INTO {$this->table_students} 
                  (username, password, first_name, last_name, date_of_birth, email, phone_number, address, major_department_id, current_status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active')";
        $stmt = $this->conn->prepare($query);
        
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt->bindParam(1, $data['username']);
        $stmt->bindParam(2, $hashed_password);
        $stmt->bindParam(3, $data['first_name']);
        $stmt->bindParam(4, $data['last_name']);
        $stmt->bindParam(5, $data['date_of_birth']);
        $stmt->bindParam(6, $data['email']);
        $stmt->bindParam(7, $data['phone_number']);
        $stmt->bindParam(8, $data['address']);
        // Handle nullable major_department_id properly
        $major_dept_id = !empty($data['major_department_id']) ? $data['major_department_id'] : null;
        $stmt->bindParam(9, $major_dept_id, PDO::PARAM_INT);

        if($stmt->execute()) {
            return ['success' => true, 'message' => 'Account created successfully! Please log in.'];
        } else {
            return ['success' => false, 'error' => 'Failed to create account. Please try again.'];
        }
    }
}
?>