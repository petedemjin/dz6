<?php

namespace App\Controller;

use App\AbstractController;
use App\Model\User;

class Login extends AbstractController
{
    public function index()
    {
        if ($this->getUser()) {
            $this->redirect('/blog');
        }
        return $this->view->render(
            'login.phtml',
            [
                'title' => 'Главная',
                'user' => $this->getUser(),
            ]
        );
    }

    public function auth()
    {
        $email = (string) $_POST['email'];
        $password = (string) $_POST['password'];

        $user = User::getByEmail($email);
        if (!$user) {
            return 'Неверный логин и пароль';
        }

        if ($user->getPassword() !== User::getPasswordHash($password)) { //User::getPasswordHash($password))
            return 'Неверный логин и пароль!';
        }

        $this->session->authUser($user->getId());

        $this->redirect('/blog');
    }

    public function register()
    {
        $fio = (string) $_POST['fio'];
        $email = (string) $_POST['email'];
        $password = (string) $_POST['password'];
        $password2 = (string) $_POST['password_2'];

        if (!$fio || !$password) {
            return 'Не заданы имя и пароль';
        }

        if (!$email) {
            return 'Не задан email';
        }

        if ($password !== $password2) {
            return 'Введенные пароли не совпадают';
        }

        if (mb_strlen($password) < 5) {
            return 'Пароль слишком короткий';
        }

        $userData = [
            'fio' => $fio,
            'created_at' => date('Y-m-d H:i:s'),
            'password' => User::getPasswordHash($password),
            'email' => $email,
        ];

        $user = new \App\Model\User($userData);
        $user->save();

        $this->session->authUser($user->getId());
        $this->redirect('/blog');
    }
}