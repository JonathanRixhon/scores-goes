<?php
//importations
require("./configs/config.php");
require("./utils/dbacess.php");
require("./utils/standings.php");
require("./models/team.php");
require("./models/match.php");
//importatoin grâce au  namespaces
use function Team\all as allTeams;
use function Match\AllWithTeams as matchesAllWithTeams;
use function Match\AllWithTeamsGrouped as matchesAllWithTeamsGrouped;

$pdo = getConnection();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            //ajouterdan la db
        }
    }
} else if ($_SERVER["REQUEST_METHOD"] === "GET") {
    //traiter les trucs en GET
    if (!isset($_GET['action']) && !isset($_GET['ressource'])) {
        // HOMEPAGE
        //récupere les data via la fonction all
        $teams = allTeams($pdo);
        $matches = matchesAllWithTeamsGrouped(matchesAllWithTeams($pdo));
        $standings = [];
        //récupération et création du tableau $matches et completion des variables
        foreach ($matches as $match) {
            $homeTeam = $match->home_team;
            $awayTeam = $match->away_team;

            if (!array_key_exists($homeTeam, $standings)) {
                $standings[$homeTeam] = getEmptySatsArray();
            }
            if (!array_key_exists($awayTeam, $standings)) {
                $standings[$awayTeam] = getEmptySatsArray();
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
        uasort($standings, function ($a, $b) {
            if ($a['points'] === $b['points']) {
                return 0;
            }
            return $a['points'] > $b['points'] ? -1 : 1;
        });
    } else {
        //type de requète pas autorisé
        header('Location: index.php');
        exit();
    }
}
/* --------------TEMPLATE-------------- */
// require renvoit une erreur fatale, inculde ne fait pas d'erreur.
//on ajoute _once pour alerter si il charge le fichier 2fois
include('./views/vue.php');
