<?php
// controllers/StudentController.php
class StudentController {
    private $db;
    private $student;
    private $course;
    private $semester;
    private $enrollment;

    public function __construct() {
        session_start();
        if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'student') {
            header('Location: index.php');
            exit();
        }

        $database = new Database();
        $this->db = $database->getConnection();
        $this->student = new Student($this->db);
        $this->course = new Course($this->db);
        $this->semester = new Semester($this->db);
        $this->enrollment = new Enrollment($this->db);
    }

    public function dashboard() {
        $student_info = $this->student->getStudentById($_SESSION['user_id']);
        $current_semester = $this->semester->getCurrentSemester();
        $enrollments = $this->enrollment->getStudentEnrollments($_SESSION['user_id'], 
            $current_semester ? $current_semester['semester_id'] : null);
        
        include_once 'views/student/dashboard.php';
    }

    public function enroll() {
        if($_POST && isset($_POST['action'])) {
            if($_POST['action'] === 'enroll') {
                $result = $this->enrollment->enrollStudent($_SESSION['user_id'], $_POST['course_id'], $_POST['semester_id']);
                $message = $result ? "Successfully enrolled!" : "Enrollment failed. Course may be full or you're already enrolled.";
                $message_type = $result ? "success" : "error";
            }
        }

        $open_semesters = $this->semester->getRegistrationOpenSemesters();
        $available_courses = [];
        
        if(!empty($open_semesters)) {
            foreach($open_semesters as $semester) {
                $available_courses[$semester['semester_id']] = $this->course->getAvailableCourses($semester['semester_id']);
            }
        }
        
        include_once 'views/student/enroll.php';
    }

    public function myEnrollments() {
        if($_POST && isset($_POST['action']) && $_POST['action'] === 'drop') {
            $this->enrollment->dropCourse($_POST['enrollment_id']);
            header('Location: index.php?controller=student&action=myEnrollments');
            exit();
        }

        $enrollments = $this->enrollment->getStudentEnrollments($_SESSION['user_id']);
        include_once 'views/student/enrollments.php';
    }

    public function profile() {
        $student_info = $this->student->getStudentById($_SESSION['user_id']);
        include_once 'views/student/profile.php';
    }
}
?>