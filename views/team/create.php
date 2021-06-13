<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <title>Création d'une équipe</title>
</head>

<body>
    <div><a href="index.php">Première League 2020</a></div>
    <h1>Création d'une équipe</h1>
    <form action="index.php" method="post" enctype="multipart/form-data">

        <input type="hidden" name="MAX_FILE_SIZE" value="10000000">
        <input type="hidden" name="action" value="store">
        <input type="hidden" name="ressource" value="team">

        <div>
            <label for="name">Nom</label>
            <input type="text" name='name' id="name" value=<?= $_SESSION['old']['name'] ?? '' ?>>

            <?php if (isset($_SESSION['errors']['name'])) : ?>
                <p><?= $_SESSION['errors']["name"] ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="slug">Entrer un identifiant (3 lettres)</label>
            <input type="text" name='slug' id="slug" value=<?= $_SESSION['old']['slug'] ?? '' ?>>
            <?php if (isset($_SESSION['errors']['slug'])) : ?>
                <p><?= $_SESSION['errors']["slug"] ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="logo">Fournissez un logo (400x400 et 1600x1600)</label>
            <input type="file" name='logo' id="logo" value=<?= $_SESSION['old']['logo'] ?? '' ?>>
            <?php if (isset($_SESSION['errors']['logo'])) : ?>
                <p><?= $_SESSION['errors']["logo"] ?></p>
            <?php endif; ?>
        </div>
        <input type="submit" value="Enregistrer cette équipe">
    </form>

</body>

</html>