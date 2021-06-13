<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <title>Inscription à l'administration</title>
</head>

<body>
    <div><a href="index.php">Première League 2020</a></div>
    <h1>Inscription à l'administration</h1>
    <form action="index.php" method="post">

        <input type="hidden" name="action" value="store">
        <input type="hidden" name="ressource" value="user">

        <div>
            <label for="name">Entrez votre Nom</label>
            <input type="text" name='name' id="name" value=<?= $_SESSION['old']['name'] ?? '' ?>>
            <?php if (isset($_SESSION['errors']['name'])) : ?>
                <p><?= $_SESSION['errors']["name"] ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="email">Entrez votre email</label>
            <input type="text" name='email' id="email" value=<?= $_SESSION['old']['email'] ?? '' ?>>

            <?php if (isset($_SESSION['errors']['email'])) : ?>
                <p><?= $_SESSION['errors']["email"] ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="password">Entrer un mot de passe (8 lettres, 1 majuscule et un chiffre)</label>
            <input type="password" name='password' id="password" value=<?= $_SESSION['old']['password'] ?? '' ?>>
            <?php if (isset($_SESSION['errors']['password'])) : ?>
                <p><?= $_SESSION['errors']["password"] ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="confirm_password">Confirmer le mot de passe</label>
            <input type="password" name='confirm_password' id="confirm_password" value=<?= $_SESSION['old']['confirm_password'] ?? '' ?>>
            <?php if (isset($_SESSION['errors']['confirm_password'])) : ?>
                <p><?= $_SESSION['errors']["confirm_password"] ?></p>
            <?php endif; ?>
        </div>
        <input type="submit" value="S'enregistrer">
    </form>

</body>

</html>