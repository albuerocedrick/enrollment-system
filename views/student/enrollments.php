<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Enrollments</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">My Enrollments</h1>
            <div class="flex items-center space-x-4">
                <a href="index.php?controller=student&action=dashboard" class="hover:underline">Dashboard</a>
                <a href="index.php?controller=auth&action=logout" class="bg-red-600 px-3 py-1 rounded hover:bg-red-700">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">My Enrollments</h2>
            <?php if(!empty($enrollments)): ?>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Semester</th>
                                <th class="text-left py-2">Course Code</th>
                                <th class="text-left py-2">Course Name</th>
                                <th class="text-left py-2">Credits</th>
                                <th class="text-left py-2">Schedule</th>
                                <th class="text-left py-2">Location</th>
                                <th class="text-left py-2">Status</th>
                                <th class="text-left py-2">Grade</th>
                                <th class="text-left py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($enrollments as $enrollment): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-2"><?php echo htmlspecialchars($enrollment['semester_name']); ?></td>
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
                                <td class="py-2"><?php echo htmlspecialchars($enrollment['grade'] ?? 'N/A'); ?></td>
                                <td class="py-2">
                                    <?php if($enrollment['status'] === 'Active'): ?>
                                        <form method="POST" class="inline" onsubmit="return confirm('Are you sure you want to drop this course?')">
                                            <input type="hidden" name="action" value="drop">
                                            <input type="hidden" name="enrollment_id" value="<?php echo $enrollment['enrollment_id']; ?>">
                                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Drop</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8 text-gray-500">
                    <p>No enrollments found.</p>
                    <a href="index.php?controller=student&action=enroll" class="text-blue-600 hover:underline mt-2 inline-block">Enroll in courses now</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>