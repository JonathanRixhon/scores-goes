<?php

namespace Models;

class Team extends Model
{
    protected $table = 'teams';
    protected $findKey = 'id';

    function all(): array
    {
        $teamsRequest = 'SELECT * FROM teams ORDER BY name';
        $pdoSt = $this->pdo->query($teamsRequest);

        return $pdoSt->fetchAll();
    }

    function findByName(string $name): \stdClass
    {
        $teamRequest = 'SELECT * FROM teams WHERE name = :name';
        $pdoSt = $this->pdo->prepare($teamRequest);
        $pdoSt->execute([':name' => $name]);

        return $pdoSt->fetch();
    }

    function save(array $team): bool
    {
        try {
            $insertTeamRequest = "INSERT INTO teams (`name`,`slug`,`file_name`) VALUES (:name,:slug,:file_name)";
            $pdoSt = $this->pdo->prepare($insertTeamRequest);
            return $pdoSt->execute([
                ':name' => $team["name"],
                ':slug' => $team["slug"],
                ':file_name' => $team["file_name"]
            ]);
        } catch (\PDOException $th) {
            die($th->getMessage());
        }
    }
}
