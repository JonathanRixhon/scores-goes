<?php

namespace Controllers;

use Intervention\Image\Exception\NotWritableException;
use Intervention\Image\ImageManagerStatic;

class Team
{
    function store()
    {
        $teamModel = new \Models\Team();
        //DEBUT VALIDATION
        //NAME
        if (!isset($_POST['name']) || trim($_POST['name'] === '')) {
            $_SESSION['errors']["name"] = "Veuillez entrer un nom d'équipe";
        }

        //SLUG
        if (!isset($_POST['slug']) || trim($_POST['slug'] === '')) {
            $_SESSION['errors']["slug"] = "Veuillez entrer un identifiant";
        } elseif (strlen($_POST['slug']) != 3) {
            $_SESSION['errors']["slug"] = "Veuillez entrer un à 3 lettres";
            $_SESSION['old']['name'] = $_POST['slug'];
        }

        //FILES
        if (!isset($_FILES['logo']['error']) || is_array($_FILES['logo']['error'])) {
            $_SESSION['errors']["logo"] = "Tentative d'attaque, entrez un fichier valide";
            header('Location: index.php?action=team&ressource=create');
            exit();
        }

        switch ($_FILES['logo']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                $_SESSION['errors']["logo"] = "Vous devez fournir une image du logo du club";
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $_SESSION['errors']["logo"] = "Vous avez dépassé la taille maximale autorisée, soit " . ini_get('upload_max_filesize');
                break;
            default:
                $_SESSION['errors']["logo"] = "Erreur inconnue";
        }
        if ($_FILES['logo']['size'] > 32000000) {
            $_SESSION['errors']["logo"] = "Vous avez dépassé la taille maximale autorisée, soit 32M";
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search(
            $finfo->file($_FILES['logo']['tmp_name']),
            array(
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
            ),
            true
        )) {
            $_SESSION['errors']["logo"] = "Le fichier envoyé n'est pas une image";
        }

        //FIN VALIDATION
        $full_file_path = './assets/images/full/';
        $thumbs_file_path = './assets/images/thumbs/';
        $file_name = sprintf('%s.%s', sha1_file($_FILES['logo']['tmp_name']), $ext);


        // GÉRÉR L'IMAGE
        ImageManagerStatic::configure();
        $image = ImageManagerStatic::make($_FILES['logo']['tmp_name']);
        if (
            ($image->width() >= 400 && $image->width() <= 1600) &&
            ($image->height() >= 400 && $image->height() <= 1600)
        ) {

            $image->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
            });


            $thumb = ImageManagerStatic::make($_FILES['logo']['tmp_name']);
            $thumb->resize(60, 60, function ($constraint) {
                $constraint->aspectRatio();
            });
            try {
                $image->save($full_file_path . $file_name);
                $thumb->save($thumbs_file_path . $file_name);
            } catch (NotWritableException $e) {
                $_SESSION['errors']["logo"] = "Le fichier n'a pas pu être enregistré sur le serveur.";
            }
        } else {
            $_SESSION['errors']["logo"] = "Le fichier que vous avez fourni ne respecte pas les contraintes de taille.";
        }



        //récupération depuis la requête
        $name = $_POST['name'];
        $slug = $_POST['slug'];
        //création de l'array
        $team = compact('name', 'slug', 'file_name');
        //ajouter dans la db
        if (!$_SESSION['errors']) {
            $teamModel->save($team);
            header('Location: index.php');
            exit();
        }
        $_SESSION['old']['name'] = $_POST['name'];
        $_SESSION['old']['slug'] = $_POST['slug'];
        header('Location: index.php?action=create&ressource=team');
        exit();
    }

    function create()
    {
        $view = './views/team/create.php';
        return compact('view');
    }
}
