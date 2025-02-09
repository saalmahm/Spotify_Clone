<?php include 'navbar.php'; ?>

<div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-gray-900 to-gray-800 text-white p-6">
    <div class="bg-gray-900 shadow-2xl rounded-lg p-8 w-full max-w-lg border border-gray-700">
        <h2 class="text-3xl font-bold text-center text-white mb-6">🎶 Uploader une chanson</h2>

        <?php if (isset($message)) { ?>
            <p class="text-center text-red-400 font-semibold"> <?php echo $message; ?> </p>
        <?php } ?>

        <form action="uploadSong" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="artisteId" value="<?php echo $_SESSION['user']['iduser']; ?>" />
            
            <div>
                <label for="songTitle" class="block text-gray-300 font-medium">Titre de la chanson :</label>
                <input type="text" id="songTitle" name="songTitle" required
                    class="w-full px-4 py-3 border border-gray-600 rounded-lg bg-gray-800 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all">
            </div>

            <div>
                <label for="songCategory" class="block text-gray-300 font-medium">Catégorie :</label>
                <select id="songCategory" name="songCategory" required
                    class="w-full px-4 py-3 border border-gray-600 rounded-lg bg-gray-800 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all">
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?php echo $category['idcategory']; ?>"> <?php echo $category['name']; ?> </option>
                    <?php } ?>
                </select>
            </div>

            <div>
                <label for="songFile" class="block text-gray-300 font-medium">Fichier audio :</label>
                <input type="file" id="songFile" name="songFile" accept=".mp3,.wav" required
                    class="w-full px-4 py-3 border border-gray-600 rounded-lg cursor-pointer bg-gray-800 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all">
            </div>
            
            <div>
                <label for="songImage" class="block text-gray-300 font-medium">Image de la chanson :</label>
                <input type="file" id="songImage" name="songImage" accept=".jpg,.jpeg,.png" required
                    class="w-full px-4 py-3 border border-gray-600 rounded-lg cursor-pointer bg-gray-800 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all">
            </div>

            <input type="hidden" name="type" value="audio" />

            <button type="submit"
                class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition-all duration-300 transform hover:scale-105">
                📤 Uploader la chanson
            </button>
        </form>
    </div>
</div>
