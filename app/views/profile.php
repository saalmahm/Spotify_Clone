<?php include 'navbar.php'; ?>

<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-lg">
        <!-- Profil Utilisateur -->
        <div class="text-center">
            <img src="<?php 
                $userImage = $_SESSION['user']['image'] ?? '';
                $defaultImage = 'public/images/default-avatar.png';
                
                // Si l'image utilisateur est vide ou n'existe pas, utiliser l'image par défaut
                if (empty($userImage) || !file_exists($userImage)) {
                    echo '/' . $defaultImage;
                } else {
                    // Nettoyer et afficher le chemin de l'image
                    echo '/' . ltrim($userImage, '/');
                }
            ?>" 
                 alt="Photo de profil" class="w-24 h-24 rounded-full mx-auto shadow-md object-cover"
            >
            <h1 class="text-2xl font-semibold text-gray-800 mt-4">
                <?php echo $_SESSION['user']['username'] ?? 'Utilisateur'; ?>
            </h1>
            <p class="text-gray-600"><?php echo $_SESSION['user']['email'] ?? 'Email non défini'; ?></p>
            <p class="text-gray-600">📞 <?php echo $_SESSION['user']['phone'] ?? 'Téléphone non défini'; ?></p>
        </div>

        <!-- Albums -->
        <div class="mt-6">
            <h2 class="text-xl font-semibold text-gray-800">🎵 Mes albums</h2>
            <ul class="mt-2 space-y-2">
                <?php if (isset($albums) && is_array($albums)) { ?>
                    <?php foreach ($albums as $album) { ?>
                        <li class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg shadow">
                            <?php echo htmlspecialchars($album['nom'] ?? $album['name'] ?? 'Album sans nom'); ?>
                        </li>
                    <?php } ?>
                <?php } else { ?>
                    <li class="text-gray-500">Aucun album disponible</li>
                <?php } ?>
            </ul>
        </div>

        <!-- Chansons -->
        <div class="mt-6">
            <h2 class="text-xl font-semibold text-gray-800">🎶 Mes chansons</h2>
            <ul class="mt-2 space-y-2">
                <?php if (isset($songs) && is_array($songs)) { ?>
                    <?php foreach ($songs as $song) { ?>
                        <li class="bg-green-100 text-green-700 px-4 py-2 rounded-lg shadow">
                            <?php echo htmlspecialchars($song['titre'] ?? $song['name'] ?? 'Chanson sans titre'); ?>
                        </li>
                    <?php } ?>
                <?php } else { ?>
                    <li class="text-gray-500">Aucune chanson disponible</li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
