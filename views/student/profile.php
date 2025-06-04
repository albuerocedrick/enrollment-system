<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">My Profile</h1>
            <div class="flex items-center space-x-4">
                <a href="index.php?controller=student&action=dashboard" class="hover:underline">Dashboard</a>
                <a href="index.php?controller=auth&action=logout" class="bg-red-600 px-3 py-1 rounded hover:bg-red-700">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Student Profile</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student_info['student_id']); ?></p>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($student_info['first_name'] . ' ' . $student_info['last_name']); ?></p>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($student_info['username']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($student_info['email']); ?></p>
                    <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($student_info['phone_number'] ?? 'N/A'); ?></p>
                </div>
                <div>
                    <p><strong>Date of Birth:</strong> <?php echo $student_info['date_of_birth'] ? date('M j, Y', strtotime($student_info['date_of_birth'])) : 'N/A'; ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($student_info['address'] ?? 'N/A'); ?></p>
                    <p><strong>Major:</strong> <?php echo htmlspecialchars($student_info['department_name'] ?? 'Not Assigned'); ?></p>
                    <p><strong>Enrollment Date:</strong> <?php echo date('M j, Y', strtotime($student_info['enrollment_date'])); ?></p>
                    <p><strong>Status:</strong> 
                        <span class="px-2 py-1 text-xs rounded-full <?php echo $student_info['current_status'] === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                            <?php echo htmlspecialchars($student_info['current_status']); ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>