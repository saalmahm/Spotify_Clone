<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Musify - Accueil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-800 text-white">
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>
    
    <!-- Hero Section -->
    <header class="text-center py-16 bg-gradient-to-r from-green-500 to-blue-600 pt-20">
        <h1 class="text-4xl font-bold">Bienvenue sur Musify</h1>
        <p class="mt-4 text-lg">Découvrez et écoutez votre musique préférée</p>
    </header>
    
    <!-- Contenu Principal -->
    <main class="container mx-auto px-4 py-8">
        <!-- Albums Populaires -->
        <section>
            <h2 class="text-2xl font-semibold mb-4">📀 Albums Populaires</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <?php if (isset($popularAlbums) && is_array($popularAlbums)) { ?>
                    <?php foreach ($popularAlbums as $album) { ?>
                        <div class="bg-gray-800 p-4 rounded-lg shadow-md">
                            <img src="<?php echo $album['cover']; ?>" alt="<?php echo $album['title']; ?>" class="rounded-lg">
                            <h3 class="mt-2 text-lg font-semibold"><?php echo $album['title']; ?></h3>
                            <p class="text-gray-400"><?php echo $album['artist']; ?></p>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p>Aucun album populaire disponible</p>
                <?php } ?>
            </div>
        </section>

        <!-- Chansons Tendances -->
        <section class="mt-8">
            <h2 class="text-2xl font-semibold mb-4">🔥 Chansons Tendances</h2>
            <ul class="space-y-4">
                <?php if (isset($trendingSongs) && is_array($trendingSongs)) { ?>
                    <?php foreach ($trendingSongs as $song) { ?>
                        <li class="flex items-center bg-gray-800 p-4 rounded-lg shadow-md">
                            <img src="<?php echo $song['cover']; ?>" alt="<?php echo $song['title']; ?>" class="w-16 h-16 rounded-lg">
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold"><?php echo $song['title']; ?></h3>
                                <p class="text-gray-400"><?php echo $song['artist']; ?></p>
                            </div>
                            <button class="ml-auto bg-green-500 px-4 py-2 rounded-lg text-white hover:bg-green-600">▶ Écouter</button>
                        </li>
                    <?php } ?>
                <?php } else { ?>
                    <p>Aucune chanson tendance disponible</p>
                <?php } ?>
            </ul>
        </section>
    </main>
</body>
</html>
