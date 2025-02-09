
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <nav class="bg-gray-900 text-white shadow-lg fixed w-full z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo -->
                <a href="home" class="text-xl font-bold text-white hover:text-gray-300">Musify</a>

                <!-- Menu Desktop -->
                <ul class="hidden md:flex space-x-6">
                    <li><a href="home" class="hover:text-gray-300">Home</a></li>
                    <li><a href="uploadSong" class="hover:text-gray-300">Uploader une chanson</a></li>
                    <li><a href="uploadAlbum" class="hover:text-gray-300">Uploader un album</a></li>
                    <li><a href="profile" class="hover:text-gray-300">Mon profil</a></li>
                    <li><a href="logout" class="text-red-400 hover:text-red-500">Déconnexion</a></li>
                </ul>

                <!-- Bouton Menu Mobile -->
                <div class="md:hidden">
                    <button id="menu-btn" class="focus:outline-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Menu Sidebar Mobile (Caché au début, positionné à droite) -->
    <div id="mobile-menu"
        class="fixed right-0 top-0 w-64 h-screen bg-gray-800 text-white transform translate-x-[100%] transition-transform duration-300 ease-in-out shadow-lg z-20">
        <!-- Bouton Fermer -->
        <div class="flex justify-end p-4">
            <button id="close-menu" class="text-white">
                ✖
            </button>
        </div>

        <ul class="flex flex-col space-y-4 p-6">
            <li><a href="home" class="block text-white hover:text-gray-300">Home</a></li>
            <li><a href="uploadSong" class="block text-white hover:text-gray-300">Uploader une chanson</a></li>
            <li><a href="uploadAlbum" class="block text-white hover:text-gray-300">Uploader un album</a></li>
            <li><a href="profile" class="block text-white hover:text-gray-300">Mon profil</a></li>
            <li><a href="logout" class="block text-red-400 hover:text-red-500">Déconnexion</a></li>
        </ul>
    </div>

    <!-- Script JavaScript -->
    <script>
        const menuBtn = document.getElementById('menu-btn');
        const closeBtn = document.getElementById('close-menu');
        const mobileMenu = document.getElementById('mobile-menu');

        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('translate-x-[100%]');
            mobileMenu.classList.add('translate-x-0');
        });

        closeBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('translate-x-0');
            mobileMenu.classList.add('translate-x-[100%]');
        });
    </script>
</body>
