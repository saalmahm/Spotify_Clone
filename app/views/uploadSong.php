<?php include 'navbar.php'; ?>

<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-lg">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Uploader une chanson 🎶</h2>

        <?php if (isset($message)) { ?>
            <p class="text-center text-red-500"><?php echo $message; ?></p>
        <?php } ?>

        <form action="uploadSong" method="POST" enctype="multipart/form-data" class="space-y-4">
    <input type="hidden" name="artisteId" value="<?php echo $_SESSION['user']['idUser']; ?>" />
    <!-- Titre -->
    <div>
        <label for="songTitle" class="block text-gray-700 font-medium">Titre de la chanson:</label>
        <input type="text" id="songTitle" name="songTitle" required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
    </div>

    <!-- Catégorie -->
    <div>
        <label for="songCategory" class="block text-gray-700 font-medium">Catégorie:</label>
        <select id="songCategory" name="songCategory" required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <?php foreach ($categories as $category) { ?>
                <option value="<?php echo $category['idcategory']; ?>"><?php echo $category['name']; ?></option>
                <?php } ?>
        </select>
    </div>

    <!-- Fichier audio -->
    <div>
        <label for="songFile" class="block text-gray-700 font-medium">Fichier audio:</label>
        <input type="file" id="songFile" name="songFile" accept=".mp3,.wav" required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:outline-none">
    </div>
    
    <!-- Image de la chanson -->
    <div>
        <label for="songImage" class="block text-gray-700 font-medium">Image de la chanson:</label>
        <input type="file" id="songImage" name="songImage" accept=".jpg,.jpeg,.png" required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:outline-none">
    </div>

    <!-- Type de fichier -->
    <input type="hidden" name="type" value="audio" />

    <!-- Bouton d'Upload -->
    <button type="submit"
        class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition duration-300">
        📤 Uploader la chanson
    </button>
</form>

    </div>
</div>

