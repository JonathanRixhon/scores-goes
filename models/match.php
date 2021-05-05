<?php
//définition de l'espace de nom pour que les fonctions puissent avoir le même nom.
namespace Models\Match;

use DateTime;

function all(\PDO $connection): array
{
    $matchesRequest = 'SELECT * FROM matches ORDER BY date';
    $pdoSt = $connection->query($matchesRequest);

    return $pdoSt->fetchAll();
}

function find(\PDO $connection, string $id): \stdClass
{
    $matchRequest = 'SELECT * FROM matches WHERE id = :id';
    $pdoSt = $connection->prepare($matchRequest);
    $pdoSt->execute([':id' => $id]);

    return $pdoSt->fetch();
}

function AllWithTeams(\PDO $connection): array
{
    $matchesInfosRequest = 'SELECT * FROM matches JOIN participations p on matches.id = p.match_id JOIN teams t on p.team_id = t.id ORDER BY matches.id, is_home;';
    $pdoSt = $connection->query($matchesInfosRequest);
    return $pdoSt->fetchAll();
}

function AllWithTeamsGrouped($allWithTeams): array
{
    $m = new \stdClass();
    $matchesWithTeams = [];
    foreach ($allWithTeams as $match) {
        if (!$match->is_home) {
            $m = new \StdClass();
            $d = new \DateTime();
            $d->setTimestamp(((int)$match->date / 1000));
            $m->match_date = $d;
            $m->away_team = $match->name;
            $m->away_team_goals = $match->goals;
        } else {
            $m->home_team = $match->name;
            $m->home_team_goals = $match->goals;
            $matchesWithTeams[] = $m;
        }
    }
    return $matchesWithTeams;
}

function save(\PDO $connection, array $match): bool
{
    $insertMatchRequest = "INSERT INTO matches (`date`,`slug`) VALUES (:date,:slug)";
    $pdoSt = $connection->prepare($insertMatchRequest);
    $pdoSt->execute([':date' => $match["date"], ':slug' => '']);
    $id = $connection->lastInsertId();

    //Insertion pour les participations
    $insertParticipationRequest = "INSERT INTO participations (`match_id`,`team_id`,`goals`,`is_home`) VALUES (:match_id,:team_id,:goals,:is_home)";

    //EXECUTION POUR LA HOME TEAM
    $pdoSt = $connection->prepare($insertParticipationRequest);
    $pdoSt->execute([
        ':match_id' => $id,
        ':team_id' => $match['home-team'],
        ':goals' => $match['home-team-goals'],
        ':is_home' => 1,
    ]);

    //EXECUTION POUR LA AWAY TEAM
    $pdoSt = $connection->prepare($insertParticipationRequest);
    return $pdoSt->execute([
        ':match_id' => $id,
        ':team_id' => $match['away-team'],
        ':goals' => $match['away-team-goals'],
        ':is_home' => 0,
    ]);
}
