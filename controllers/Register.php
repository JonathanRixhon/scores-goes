<?php

namespace Controllers;

use Models\User;

class Register
{
    public function create()
    {
        $view = './views/register/create.php';
        return compact('view');
    }
    public function store()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $userModel = new User;
        $userModel->save(compact('name', 'email', 'password'));
        header('Location: index.php');
        exit();
    }
}
