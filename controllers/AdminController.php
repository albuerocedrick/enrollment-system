<?php
// controllers/AdminController.php
class AdminController {
    private $db;
    private $student;
    private $department;
    private $course;
    private $semester;
    private $enrollment;

    public function __construct() {
        session_start();
        if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            header('Location: index.php');
            exit();
        }

        $database = new Database();
        $this->db = $database->getConnection();
        $this->student = new Student($this->db);
        $this->department = new Department($this->db);
        $this->course = new Course($this->db);
        $this->semester = new Semester($this->db);
        $this->enrollment = new Enrollment($this->db);
    }

    public function dashboard() {
        $students = $this->student->getAllStudents();
        $departments = $this->department->getAllDepartments();
        $courses = $this->course->getAllCourses();
        $enrollments = $this->enrollment->getAllEnrollments();
        
        include_once 'views/admin/dashboard.php';
    }

    public function manageDepartments() {
        if($_POST && isset($_POST['action'])) {
            switch($_POST['action']) {
                case 'create':
                    $this->department->createDepartment($_POST);
                    break;
                case 'update':
                    $this->department->updateDepartment($_POST['department_id'], $_POST);
                    break;
                case 'delete':
                    $this->department->deleteDepartment($_POST['department_id']);
                    break;
            }
            header('Location: index.php?controller=admin&action=manageDepartments');
            exit();
        }
        
        $departments = $this->department->getAllDepartments();
        include_once 'views/admin/departments.php';
    }

    public function manageCourses() {
        if($_POST && isset($_POST['action'])) {
            switch($_POST['action']) {
                case 'create':
                    $this->course->createCourse($_POST);
                    break;
                case 'update':
                    $this->course->updateCourse($_POST['course_id'], $_POST);
                    break;
                case 'delete':
                    $this->course->deleteCourse($_POST['course_id']);
                    break;
            }
            header('Location: index.php?controller=admin&action=manageCourses');
            exit();
        }
        
        $courses = $this->course->getAllCourses();
        $departments = $this->department->getAllDepartments();
        include_once 'views/admin/courses.php';
    }

    public function manageStudents() {
        if($_POST && isset($_POST['action'])) {
            switch($_POST['action']) {
                case 'create':
                    $this->student->createStudent($_POST);
                    break;
                case 'update':
                    $this->student->updateStudent($_POST['student_id'], $_POST);
                    break;
                case 'delete':
                    $this->student->deleteStudent($_POST['student_id']);
                    break;
            }
            header('Location: index.php?controller=admin&action=manageStudents');
            exit();
        }
        
        $students = $this->student->getAllStudents();
        $departments = $this->department->getAllDepartments();
        include_once 'views/admin/students.php';
    }

    public function manageSemesters() {
        if($_POST && isset($_POST['action'])) {
            switch($_POST['action']) {
                case 'create':
                    $this->semester->createSemester($_POST);
                    break;
                case 'update':
                    $this->semester->updateSemester($_POST['semester_id'], $_POST);
                    break;
                case 'delete':
                    $this->semester->deleteSemester($_POST['semester_id']);
                    break;
            }
            header('Location: index.php?controller=admin&action=manageSemesters');
            exit();
        }
        
        $semesters = $this->semester->getAllSemesters();
        include_once 'views/admin/semesters.php';
    }
}
?>