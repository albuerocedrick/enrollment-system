<?php
class ClassSchedule {
    private $conn;
    private $table = 'class_schedule';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllSchedules() {
        $query = "SELECT cs.*, c.course_code, c.course_name, s.semester_name 
                  FROM {$this->table} cs 
                  JOIN courses c ON cs.course_id = c.course_id 
                  JOIN semesters s ON cs.semester_id = s.semester_id 
                  ORDER BY c.course_code, s.start_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createSchedule($data) {
        $query = "INSERT INTO {$this->table} (course_id, semester_id, days, start_time, end_time, location) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$data['course_id'], $data['semester_id'], $data['days'], $data['start_time'], $data['end_time'], $data['location']]);
    }

    public function updateSchedule($id, $data) {
        $query = "UPDATE {$this->table} SET course_id = ?, semester_id = ?, days = ?, start_time = ?, end_time = ?, location = ? WHERE schedule_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$data['course_id'], $data['semester_id'], $data['days'], $data['start_time'], $data['end_time'], $data['location'], $id]);
    }

    public function deleteSchedule($id) {
        $query = "DELETE FROM {$this->table} WHERE schedule_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>