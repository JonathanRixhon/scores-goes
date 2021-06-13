<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un match</title>
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <div><a href="index.php">Première League 2020</a></div>

    <h1>Ajouter un match</h1>
    <form action="index.php" method="post">
        <label for="match-date">Date du match</label>
        <input type="text" id="match-date" name="match-date" placeholder="2020-04-10">
        <br>
        <label for="home-team">Équipe à domicile</label>
        <select name="home-team" id="home-team">

            <!-- Génération de la liste -->
            <?php foreach ($teams as $team) : ?>
                <option value="<?= $team->id ?>">
                    <?= $team->name ?> [<?= $team->slug ?>]
                </option>
            <?php endforeach; ?>

        </select>
        <label for="home-team-goals">Goals de l’équipe à domicile</label>
        <input type="text" id="home-team-goals" name="home-team-goals">
        <br>
        <label for="away-team">Équipe visiteuse</label>
        <select name="away-team" id="away-team">

            <!-- Génération de la liste -->
            <?php foreach ($teams as $team) : ?>
                <option value="<?= $team->id ?>">
                    <?= $team->name ?> [<?= $team->slug ?>]
                </option>
            <?php endforeach; ?>

        </select>
        <label for="away-team-goals">Goals de l’équipe visiteuse</label>
        <input type="text" id="away-team-goals" name="away-team-goals">
        <br>
        <input type="hidden" name="action" value="store">
        <input type="hidden" name="ressource" value="match">
        <input type="submit" value="Ajouter ce match">
    </form>
</body>

</html>