<?php

namespace Models;

use Carbon\Carbon;

class Matches extends Model
{
    protected $table = 'matches';
    protected $findKey = "id";

    function all(): array
    {
        $matchesRequest = 'SELECT * FROM matches ORDER BY date';
        $pdoSt = $this->pdo->query($matchesRequest);

        return $pdoSt->fetchAll();
    }



    function allWithTeams(): array
    {
        $matchesInfosRequest = 'SELECT * FROM matches JOIN participations p on matches.id = p.match_id JOIN teams t on p.team_id = t.id ORDER BY matches.id, is_home;';
        $pdoSt = $this->pdo->query($matchesInfosRequest);
        return $pdoSt->fetchAll();
    }

    function allWithTeamsGrouped($allWithTeams): array
    {
        $m = new \stdClass();
        $matchesWithTeams = [];
        foreach ($allWithTeams as $match) {
            if (!$match->is_home) {
                $m = new \StdClass();
                $d = Carbon::createFromTimestamp($match->date / 1000);
                $m->match_date = $d;
                $m->away_team = $match->name;
                $m->away_team_goals = $match->goals;
                $m->away_team_logo = $match->file_name;
            } else {
                $m->home_team = $match->name;
                $m->home_team_goals = $match->goals;
                $m->home_team_logo = $match->file_name;
                $matchesWithTeams[] = $m;
            }
        }
        return $matchesWithTeams;
    }

    function save(array $match): bool
    {
        $insertMatchRequest = "INSERT INTO matches (`date`,`slug`) VALUES (:date,:slug)";
        $pdoSt = $this->pdo->prepare($insertMatchRequest);
        $pdoSt->execute([':date' => $match["date"], ':slug' => '']);
        $id = $this->pdo->lastInsertId();

        //Insertion pour les participations
        $insertParticipationRequest = "INSERT INTO participations (`match_id`,`team_id`,`goals`,`is_home`) VALUES (:match_id,:team_id,:goals,:is_home)";

        //EXECUTION POUR LA HOME TEAM
        $pdoSt = $this->pdo->prepare($insertParticipationRequest);
        $pdoSt->execute([
            ':match_id' => $id,
            ':team_id' => $match['home-team'],
            ':goals' => $match['home-team-goals'],
            ':is_home' => 1,
        ]);

        //EXECUTION POUR LA AWAY TEAM
        $pdoSt = $this->pdo->prepare($insertParticipationRequest);
        return $pdoSt->execute([
            ':match_id' => $id,
            ':team_id' => $match['away-team'],
            ':goals' => $match['away-team-goals'],
            ':is_home' => 0,
        ]);
    }
}
