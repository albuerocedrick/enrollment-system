<!-- views/student/dashboard.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Student Dashboard</h1>
            <div class="flex items-center space-x-4">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</span>
                <a href="index.php?controller=auth&action=logout" class="bg-red-600 px-3 py-1 rounded hover:bg-red-700">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        <!-- Student Info Card -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Student Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($student_info['first_name'] . ' ' . $student_info['last_name']); ?></p>
                    <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student_info['student_id']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($student_info['email']); ?></p>
                </div>
                <div>
                    <p><strong>Major:</strong> <?php echo htmlspecialchars($student_info['department_name'] ?? 'Not Assigned'); ?></p>
                    <p><strong>Status:</strong> 
                        <span class="px-2 py-1 text-xs rounded-full <?php echo $student_info['current_status'] === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                            <?php echo htmlspecialchars($student_info['current_status']); ?>
                        </span>
                    </p>
                    <p><strong>Enrollment Date:</strong> <?php echo date('M j, Y', strtotime($student_info['enrollment_date'])); ?></p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="index.php?controller=student&action=enroll" class="bg-green-500 text-white p-4 rounded-lg text-center hover:bg-green-600 transition">
                    <div class="text-2xl mb-2">➕</div>
                    <div>Enroll in Courses</div>
                </a>
                <a href="index.php?controller=student&action=myEnrollments" class="bg-blue-500 text-white p-4 rounded-lg text-center hover:bg-blue-600 transition">
                    <div class="text-2xl mb-2">📚</div>
                    <div>My Enrollments</div>
                </a>
                <a href="index.php?controller=student&action=profile" class="bg-purple-500 text-white p-4 rounded-lg text-center hover:bg-purple-600 transition">
                    <div class="text-2xl mb-2">👤</div>
                    <div>My Profile</div>
                </a>
            </div>
        </div>

        <!-- Current Enrollments -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">
                Current Semester Enrollments
                <?php if($current_semester): ?>
                    - <?php echo htmlspecialchars($current_semester['semester_name']); ?>
                <?php endif; ?>
            </h2>
            <?php if(!empty($enrollments)): ?>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Course Code</th>
                                <th class="text-left py-2">Course Name</th>
                                <th class="text-left py-2">Credits</th>
                                <th class="text-left py-2">Schedule</th>
                                <th class="text-left py-2">Location</th>
                                <th class="text-left py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($enrollments as $enrollment): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-2 font-medium"><?php echo htmlspecialchars($enrollment['course_code']); ?></td>
                                <td class="py-2"><?php echo htmlspecialchars($enrollment['course_name']); ?></td>
                                <td class="py-2"><?php echo htmlspecialchars($enrollment['credits']); ?></td>
                                <td class="py-2"><?php echo htmlspecialchars($enrollment['days'] . ' ' . date('g:i A', strtotime($enrollment['start_time'])) . '-' . date('g:i A', strtotime($enrollment['end_time']))); ?></td>
                                <td class="py-2"><?php echo htmlspecialchars($enrollment['location'] ?? 'TBA'); ?></td>
                                <td class="py-2">
                                    <span class="px-2 py-1 text-xs rounded-full <?php echo $enrollment['status'] === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?php echo htmlspecialchars($enrollment['status']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8 text-gray-500">
                    <p>No current enrollments found.</p>
                    <a href="index.php?controller=student&action=enroll" class="text-blue-600 hover:underline mt-2 inline-block">Enroll in courses now</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>