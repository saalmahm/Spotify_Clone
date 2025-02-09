<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
    <form method="POST" class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-xl font-semibold mb-4">Login</h2>

        <label class="block mb-2 text-sm font-medium text-gray-900">Email:</label>
        <input type="email" name="email" required class="block w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">

        <label class="block mt-2 mb-2 text-sm font-medium text-gray-900">Password:</label>
        <input type="password" name="password" required class="block w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">

        <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded mt-4 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300">Login</button>

        <?php if (isset($_GET['error']) && $_GET['error'] == 'invalid'): ?>
            <p class="text-red-500 text-sm mt-2">Invalid email or password. Please try again.</p>
        <?php endif; ?>
    </form>
</body>
</html>
