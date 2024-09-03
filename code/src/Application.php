<?php

namespace Geekbrains\Application1;

final class Application
{

    private const APP_NAMESPACE = 'Geekbrains\Application1\Controllers\\';

    private string $controllerName;
    private string $methodName;
    private static array $config;

    public static function config(): array
    {
        return Application::$config;
    }

    public function run(): string
    {
        Application::$config = parse_ini_file('config.ini', true);

        // разбиваем адрес по символу слэша
        $routeArray = explode('/', $_SERVER['REQUEST_URI']);

        // если у нас url localhost/user/index, то в [0] идет localhost, [1] - user, [2] - index

        if (isset($routeArray[1]) && $routeArray[1] != '') {
            // если значения пользователь задал, то
            $controllerName = $routeArray[1];
        } else {
            // по умолчанию будет Controller Page и метод index (см. строку 53)
            $controllerName = "page";
        }

        // определяем имя контроллера

        $this->controllerName = Application::APP_NAMESPACE . ucfirst($controllerName) . "Controller"; // - сборка имени контроллера
        // ucfirst($controllerName) - делает первую букву заглавной

        // проверяем контроллер(по сути класс к которому мы на самом деле обращаемся) на существование

        if (class_exists($this->controllerName)) {
            // пытаемся вызвать метод
            
            if (isset($routeArray[2]) && $routeArray[2] != '') {

                $methodName = $routeArray[2];
            } else {
                $methodName = "index";
                // - тут какой будет значение по умолчанию если после localhost ничего не написано
            }
            // - если все впорядке то собираем имя метода, который будет использоваться в наших контроллерах
            
            $this->methodName = "action" . ucfirst($methodName);

            // проверяем метод на существование
            if (method_exists($this->controllerName, $this->methodName)) {
                // создаем экземепляр контроллера, если класс существует

                $controllerInstance = new $this->controllerName();

                $method = $this->methodName;
                return $controllerInstance->$method();

            } // вызываем метод если он существует
            else {
                header(404);
                header("Location: /error_page.html");
                die();
            }
        } else {
            header(404);
            header("Location: /error_page.html");
            die();
        }
    }

}