<?php
//définition de l'espace de nom pour que les fonctions puissent avoir le même nom.
namespace Match;

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
    $matchesInfosRequest = 'SELECT * FROM matches JOIN participations p on matches.id = p.match_id JOIN teams t on p.team_id = t.id ORDER BY matches.id;';
    $pdoSt = $connection->query($matchesInfosRequest);
    return $pdoSt->fetchAll();
}
