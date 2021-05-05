<?php
//importation composer

use Carbon\Carbon;

require('./vendor/autoload.php');
//importations
require("./controllers/match.php");
require("./controllers/team.php");
require("./utils/standings.php");
require("./configs/config.php");
require("./utils/dbacess.php");
require("./models/match.php");
require("./models/team.php");
//importatoin grâce au  namespaces
use function Models\Team\all as allTeams;
use function Controllers\Team\store as storeTeam;
use function Controllers\Match\store as storeMatch;
use function Models\Match\AllWithTeams as matchesAllWithTeams;
use function Models\Match\AllWithTeamsGrouped as matchesAllWithTeamsGrouped;

$pdo = getConnection();

/* 
**********************************

chaque requète est caractérisée par :
    - sa méthode GET/POST
    - une action Lister/créer/éditer/sauvegarder/supprimer
    - une ressource Team/match dans ce cas-ci

**********************************
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Vérification des champs cachés de sécurité
    if (isset($_POST['action']) && isset($_POST['ressource'])) {
        if ($_POST['action'] === 'store' && $_POST['ressource'] === 'match') {
            storeMatch($pdo);
        } else if ($_POST['action'] === 'store' && $_POST['ressource'] === 'team') {
            storeTeam($pdo);
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
