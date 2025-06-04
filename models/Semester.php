<?php
class Semester {
    private $conn;
    private $table = 'semesters';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllSemesters() {
        $query = "SELECT * FROM {$this->table} ORDER BY start_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCurrentSemester() {
        $query = "SELECT * FROM {$this->table} WHERE CURDATE() BETWEEN start_date AND end_date LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRegistrationOpenSemesters() {
        $query = "SELECT * FROM {$this->table} WHERE CURDATE() BETWEEN registration_start AND registration_end ORDER BY start_date";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createSemester($data) {
        $query = "INSERT INTO {$this->table} (semester_id, semester_name, start_date, end_date, registration_start, registration_end) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$data['semester_id'], $data['semester_name'], $data['start_date'], $data['end_date'], $data['registration_start'], $data['registration_end']]);
    }

    public function updateSemester($id, $data) {
        $query = "UPDATE {$this->table} SET semester_name = ?, start_date = ?, end_date = ?, registration_start = ?, registration_end = ? WHERE semester_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$data['semester_name'], $data['start_date'], $data['end_date'], $data['registration_start'], $data['registration_end'], $id]);
    }

    public function deleteSemester($id) {
        $query = "DELETE FROM {$this->table} WHERE semester_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>