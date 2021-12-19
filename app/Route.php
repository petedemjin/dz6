<?php


namespace App;

//Раскладывает $_SERVER['REQUEST_URI'] и выдает название Класса и Метода
class Route
{
    private $controller;
    private $action;

    public function dispatch($uri)
    {
        if($uri == '/'){
            $this->action ='Index';
            $nameController = '\\App\\Controller\\Login';
            $this->controller = new $nameController;
            return;
        }

        $parse = parse_url($uri);
        $parts = explode('/',$parse['path']);
        $files = scandir(__DIR__ . '/Controller/');

        if(empty($parts[1])){
            return false;
        }
        if(!array_search(ucfirst(strtolower($parts[1])) . '.php', $files)){
            throw new Error404Exception();
        }

        $nameController = 'App\\Controller\\' . ucfirst(strtolower($parts[1]));
        //var_dump($nameController);die();
        $this->controller = new $nameController;
        $this->action = $parts[2] ?? 'Index';
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }
}