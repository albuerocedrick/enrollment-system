<?php
// models/Student.php
class Student {
    private $conn;
    private $table = 'students';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllStudents() {
        $query = "SELECT s.*, d.department_name 
                  FROM {$this->table} s 
                  LEFT JOIN departments d ON s.major_department_id = d.department_id 
                  ORDER BY s.last_name, s.first_name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudentById($id) {
        $query = "SELECT s.*, d.department_name 
                  FROM {$this->table} s 
                  LEFT JOIN departments d ON s.major_department_id = d.department_id 
                  WHERE s.student_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createStudent($data) {
        $query = "INSERT INTO {$this->table} 
                  (username, password, first_name, last_name, date_of_birth, email, phone_number, address, major_department_id, current_status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        
        return $stmt->execute([
            $data['username'], $hashed_password, $data['first_name'], $data['last_name'],
            $data['date_of_birth'], $data['email'], $data['phone_number'], 
            $data['address'], $data['major_department_id'], $data['current_status']
        ]);
    }

    public function updateStudent($id, $data) {
        $query = "UPDATE {$this->table} SET 
                  username = ?, first_name = ?, last_name = ?, date_of_birth = ?, 
                  email = ?, phone_number = ?, address = ?, major_department_id = ?, current_status = ? 
                  WHERE student_id = ?";
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            $data['username'], $data['first_name'], $data['last_name'], $data['date_of_birth'],
            $data['email'], $data['phone_number'], $data['address'], 
            $data['major_department_id'], $data['current_status'], $id
        ]);
    }

    public function deleteStudent($id) {
        $query = "DELETE FROM {$this->table} WHERE student_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>