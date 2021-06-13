<?php

namespace Controllers;

use Models\team;


class Matches
{
    function store()
    {
        $matchModel = new \Models\Matches();
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
        $matchModel->save($match);
        header('Location: index.php');
        exit();
    }

    function create(): array
    {
        $teamModel = new Team();
        $teams = $teamModel->all();
        $view = './views/match/create.php';
        return compact('view', 'teams');
    }
}
/* require("./models/Matches.php");
require("./models/team.php");
 */
