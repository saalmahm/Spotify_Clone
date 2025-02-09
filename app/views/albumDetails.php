<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'Album - Musify</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Album Details Section -->
    <div class="container mx-auto px-6 py-12">
        <?php if (isset($album) && is_array($album)): ?>
            <div class="flex flex-col md:flex-row bg-gray-800 rounded-lg overflow-hidden shadow-lg">
                <!-- Album Cover -->
                <div class="md:w-1/3 p-6">
                    <img src="<?php echo !empty($album['cover']) ? $album['cover'] : 'public/images/default-album.jpg'; ?>" 
                         alt="<?php echo htmlspecialchars($album['nom']); ?>" 
                         class="w-full h-auto rounded-lg object-cover">
                </div>

                <!-- Album Information -->
                <div class="md:w-2/3 p-6">
                    <h1 class="text-4xl font-bold mb-4"><?php echo htmlspecialchars($album['nom']); ?></h1>
                    <p class="text-xl text-gray-400 mb-4">Artiste: <?php echo htmlspecialchars($album['artistename']); ?></p>
                    <p class="text-lg mb-4">Nombre de chansons: <?php echo $album['nombrechansons']; ?></p>
                    
                    <!-- Chansons de l'Album -->
                    <h2 class="text-2xl font-semibold mt-6 mb-4">Chansons</h2>
                    <div class="space-y-4">
                        <?php if (isset($chansons) && is_array($chansons)): ?>
                            <?php foreach ($chansons as $chanson): ?>
                                <div class="flex items-center bg-gray-700 p-4 rounded-lg">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium"><?php echo htmlspecialchars($chanson['titre']); ?></h3>
                                        <p class="text-gray-400 text-sm"><?php 
                                            echo !empty($chanson['duree']) ? htmlspecialchars($chanson['duree']) : '00:00'; 
                                        ?></p>
                                    </div>
                                    <audio controls class="ml-4">
                                        <source src="<?php 
                                            echo htmlspecialchars($chanson['songFile']); 
                                            echo "\n<!-- Debug: songFile = " . print_r($chanson, true) . " -->";
                                        ?>" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-gray-400">Aucune chanson dans cet album</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-400">Album non trouvé</p>
        <?php endif; ?>
    </div>
</body>
</html>
