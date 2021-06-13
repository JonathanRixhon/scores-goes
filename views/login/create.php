<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <title>Connection à l'administration</title>
</head>

<body>
    <div><a href="index.php">Première League 2020</a></div>
    <h1>Connection à l'administration</h1>
    <form action="index.php" method="post">

        <input type="hidden" name="action" value="check">
        <input type="hidden" name="ressource" value="login">

        <div>
            <label for="email">Entrez votre email</label>
            <input type="text" name='email' id="email" value=<?= $_SESSION['old']['email'] ?? '' ?>>

            <?php if (isset($_SESSION['errors']['email'])) : ?>
                <p><?= $_SESSION['errors']["email"] ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="password">Entrer votre mot de passe (8 lettres, 1 majuscule et un chiffre)</label>
            <input type="text" name='password' id="password" value=<?= $_SESSION['old']['password'] ?? '' ?>>
            <?php if (isset($_SESSION['errors']['password'])) : ?>
                <p><?= $_SESSION['errors']["password"] ?></p>
            <?php endif; ?>
        </div>
        <input type="submit" value="S'enregistrer">
    </form>

</body>

</html>