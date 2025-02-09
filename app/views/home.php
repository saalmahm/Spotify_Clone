<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Musify - Accueil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Hero Section -->
    <header class="text-center py-20 bg-gradient-to-r from-purple-600 to-indigo-800 shadow-lg">
        <h1 class="text-5xl font-extrabold">Bienvenue sur Musify</h1>
        <p class="mt-4 text-xl text-gray-200">Écoutez vos artistes préférés en illimité</p>
    </header>

    <!-- Contenu Principal -->
    <main class="container mx-auto px-6 py-12">
        <!-- Albums Populaires -->
        <section>
            <h2 class="text-3xl font-bold mb-6">📀 Albums Populaires</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <?php if (isset($popularAlbums) && is_array($popularAlbums)) { ?>
                    <?php foreach ($popularAlbums as $album) { ?>
                        <div class="bg-gray-800 p-4 rounded-lg shadow-lg transform hover:scale-105 transition">
                            <img src="<?php echo $album['cover']; ?>" alt="<?php echo $album['nom']; ?>" class="rounded-lg w-full h-40 object-cover">
                            <h3 class="text-lg font-semibold mt-3"> <?php echo $album['nom']; ?> </h3>
                            <p class="text-gray-400 text-sm"> <?php echo $album['artisteId']; ?> </p>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p class="text-gray-400">Aucun album populaire disponible</p>
                <?php } ?>
            </div>
        </section>

        <!-- Chansons Tendances -->
        <section class="mt-12">
    <h2 class="text-3xl font-bold mb-6">🔥 Chansons Tendances</h2>
    <ul class="space-y-6">
        <?php if (isset($trendingSongs) && is_array($trendingSongs)) { ?>
            <?php foreach ($trendingSongs as $song) { ?>
                <li class="flex items-center bg-gray-800 p-4 rounded-lg shadow-lg hover:bg-gray-700 transition transform hover:scale-105">
                    <img src="<?php echo $song['cover']; ?>" alt="<?php echo $song['title']; ?>" class="w-16 h-16 rounded-lg object-cover">
                    <div class="ml-4 flex-1">
                        <h3 class="text-lg font-semibold"> <?php echo $song['title']; ?> </h3>
                        <p class="text-gray-400 text-sm">L'artiste : <?php echo $song['artist']; ?> </p>
                    </div>
                    <audio controls class="ml-4">
                        <source src="<?php echo $song['songfile']; ?>" type="audio/mpeg">
                    </audio>
                </li>
            <?php } ?>
        <?php } else { ?>
            <p class="text-gray-400">Aucune chanson tendance disponible</p>
        <?php } ?>
    </ul>
</section>

    </main>
</body>
</html>
