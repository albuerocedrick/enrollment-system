<!-- views/admin/dashboard.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-blue-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Admin Dashboard</h1>
            <div class="flex items-center space-x-4">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</span>
                <a href="index.php?controller=auth&action=logout" class="bg-red-600 px-3 py-1 rounded hover:bg-red-700">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-gray-500 text-sm uppercase">Total Students</h3>
                <p class="text-3xl font-bold text-blue-600"><?php echo count($students); ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-gray-500 text-sm uppercase">Departments</h3>
                <p class="text-3xl font-bold text-green-600"><?php echo count($departments); ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-gray-500 text-sm uppercase">Courses</h3>
                <p class="text-3xl font-bold text-purple-600"><?php echo count($courses); ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-gray-500 text-sm uppercase">Enrollments</h3>
                <p class="text-3xl font-bold text-orange-600"><?php echo count($enrollments); ?></p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <a href="index.php?controller=admin&action=manageDepartments" class="bg-blue-500 text-white p-4 rounded-lg text-center hover:bg-blue-600 transition">
                    <div class="text-2xl mb-2">🏢</div>
                    <div>Manage Departments</div>
                </a>
                <a href="index.php?controller=admin&action=manageCourses" class="bg-green-500 text-white p-4 rounded-lg text-center hover:bg-green-600 transition">
                    <div class="text-2xl mb-2">📚</div>
                    <div>Manage Courses</div>
                </a>
                <a href="index.php?controller=admin&action=manageStudents" class="bg-purple-500 text-white p-4 rounded-lg text-center hover:bg-purple-600 transition">
                    <div class="text-2xl mb-2">👥</div>
                    <div>Manage Students</div>
                </a>
                <a href="index.php?controller=admin&action=manageSemesters" class="bg-orange-500 text-white p-4 rounded-lg text-center hover:bg-orange-600 transition">
                    <div class="text-2xl mb-2">📅</div>
                    <div>Manage Semesters</div>
                </a>
                <a href="index.php?controller=admin&action=manageClassSchedules" class="bg-orange-500 text-white p-4 rounded-lg text-center hover:bg-orange-600 transition">
                    <div class="text-2xl mb-2">🕒</div>
                    <div>Manage Class Schedules</div>
                </a>
            </div>
        </div>

        <!-- Recent Enrollments -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Recent Enrollments</h2>
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Student</th>
                            <th class="text-left py-2">Course</th>
                            <th class="text-left py-2">Semester</th>
                            <th class="text-left py-2">Status</th>
                            <th class="text-left py-2">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach(array_slice($enrollments, 0, 10) as $enrollment): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2"><?php echo htmlspecialchars($enrollment['first_name'] . ' ' . $enrollment['last_name']); ?></td>
                            <td class="py-2"><?php echo htmlspecialchars($enrollment['course_code'] . ' - ' . $enrollment['course_name']); ?></td>
                            <td class="py-2"><?php echo htmlspecialchars($enrollment['semester_name']); ?></td>
                            <td class="py-2">
                                <span class="px-2 py-1 text-xs rounded-full <?php echo $enrollment['status'] === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <?php echo htmlspecialchars($enrollment['status']); ?>
                                </span>
                            </td>
                            <td class="py-2"><?php echo date('M j, Y', strtotime($enrollment['enrollment_date'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>