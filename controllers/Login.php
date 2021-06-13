<?php

namespace Controllers;

class Login
{
    function create()
    {
        $view = './views/login/create.php';
        return compact('view');
    }

    public function check()
    {
        // Collecte des données
        $email = $_POST['email'];
        $password = $_POST['password'];

        //Identification
        $userModel = new \Models\User();
        $user = $userModel->find($email);

        //Authentification
        if (password_verify($password, $user->password)) {
            //connecter l'utilisateur au site
            $_SESSION['user'] = $user;
            header('Location: index.php');
        } else {
            header('Location: index.php?action=view&ressource=login-form');
        }
        exit();
    }

    public function delete()
    {
        session_start();
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
        header('Location: index.php');
    }
}
