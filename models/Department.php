<?php
class Department {
    private $conn;
    private $table = 'departments';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllDepartments() {
        $query = "SELECT * FROM {$this->table} ORDER BY department_name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDepartmentById($id) {
        $query = "SELECT * FROM {$this->table} WHERE department_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createDepartment($data) {
        $query = "INSERT INTO {$this->table} (department_name, department_head, office_location, phone_number, email) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$data['department_name'], $data['department_head'], $data['office_location'], $data['phone_number'], $data['email']]);
    }

    public function updateDepartment($id, $data) {
        $query = "UPDATE {$this->table} SET department_name = ?, department_head = ?, office_location = ?, phone_number = ?, email = ? WHERE department_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$data['department_name'], $data['department_head'], $data['office_location'], $data['phone_number'], $data['email'], $id]);
    }

    public function deleteDepartment($id) {
        $query = "DELETE FROM {$this->table} WHERE department_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}

?>