<?php include 'navbar.php'; ?>

<div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-gray-900 to-gray-800 text-white p-6">
    <div class="bg-gray-900 shadow-2xl rounded-lg p-8 w-full max-w-lg border border-gray-700">
        <h2 class="text-3xl font-bold text-center text-white mb-6"> Uploader un Album</h2>

        <?php if (isset($message)) { ?>
            <p class="text-center text-red-400 font-semibold"> <?php echo $message; ?> </p>
        <?php } ?>

        <form action="uploadAlbum" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="artisteId" value="<?php echo $_SESSION['user']['iduser']; ?>" />
            
            <!-- Nom de l'album -->
            <div>
                <label for="albumTitle" class="block text-gray-300 font-medium">Nom de l'album :</label>
                <input type="text" id="albumTitle" name="albumTitle" required
                    class="w-full px-4 py-3 border border-gray-600 rounded-lg bg-gray-800 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all">
            </div>

            <!-- Ajouter plusieurs chansons -->
            <div id="songUploads">
                <div class="song-upload">
                    <h3 class="text-xl font-semibold text-white"> Chanson 1</h3>

                    <label for="songTitles[]" class="block text-gray-300 font-medium">Titre de la chanson :</label>
                    <input type="text" name="songTitles[]" required
                        class="w-full px-4 py-3 border border-gray-600 rounded-lg bg-gray-800 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all">

                    <label for="songCategories[]" class="block text-gray-300 font-medium">Catégorie :</label>
                    <select name="songCategories[]" required
                        class="w-full px-4 py-3 border border-gray-600 rounded-lg bg-gray-800 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all">
                        <?php foreach ($categories as $category) { ?>
                            <option value="<?php echo $category['idcategory']; ?>"><?php echo $category['name']; ?></option>
                        <?php } ?>
                    </select>

                    <label for="songFiles[]" class="block text-gray-300 font-medium">Fichier audio :</label>
                    <input type="file" name="songFiles[]" accept=".mp3,.wav" required
                        class="w-full px-4 py-3 border border-gray-600 rounded-lg cursor-pointer bg-gray-800 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all">

                    <label for="songImages[]" class="block text-gray-300 font-medium">Image de la chanson :</label>
                    <input type="file" name="songImages[]" accept=".jpg,.jpeg,.png" required
                        class="w-full px-4 py-3 border border-gray-600 rounded-lg cursor-pointer bg-gray-800 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all">
                </div>
            </div>

            <!-- Bouton pour ajouter une autre chanson -->
            <button type="button" id="addSong"
                class="w-full bg-green-600 text-white font-semibold py-3 rounded-lg hover:bg-green-700 transition-all duration-300 transform hover:scale-105">
                Ajouter une autre chanson
            </button>

            <button type="submit"
                class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition-all duration-300 transform hover:scale-105">
                Uploader l'album
            </button>
        </form>
    </div>
</div>

<script>
    document.getElementById('addSong').addEventListener('click', function () {
        let songUploads = document.getElementById('songUploads');
        let index = songUploads.children.length + 1;

        let songHtml = `
            <div class="song-upload">
                <h3 class="text-xl font-semibold text-white"> Chanson ${index}</h3>

                <label for="songTitles[]" class="block text-gray-300 font-medium">Titre de la chanson :</label>
                <input type="text" name="songTitles[]" required
                    class="w-full px-4 py-3 border border-gray-600 rounded-lg bg-gray-800 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all">

                <label for="songCategories[]" class="block text-gray-300 font-medium">Catégorie :</label>
                <select name="songCategories[]" required
                    class="w-full px-4 py-3 border border-gray-600 rounded-lg bg-gray-800 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all">
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?php echo $category['idcategory']; ?>"><?php echo $category['name']; ?></option>
                    <?php } ?>
                </select>

                <label for="songFiles[]" class="block text-gray-300 font-medium">Fichier audio :</label>
                <input type="file" name="songFiles[]" accept=".mp3,.wav" required
                    class="w-full px-4 py-3 border border-gray-600 rounded-lg cursor-pointer bg-gray-800 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all">

                <label for="songImages[]" class="block text-gray-300 font-medium">Image de la chanson :</label>
                <input type="file" name="songImages[]" accept=".jpg,.jpeg,.png" required
                    class="w-full px-4 py-3 border border-gray-600 rounded-lg cursor-pointer bg-gray-800 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all">
            </div>
        `;

        songUploads.insertAdjacentHTML('beforeend', songHtml);
    });
</script>
