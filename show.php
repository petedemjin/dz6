<?php
require __DIR__ . '/vendor/autoload.php';

$img = new \App\Controller\Image();

$img->imageAction();

echo $img->imageAction();

