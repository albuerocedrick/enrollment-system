<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll in Courses</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Enroll in Courses</h1>
            <div class="flex items-center space-x-4">
                <a href="index.php?controller=student&action=dashboard" class="hover:underline">Dashboard</a>
                <a href="index.php?controller=auth&action=logout" class="bg-red-600 px-3 py-1 rounded hover:bg-red-700">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Course Enrollment</h2>

            <!-- Semester Selection -->
            <form method="GET" class="mb-6">
                <input type="hidden" name="controller" value="student">
                <input type="hidden" name="action" value="enroll">
                <div class="flex items-center space-x-4">
                    <label class="text-sm font-medium text-gray-700">Select Semester</label>
                    <select name="semester_id" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Select Semester --</option>
                        <?php foreach($semesters as $semester): ?>
                            <option value="<?php echo htmlspecialchars($semester['semester_id']); ?>" <?php echo $selected_semester == $semester['semester_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($semester['semester_name']); ?> (<?php echo date('M j, Y', strtotime($semester['start_date'])); ?> - <?php echo date('M j, Y', strtotime($semester['end_date'])); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <!-- Error/Success Messages -->
            <?php if(isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            <?php if(isset($success)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <!-- Courses Table -->
            <?php if($selected_semester && !empty($courses)): ?>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Course Code</th>
                                <th class="text-left py-2">Course Name</th>
                                <th class="text-left py-2">Credits</th>
                                <th class="text-left py-2">Schedule</th>
                                <th class="text-left py-2">Location</th>
                                <th class="text-left py-2">Prerequisites</th>
                                <th class="text-left py-2">Available Seats</th>
                                <th class="text-left py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($courses as $course): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-2 font-medium"><?php echo htmlspecialchars($course['course_code']); ?></td>
                                <td class="py-2"><?php echo htmlspecialchars($course['course_name']); ?></td>
                                <td class="py-2"><?php echo htmlspecialchars($course['credits']); ?></td>
                                <td class="py-2"><?php echo htmlspecialchars($course['schedule'] ?? 'TBA'); ?></td>
                                <td class="py-2"><?php echo htmlspecialchars($course['location'] ?? 'TBA'); ?></td>
                                <td class="py-2"><?php echo htmlspecialchars($course['prerequisites'] ?? 'None'); ?></td>
                                <td class="py-2"><?php echo htmlspecialchars($course['available_seats']); ?>/<?php echo htmlspecialchars($course['max_capacity']); ?></td>
                                <td class="py-2">
                                    <?php if($course['can_enroll']): ?>
                                        <button onclick="openEnrollModal(<?php echo htmlspecialchars(json_encode($course)); ?>)" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">Enroll</button>
                                    <?php else: ?>
                                        <span class="text-gray-500 text-sm"><?php echo htmlspecialchars($course['enroll_status']); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif($selected_semester): ?>
                <div class="text-center py-8 text-gray-500">
                    <p>No courses available for this semester.</p>
                </div>
            <?php else: ?>
                <div class="text-center py-8 text-gray-500">
                    <p>Please select a semester to view available courses.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Enrollment Modal -->
    <div id="enrollModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg max-w-md w-full mx-4">
            <h3 class="text-lg font-bold mb-4">Confirm Enrollment</h3>
            <p class="mb-4">Are you sure you want to enroll in <span id="modalCourseName" class="font-medium"></span> (<span id="modalCourseCode"></span>) for <span id="modalSemester"></span>?</p>
            <form method="POST" id="enrollForm">
                <input type="hidden" name="action" value="processEnrollment">
                <input type="hidden" name="course_id" id="modalCourseId">
                <input type="hidden" name="semester_id" id="modalSemesterId">
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeEnrollModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Enroll</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEnrollModal(course) {
            document.getElementById('modalCourseName').textContent = course.course_name;
            document.getElementById('modalCourseCode').textContent = course.course_code;
            document.getElementById('modalSemester').textContent = '<?php echo htmlspecialchars($semester_name ?? ''); ?>';
            document.getElementById('modalCourseId').value = course.course_id;
            document.getElementById('modalSemesterId').value = '<?php echo htmlspecialchars($selected_semester ?? ''); ?>';
            document.getElementById('enrollModal').classList.remove('hidden');
            document.getElementById('enrollModal').classList.add('flex');
        }

        function closeEnrollModal() {
            document.getElementById('enrollModal').classList.add('hidden');
            document.getElementById('enrollModal').classList.remove('flex');
        }
    </script>
</body>
</html>