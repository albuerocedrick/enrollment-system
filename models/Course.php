<?php
class Course {
    private $conn;
    private $table = 'courses';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllCourses() {
        $query = "SELECT c.*, d.department_name 
                  FROM {$this->table} c 
                  LEFT JOIN departments d ON c.department_id = d.department_id 
                  ORDER BY c.course_code";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvailableCourses($semester_id) {
        $query = "SELECT c.*, d.department_name, cs.days, cs.start_time, cs.end_time, cs.location
                  FROM {$this->table} c 
                  JOIN departments d ON c.department_id = d.department_id
                  JOIN class_schedule cs ON c.course_id = cs.course_id
                  JOIN semesters s ON cs.semester_id = s.semester_id
                  WHERE c.is_active = 1 AND cs.semester_id = ?
                  AND CURDATE() BETWEEN s.registration_start AND s.registration_end
                  ORDER BY c.course_code";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $semester_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCourseById($id) {
        $query = "SELECT c.*, d.department_name 
                  FROM {$this->table} c 
                  LEFT JOIN departments d ON c.department_id = d.department_id 
                  WHERE c.course_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createCourse($data) {
        $query = "INSERT INTO {$this->table} (course_code, course_name, description, credits, department_id, max_capacity, prerequisites, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$data['course_code'], $data['course_name'], $data['description'], $data['credits'], $data['department_id'], $data['max_capacity'], $data['prerequisites'], $data['is_active']]);
    }

    public function updateCourse($id, $data) {
        $query = "UPDATE {$this->table} SET course_code = ?, course_name = ?, description = ?, credits = ?, department_id = ?, max_capacity = ?, prerequisites = ?, is_active = ? WHERE course_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$data['course_code'], $data['course_name'], $data['description'], $data['credits'], $data['department_id'], $data['max_capacity'], $data['prerequisites'], $data['is_active'], $id]);
    }

    public function deleteCourse($id) {
        $query = "DELETE FROM {$this->table} WHERE course_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>