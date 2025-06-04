<?php
class Enrollment {
    private $conn;
    private $table = 'enrollments';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function enrollStudent($student_id, $course_id, $semester_id) {
        // Check if already enrolled
        $check_query = "SELECT * FROM {$this->table} WHERE student_id = ? AND course_id = ? AND semester_id = ? AND status = 'Active'";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->execute([$student_id, $course_id, $semester_id]);
        
        if($check_stmt->rowCount() > 0) {
            return false; // Already enrolled
        }

        // Check capacity
        $capacity_query = "SELECT c.max_capacity, COUNT(e.enrollment_id) as current_enrollment
                          FROM courses c
                          LEFT JOIN enrollments e ON c.course_id = e.course_id AND e.semester_id = ? AND e.status = 'Active'
                          WHERE c.course_id = ?
                          GROUP BY c.course_id, c.max_capacity";
        $capacity_stmt = $this->conn->prepare($capacity_query);
        $capacity_stmt->execute([$semester_id, $course_id]);
        $capacity_data = $capacity_stmt->fetch(PDO::FETCH_ASSOC);
        
        if($capacity_data && $capacity_data['current_enrollment'] >= $capacity_data['max_capacity']) {
            return false; // Course full
        }

        // Enroll student
        $query = "INSERT INTO {$this->table} (student_id, course_id, semester_id, status) VALUES (?, ?, ?, 'Active')";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$student_id, $course_id, $semester_id]);
    }

    public function getStudentEnrollments($student_id, $semester_id = null) {
        $query = "SELECT e.*, c.course_code, c.course_name, c.credits, s.semester_name, cs.days, cs.start_time, cs.end_time, cs.location
                  FROM {$this->table} e
                  JOIN courses c ON e.course_id = c.course_id
                  JOIN semesters s ON e.semester_id = s.semester_id
                  LEFT JOIN class_schedule cs ON c.course_id = cs.course_id AND e.semester_id = cs.semester_id
                  WHERE e.student_id = ?";
        
        if($semester_id) {
            $query .= " AND e.semester_id = ?";
        }
        
        $query .= " ORDER BY s.start_date DESC, c.course_code";
        
        $stmt = $this->conn->prepare($query);
        if($semester_id) {
            $stmt->execute([$student_id, $semester_id]);
        } else {
            $stmt->execute([$student_id]);
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dropCourse($enrollment_id) {
        $query = "UPDATE {$this->table} SET status = 'Dropped' WHERE enrollment_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$enrollment_id]);
    }

    public function getAllEnrollments() {
        $query = "SELECT e.*, s.first_name, s.last_name, c.course_code, c.course_name, sem.semester_name
                  FROM {$this->table} e
                  JOIN students s ON e.student_id = s.student_id
                  JOIN courses c ON e.course_id = c.course_id
                  JOIN semesters sem ON e.semester_id = sem.semester_id
                  ORDER BY e.enrollment_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>