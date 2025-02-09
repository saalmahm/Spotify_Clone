<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Inscription</h2>
    <form action="UserController.php" method="POST">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit" name="register">S'inscrire</button>
    </form>

    <h2>Connexion</h2>
    <form action="UserController.php" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit" name="login">Se connecter</button>
    </form>

    <?php if (isset($_SESSION['user'])): ?>
        <h2>Profil</h2>
        <p>Nom d'utilisateur: <?= $_SESSION['user']['username']; ?></p>
        <p>Email: <?= $_SESSION['user']['email']; ?></p>
        <p>Rôle: <?= $_SESSION['user']['role']; ?></p>
        <a href="UserController.php?logout=true">Déconnexion</a>

        <h2>Modifier le Profil</h2>
        <form action="UserController.php" method="POST">
            <input type="hidden" name="idUser" value="<?= $_SESSION['user']['idUser']; ?>">
            <input type="text" name="username" placeholder="Nom d'utilisateur" value="<?= $_SESSION['user']['username']; ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?= $_SESSION['user']['email']; ?>" required>
            <input type="text" name="image" placeholder="URL de l'image de profil">
            <input type="text" name="phone" placeholder="Téléphone">
            <button type="submit" name="update">Mettre à jour</button>
        </form>
    <?php endif; ?>

</body>
</html>
