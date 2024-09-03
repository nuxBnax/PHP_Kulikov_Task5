<?php

namespace Geekbrains\Application1\Controllers;
use Geekbrains\Application1\Application;
use Geekbrains\Application1\Render;

class PageController {

    public function actionIndex() {
        $render = new Render();
        // echo Application::config()['storage']['address']; // просто выводит значение из config.ini (address = code/storage/birthdays.txt)
        return $render->renderPage('page-index.twig', ['title' => 'Главная страница']);
    }
}