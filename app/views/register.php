<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
    <form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-xl font-semibold mb-4">User Information</h2>
        
        <label class="block mb-2 text-sm font-medium text-gray-900">Username:</label>
        <input type="text" name="username" required class="block w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
        
        <label class="block mt-2 mb-2 text-sm font-medium text-gray-900">Email:</label>
        <input type="email" name="email" required class="block w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
        
        <label class="block mt-2 mb-2 text-sm font-medium text-gray-900">Password:</label>
        <input type="password" name="password" required class="block w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
        
        <label class="block mt-2 mb-2 text-sm font-medium text-gray-900">Role:</label>
        <select name="role" required class="block w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            <option value="user">User</option>
            <option value="artiste">Artiste</option>
        </select>
        
        <label class="block mt-2 mb-2 text-sm font-medium text-gray-900">Phone:</label>
        <input type="text" name="phone" required class="block w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
        
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload file</label>
        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" id="file_input" type="file" name="image" accept="image/*">
        
        <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded mt-4 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300">Submit</button>
    </form>
</body>
</html>
