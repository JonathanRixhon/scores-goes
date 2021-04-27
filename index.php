<?php
//importations
require("./configs/config.php");
require("./utils/dbacess.php");
require("./models/team.php");
require("./models/match.php");
//importatoin grâce au  namespaces
use function Team\all as allTeams;
use function Match\AllWithTeams as matchesAllWithTeams;
use function Match\AllWithTeamsGrouped as matchesAllWithTeamsGrouped;
//Variables
$pdo = getConnection();
$teams = allTeams($pdo); //récupere les data via la fonction all
$matches = matchesAllWithTeamsGrouped(matchesAllWithTeams($pdo));
//var_dump($matches);
//die();
$standings = [];
$handle = fopen(FILE_PATH, 'r'); //r = read, fopen stock le contenu du fichier dans $handle
$headers = fgetcsv($handle, 1000, ','); // on récupère les headers car cette fonction ne prend qu'une ligne
//var_dump($matches);
//die;
//recuperation
function getEmptySatsArray(): array
{
    return [
        'games' => 0,
        'points' => 0,
        'wins' => 0,
        'losses' => 0,
        'draws' => 0,
        'GF' => 0,
        'GD' => 0,
        'GA' => 0,
    ];
}
//récupération et création du tableau $matches et completion des variables
while ($line = fgetcsv($handle, 1000, ',')) {
    //fgetcsv ne prend qu'une ligne à la fois
    //la boucle continue tant que fgetcsv() revoie quelque chose
    $match = array_combine($headers, $line);
    /* $matches[] = $match; */
    //
    $homeTeam = $match['home-team'];
    $awayTeam = $match['away-team'];
    if (!array_key_exists($homeTeam, $standings)) {
        $standings[$homeTeam] = getEmptySatsArray();
    }
    if (!array_key_exists($awayTeam, $standings)) {
        $standings[$awayTeam] = getEmptySatsArray();
    }
    //ajout des games jouées à chaque fois que le m^me nom apparaît
    $standings[$homeTeam]['games']++;
    $standings[$awayTeam]['games']++;

    if ($match['home-team-goals'] === $match['away-team-goals']) {
        //gestion des égalités
        $standings[$homeTeam]['points']++;
        $standings[$awayTeam]['points']++;
        $standings[$homeTeam]['draws']++;
        $standings[$awayTeam]['draws']++;
    } elseif ($match['home-team-goals'] > $match['away-team-goals']) {
        //victoire à domicile
        $standings[$homeTeam]['points'] += 3;
        $standings[$homeTeam]['wins']++;
        $standings[$awayTeam]['losses']++;
    } else {
        //victoire à l'éxtérieur
        $standings[$awayTeam]['points'] += 3;
        $standings[$awayTeam]['wins']++;
        $standings[$homeTeam]['losses']++;
    }
    //calcul des stats globales (nombre de goals encaissés ect.)
    $standings[$homeTeam]["GF"] += $match['home-team-goals'];
    $standings[$homeTeam]["GA"] += $match['away-team-goals'];
    $standings[$awayTeam]["GF"] += $match['away-team-goals'];
    $standings[$awayTeam]["GA"] += $match['home-team-goals'];
    $standings[$homeTeam]["GD"] += $standings[$homeTeam]["GF"] - $standings[$homeTeam]["GA"];
    $standings[$awayTeam]["GD"] += $standings[$awayTeam]["GF"] - $standings[$awayTeam]["GA"];
}
// tri des valeurs en fonction de celui qui a le plus de points
uasort($standings, function ($a, $b) {
    if ($a['points'] === $b['points']) {
        return 0;
    }
    return $a['points'] > $b['points'] ? -1 : 1;
});

/* --------------TEMPLATE-------------- */
// require renvoit une erreur fatale, inculde ne fait pas d'erreur.
//on ajoute _once pour alerter si il charge le fichier 2fois
include('./views/vue.php');
