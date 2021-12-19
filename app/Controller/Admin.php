<?php


namespace App\Controller;

use App\AbstractController;
use App\Model\Message;
use App\Model\User;


class Admin extends AbstractController
{
    public function index()
    {
        return $this->view->render(
            'admin/users.phtml',
            [
                'users' => User::getList()
            ]
        );
    }

    public function preDispatch()
    {
        parent::preDispatch();
        if(!$this->getUser() || !$this->getUser()->isAdmin()) {
            $this->redirect('/');
        }
    }

    public function deleteMessage()
    {
        $messageId = (int) $_GET['id'];
        Message::deleteMessage($messageId);
        $this->redirect('/blog');
    }

    public function saveUser()
    {
        $id = (int) $_POST['id'];
        $fio = (string) $_POST['fio'];
        $email = (string) $_POST['email'];
        $password = (string) $_POST['password'];

        $user = User::getById($id);
        if (!$user) {
            return $this->response(['error' => 'no user']);
        }

        if (!$fio) {
            return $this->response(['error' => 'no fio']);
        }

        if (!$email) {
            return $this->response(['error' => 'no email']);
        }

        if ($password && mb_strlen($password) < 5) {
            return $this->response(['error' => 'too short password']);
        }

        $user->fio = $fio;
        $user->email = $email;

        /** @var User $emailUser */
        $emailUser = User::getByEmail($email);
        if ($emailUser && $emailUser->id != $id) {
            return $this->response(['error' => 'this email already userd by uid#' . $emailUser->id]);
        }

        if ($password) {
            $user->password = User::getPasswordHash($password);
        }
        $user->save();

        return $this->response(['result' => 'ok']);

    }

    public function deleteUser()
    {
        $id = (int) $_POST['id'];

        $user = User::getById($id);
        if (!$user) {
            return $this->response(['error' => 'no user']);
        }

        $user->delete();

        return $this->response(['result' => 'ok']);
    }

    public function addUser()
    {
        $fio = (string) $_POST['fio'];
        $email = (string) $_POST['email'];
        $password = (string) $_POST['password'];

        if (!$fio || !$password) {
            return 'Не заданы имя и пароль';
        }

        if (!$fio) {
            return $this->response(['error' => 'no fio']);
        }

        if (!$email) {
            return $this->response(['error' => 'no email']);
        }

        if (!$password || mb_strlen($password) < 5) {
            return $this->response(['error' => 'too short password']);
        }

        /** @var User $emailUser */
        $emailUser = User::getByEmail($email);
        if ($emailUser) {
            return $this->response(['error' => 'this email already userd by uid#' . $emailUser->id]);
        }

        $userData = [
            'fio' => $fio,
            'created_at' => date('Y-m-d H:i:s'),
            'password' => User::getPasswordHash($password),
            'email' => $email,
        ];
        $user = new User($userData);
        $user->save();

        return $this->response(['result' => 'ok']);

    }

    public function response(array $data)
    {
        header('Content-type: application/json');
        return json_encode($data);
    }
}