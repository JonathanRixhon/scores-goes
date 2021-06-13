<?php

namespace Controllers;


use Models\Matches;
use Models\Team;

require("./utils/standings.php");

class Dashboard
{
    function index()

    {
        $teamModel = new Team();
        $matchModel = new Matches();
        //récupere les data via la fonction all
        $standings = [];
        $matches = $matchModel->allWithTeamsGrouped($matchModel->allWithTeams());
        $teams = $teamModel->all();
        $view = 'views/dashboard.php';
        //récupération et création du tableau $matches et completion des variables
        foreach ($matches as $match) {
            $homeTeam = $match->home_team;
            $awayTeam = $match->away_team;

            if (!array_key_exists($homeTeam, $standings)) {
                $standings[$homeTeam] = getEmptySatsArray();
                $standings[$homeTeam]['logo'] = $match->home_team_logo;
            }
            if (!array_key_exists($awayTeam, $standings)) {
                $standings[$awayTeam] = getEmptySatsArray();
                $standings[$awayTeam]['logo'] = $match->away_team_logo;
            }
            //ajout des games jouées à chaque fois que le m^me nom apparaît
            $standings[$homeTeam]['games']++;
            $standings[$awayTeam]['games']++;
            //
            if ($match->home_team_goals === $match->away_team_goals) {
                //gestion des égalités
                $standings[$homeTeam]['points']++;
                $standings[$awayTeam]['points']++;
                $standings[$homeTeam]['draws']++;
                $standings[$awayTeam]['draws']++;
            } elseif ($match->home_team_goals > $match->away_team_goals) {
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
            $standings[$homeTeam]["GF"] += $match->home_team_goals;
            $standings[$homeTeam]["GA"] += $match->away_team_goals;
            $standings[$awayTeam]["GF"] += $match->away_team_goals;
            $standings[$awayTeam]["GA"] += $match->home_team_goals;
            $standings[$homeTeam]["GD"] += $standings[$homeTeam]["GF"] - $standings[$homeTeam]["GA"];
            $standings[$awayTeam]["GD"] += $standings[$awayTeam]["GF"] - $standings[$awayTeam]["GA"];
        }
        // tri des valeurs en fonction de celui qui a le plus de points
        uasort($standings, static function ($a, $b) {
            if ($a['points'] === $b['points']) {
                return 0;
            }
            return $a['points'] > $b['points'] ? -1 : 1;
        });
        return compact('standings', 'matches', 'teams', 'view');
    }
}
