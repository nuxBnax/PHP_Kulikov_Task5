<?php

namespace Geekbrains\Application1\Models;

class User
{

    private ?string $userName; // ?string  это тоже самое что string|null, т.е. после может быть строкой или null
    private ?int $userBirthday;
    private string $strBirthday;

    private static string $storageAddress = '/storage/birthdays.txt';

    public function __construct(string $name = null, int $birthday = null)
    {   // ?string если в 9 строке не было бы ?, то тут была бы ошика типа
        $this->userName = $name;
        $this->userBirthday = $birthday;
    }

    public function setName(string $userName): void
    {
        $this->userName = $userName;
    }
    public function setBirthday(string $birthdayString): void
    {
        $this->strBirthday = $birthdayString;
    }
    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getUserBirthday(): int
    {
        return $this->userBirthday;
    }
    public function getUserStringBirthday(): string
    {
        return $this->strBirthday;
    }

    public function setBirthdayFromString(string $birthdayString): void
    {
        $this->userBirthday = strtotime($birthdayString);
    }

    public static function getAllUsersFromStorage(): array|false
    {
        $address = $_SERVER['DOCUMENT_ROOT'] . User::$storageAddress;
        if (file_exists($address) && is_readable($address)) {
            $file = fopen($address, "r");
            $users = [];
            while (!feof($file)) {
                $userString = fgets($file);
                $userArray = explode(",", $userString);
                $user = new User(
                    $userArray[0]
                );
                $user->setBirthdayFromString($userArray[1]);
                $users[] = $user;
            }
            fclose($file);
            // echo "<pre>";
            // var_dump($users);
            return $users;
        } else {
            return false;
        }
    }
    public static function setUserInfo()
    {
        $address = $_SERVER['DOCUMENT_ROOT'] . User::$storageAddress;
        $user = new User();
        $user->setName($_GET['name'] ?? '');
        $user->setBirthday($_GET['birthday'] ?? '');
        $userInfo = $user->getUserName() . ", " . $user->getUserStringBirthday();

        
        // echo "<pre>";
        // var_dump($users);
        $newData = '';
        
        if (file_exists($address) && is_readable($address)) {
            $file = fopen($address, "rb");
            while (!feof($file)) {
                $contents = fgets($file, 100);
                $newData .= $contents;
            }
            $newData = $newData . PHP_EOL . $userInfo; 

            $fileHandler = fopen($address, 'w');
            if (fwrite($fileHandler, $newData)) {
                return true;
            } else {
                return false;
            }

        } else {
            return "Файл не существует";
        }

    }
}