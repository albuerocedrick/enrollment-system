<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Semesters</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Manage Semesters</h1>
            <div class="flex items-center space-x-4">
                <a href="index.php?controller=admin&action=dashboard" class="hover:underline">Dashboard</a>
                <a href="index.php?controller=auth&action=logout" class="bg-red-600 px-3 py-1 rounded hover:bg-red-700">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        <!-- Add New Semester -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Add New Semester</h2>
            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="action" value="create">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Semester ID</label>
                    <input type="text" name="semester_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Semester Name</label>
                    <input type="text" name="semester_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" name="end_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Registration Start</label>
                    <input type="date" name="registration_start" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Registration End</label>
                    <input type="date" name="registration_end" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Add Semester</button>
                </div>
            </form>
        </div>

        <!-- Semesters List -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Existing Semesters</h2>
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Semester ID</th>
                            <th class="text-left py-2">Name</th>
                            <th class="text-left py-2">Start Date</th>
                            <th class="text-left py-2">End Date</th>
                            <th class="text-left py-2">Registration Period</th>
                            <th class="text-left py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($semesters as $semester): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2 font-medium"><?php echo htmlspecialchars($semester['semester_id']); ?></td>
                            <td class="py-2"><?php echo htmlspecialchars($semester['semester_name']); ?></td>
                            <td class="py-2"><?php echo date('M j, Y', strtotime($semester['start_date'])); ?></td>
                            <td class="py-2"><?php echo date('M j, Y', strtotime($semester['end_date'])); ?></td>
                            <td class="py-2">
                                <?php echo $semester['registration_start'] ? date('M j, Y', strtotime($semester['registration_start'])) . ' - ' . date('M j, Y', strtotime($semester['registration_end'])) : 'N/A'; ?>
                            </td>
                            <td class="py-2">
                                <button onclick="editSemester(<?php echo htmlspecialchars(json_encode($semester)); ?>)" class="bg-yellow-500 text-white px-2 py-1 rounded text-sm hover:bg-yellow-600 mr-2">Edit</button>
                                <form method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="semester_id" value="<?php echo $semester['semester_id']; ?>">
                                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded text-sm hover:bg-red-600">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg max-w-md w-full mx-4">
            <h3 class="text-lg font-bold mb-4">Edit Semester</h3>
            <form method="POST" id="editForm">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="semester_id" id="edit_semester_id">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Semester Name</label>
                        <input type="text" name="semester_name" id="edit_semester_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" name="start_date" id="edit_start_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" name="end_date" id="edit_end_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Registration Start</label>
                        <input type="date" name="registration_start" id="edit_registration_start" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Registration End</label>
                        <input type="date" name="registration_end" id="edit_registration_end" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editSemester(semester) {
            document.getElementById('edit_semester_id').value = semester.semester_id;
            document.getElementById('edit_semester_name').value = semester.semester_name;
            document.getElementById('edit_start_date').value = semester.start_date;
            document.getElementById('edit_end_date').value = semester.end_date;
            document.getElementById('edit_registration_start').value = semester.registration_start || '';
            document.getElementById('edit_registration_end').value = semester.registration_end || '';
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }
    </script>
</body>
</html>