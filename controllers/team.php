<?php

namespace Controllers\Team;

use function Models\Team\save as saveTeam;

function store(\PDO $pdo)
{
    //récupération depuis la requête
    $name = $_POST['name'];
    $slug = $_POST['slug'];
    //TODO: il manque de la validation 
    //création de l'array
    $team = compact('name', 'slug');
    //ajouter dans la db
    saveTeam($pdo, $team);
    header('Location: index.php');
    exit();
}
