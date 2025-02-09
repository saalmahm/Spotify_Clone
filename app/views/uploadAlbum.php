<!-- app/views/uploadAlbum.php -->
<?php include 'navbar.php'; ?>

<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-lg">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Uploader un album 🎶</h2>

        <form action="uploadAlbum" method="POST" enctype="multipart/form-data" class="space-y-4">
            <!-- Titre de l'album -->
            <div>
                <label for="albumTitle" class="block text-gray-700 font-medium">Titre de l'album:</label>
                <input type="text" id="albumTitle" name="albumTitle" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <!-- Fichiers audio (multiple) -->
            <div>
                <label for="albumSongs" class="block text-gray-700 font-medium">Chansons (Sélection multiple) :</label>
                <input type="file" id="albumSongs" name="albumSongs[]" accept=".mp3,.wav" multiple required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <!-- Bouton d'Upload -->
            <button type="submit"
                class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                📤 Uploader l'album
            </button>
        </form>
    </div>
</div>
