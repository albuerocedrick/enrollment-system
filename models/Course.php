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

    public function getAvailableCourses($semester_id, $student_id = null) {
        $query = "SELECT c.*, d.department_name, cs.days, cs.start_time, cs.end_time, cs.location,
                         COUNT(e.enrollment_id) as current_enrollment,
                         e2.status as student_enrollment_status,
                         CASE 
                             WHEN c.prerequisites IS NULL OR TRIM(c.prerequisites) = '' OR c.prerequisites = 'None' THEN 1
                             ELSE (SELECT COUNT(*) FROM enrollments e3
                                   JOIN courses c2 ON e3.course_id = c2.course_id
                                   WHERE e3.student_id = ? AND c2.course_code = c.prerequisites AND e3.status = 'Completed')
                         END as prerequisites_met
                  FROM {$this->table} c 
                  LEFT JOIN departments d ON c.department_id = d.department_id
                  LEFT JOIN class_schedule cs ON c.course_id = cs.course_id AND cs.semester_id = ?
                  LEFT JOIN enrollments e ON c.course_id = e.course_id AND e.semester_id = ? AND e.status = 'Active'
                  LEFT JOIN enrollments e2 ON c.course_id = e2.course_id AND e2.semester_id = ? AND e2.student_id = ?
                  JOIN semesters s ON cs.semester_id = s.semester_id
                  WHERE c.is_active = 1 AND cs.semester_id = ?
                  AND CURDATE() BETWEEN s.registration_start AND s.registration_end
                  GROUP BY c.course_id, cs.schedule_id
                  ORDER BY c.course_code";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$student_id, $semester_id, $semester_id, $semester_id, $student_id, $semester_id]);
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