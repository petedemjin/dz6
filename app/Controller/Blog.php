<?php

namespace App\Controller;

use App\AbstractController;
use App\Model\Message;

class Blog extends AbstractController
{
    public function index()
    {
        if (!$this->getUser()) {
            $this->redirect('/login');
        }
        $messages = Message::getList();

        return $this->view->render('blog.phtml', [
            'messages' => $messages,
            'user' => $this->getUser()
        ]);
    }

    public function addMessage()
    {
        if (!$this->getUser()) {
            $this->redirect('/login');
        }

        $content = (string) $_POST['content'];
        if (!$content) {
            $this->error('Сообщение не может быть пустым');
        }

        $message = new Message([
            'content' => $content,
            'author_id' => $this->getUserId(),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        if (isset($_FILES['image']['tmp_name'])) {
            $message->loadFile($_FILES['image']['tmp_name']);
        }
        $message->save();
        $this->redirect('/blog');

    }

    public function twig()
    {
        return $this->view->renderTwig('test.twig', ['var' => 'Hello, world!!!']);
    }

}