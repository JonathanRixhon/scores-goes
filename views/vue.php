<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Premier League 2020</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Premier League 2020</h1>
    <?php if (count($matches)) : ?>
        <section>
            <h2>Standings</h2>
            <table>
                <thead>
                    <tr>
                        <td></td>
                        <th scope="col">Team</th>
                        <th scope="col">Games</th>
                        <th scope="col">Points</th>
                        <th scope="col">Wins</th>
                        <th scope="col">Losses</th>
                        <th scope="col">Draws</th>
                        <th scope="col">GF</th>
                        <th scope="col">GA</th>
                        <th scope="col">GD</th>
                    </tr>
                </thead>
                <tbody>

                    <!-- Affichage des statistiques des équipes -->
                    <?php $i = 1 ?>
                    <?php foreach ($standings as $team => $teamStats) : ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <th scope="row"><?= $team ?></th>
                            <td><?= $teamStats['games'] ?></td>
                            <td><?= $teamStats['points'] ?></td>
                            <td><?= $teamStats['wins'] ?></td>
                            <td><?= $teamStats['losses'] ?></td>
                            <td><?= $teamStats['draws'] ?></td>
                            <td><?= $teamStats['GF'] ?></td>
                            <td><?= $teamStats['GA'] ?></td>
                            <td><?= $teamStats['GD'] ?></td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </section>
    <?php endif; ?>

    <section>
        <h2>Matche joués le <?= TODAY ?></h2>
        <?php if (count($matches)) : ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>À domicile</th>
                        <th>Goals de l'équipe visitée</th>
                        <th>Goals de l'équipe visiteuse</th>
                        <th>Équipe visiteuse</th>
                    </tr>
                </thead>
                <tbody>

                    <!-- Affichage des matches à partir de $matches -->

                    <?php foreach ($matches as $match) : ?>
                        <tr>
                            <td><?= ($match->match_date)->format('M l jS, Y') ?></td>
                            <td><?= $match->home_team ?></td>
                            <td><?= $match->home_team_goals ?></td>
                            <td><?= $match->away_team_goals ?></td>
                            <td><?= $match->away_team ?></td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>

        <?php else : ?>
            <p> Aucun match n'a été joué à ce jour.</p>
        <?php endif; ?>
    </section>
    <section>
        <!-- ************ FORMULAIRE ************ -->
        <h2>Encodage d’un nouveau match</h2>
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
            <label for="home-team-unlisted">Équipe non listée&nbsp;?</label>
            <input type="text" name="home-team-unlisted" id="home-team-unlisted">
            <br>
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
            <label for="away-team-unlisted">Équipe non listée&nbsp;?</label>
            <input type="text" name="away-team-unlisted" id="away-team-unlisted">
            <br>
            <label for="away-team-goals">Goals de l’équipe visiteuse</label>
            <input type="text" id="away-team-goals" name="away-team-goals">
            <br>
            <input type="hidden" name="action" value="store">
            <input type="hidden" name="ressource" value="match">
            <input type="submit" value="Ajouter ce match">
        </form>
    </section>
</body>

</html>