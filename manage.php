<?php
//fonction d'ajout déléments dans le fichier
function appendArrayRoCSV(array $array, string $csvFile)
{
    //ouvrir le fichier
    $handle = fopen($csvFile, 'a'); //le mode a permet de ler ET d'écrire
    fputcsv($handle, $array);
    fclose($handle);
}

//Vérification des champs cachés de sécurité
if (isset($_POST['action']) && isset($_POST['ressource'])) {
    if ($_POST['action'] === 'store' && $_POST['ressource'] === 'match') {
        //récupération depuis la requête
        $matchDate = $_POST['match-date'];
        $homeTeam = $_POST['home-team-unlisted'] === '' ? $_POST['home-team'] : $_POST['home-team-unlisted'];
        $awayTeam = $_POST['away-team-unlisted'] === '' ? $_POST['away-team'] : $_POST['away-team-unlisted'];
        $homeTeamGoals = $_POST['home-team-goals'];
        $awayTeamGoals = $_POST['away-team-goals'];

        $match = [$matchDate, $homeTeam, $homeTeamGoals, $awayTeamGoals, $awayTeam];
        appendArrayRoCSV($match, 'matches.csv');
    }
}

//redirection vers index
header('Location: index.php');
exit();
