<?php
spl_autoload_register(function ($name){
    include __DIR__ . '/../' . $name . '.php';
}
);
