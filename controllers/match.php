<?php

namespace Controllers\Match;

use function Models\Match\save as saveMatch;

function store(\PDO $pdo)
{
    //récupération depuis la requête
    $matchDate = $_POST['match-date'];
    $homeTeam = $_POST['home-team'];
    $awayTeam = $_POST['away-team'];
    $homeTeamGoals = $_POST['home-team-goals'];
    $awayTeamGoals = $_POST['away-team-goals'];

    $match = [
        'date' => $matchDate,
        'home-team' => $homeTeam,
        'away-team' => $awayTeam,
        'home-team-goals' => $homeTeamGoals,
        'away-team-goals' => $awayTeamGoals,
    ];
    //ajouter dans la db
    saveMatch($pdo, $match);
    header('Location: index.php');
    exit();
}
