<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.10.2017
 * Time: 19:46
 */
class Constants
{
    static $ROOT_PATH = "D:/Projects/WEB/students_control/";
    static $DB_HOST_NAME = "localhost";
    static $DB_LOGIN = "root";
    static $DB_PASSWORD = "root";
    static $DB_NAME = "students_control";
    static $DB_TABLE_STUDENTS = "students";
    static $DB_TABLE_TEACHERS = "teachers";
    static $DB_TABLE_GROUPS = "groups";


    static $INCORRECT_LOGIN_MESSAGE = "Неверный логин";
    static $INCORRECT_PASSWORD_MESSAGE = "Неверный пароль";

    static function random_string($length, $chartypes = "numbers,lower")
    {
        $chartypes_array=explode(",", $chartypes);
        // задаем строки символов.
        //Здесь вы можете редактировать наборы символов при необходимости
        $lower = 'abcdefghijklmnopqrstuvwxyz'; // lowercase
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // uppercase
        $numbers = '1234567890'; // numbers
        $special = '^@*+-+%()!?'; //special characters
        $chars = "";
        // определяем на основе полученных параметров,
        //из чего будет сгенерирована наша строка.
        if (in_array('all', $chartypes_array)) {
            $chars = $lower . $upper. $numbers . $special;
        } else {
            if(in_array('lower', $chartypes_array))
                $chars = $lower;
            if(in_array('upper', $chartypes_array))
                $chars .= $upper;
            if(in_array('numbers', $chartypes_array))
                $chars .= $numbers;
            if(in_array('special', $chartypes_array))
                $chars .= $special;
        }
        // длина строки с символами
        $chars_length = strlen($chars) - 1;
        // создаем нашу строку,
        //извлекаем из строки $chars символ со случайным
        //номером от 0 до длины самой строки
        $string = $chars{rand(0, $chars_length)};
        // генерируем нашу строку
        for ($i = 1; $i < $length; $i = strlen($string)) {
            // выбираем случайный элемент из строки с допустимыми символами
            $random = $chars{rand(0, $chars_length)};
            // убеждаемся в том, что два символа не будут идти подряд
            if ($random != $string{$i - 1}) $string .= $random;
        }
        // возвращаем результат
        return $string;
    }
    static function authFailed()
    {
        header("Location: index.php");
        exit();
    }
    static function printRusDate($date)
    {
        $retStr = $date->format("d");
        switch ($date->format("m"))
        {
            case '1':
                $month = 'Января';
                break;
            case '2':
                $month = 'Февраля';
                break;
            case '3':
                $month = 'Марта';
                break;
            case '4':
                $month = 'Апреля';
                break;
            case '5':
                $month = 'Мая';
                break;
            case '6':
                $month = 'Июня';
                break;
            case '7':
                $month = 'Июля';
                break;
            case '8':
                $month = 'Августа';
                break;
            case '9':
                $month = 'Сентября';
                break;
            case '10':
                $month = 'Октября';
                break;
            case '11':
                $month = 'Ноября';
                break;
            case '12':
                $month = 'Декабря';
                break;
        }
        $retStr .= " $month";
        return $retStr;
    }

    static function getMonthNameByOffset($monthOffset = 0)
    {
        //setlocale(LC_ALL, 'rus_rus');
        if($monthOffset>=0)
            $tempChar = '+';
        else
            $tempChar = '-';
        $dt = strtotime($tempChar . $monthOffset . " month");

        $monthNum = strftime('%m', $dt);

        switch ($monthNum)
        {
            case 1:
                $month = 'январь';
                break;
            case 2:
                $month = 'февраль';
                break;
            case 3:
                $month = 'март';
                break;
            case 4:
                $month = 'апрель';
                break;
            case 5:
                $month = 'май';
                break;
            case 6:
                $month = 'июнь';
                break;
            case 7:
                $month = 'июль';
                break;
            case 8:
                $month = 'август';
                break;
            case 9:
                $month = 'сентябрь';
                break;
            case 10:
                $month = 'октябрь';
                break;
            case 11:
                $month = 'ноябрь';
                break;
            case 12:
                $month = 'декабрь';
                break;
        }
        return $month;
    }
    static function getYeraNumByOffset($monthOffset = 0)
    {
        //setlocale(LC_ALL, 'rus_rus');
        if ($monthOffset >= 0)
            $tempChar = '+';
        else
            $tempChar = '-';
        $dt = strtotime($tempChar . $monthOffset . " month");

        return strftime('%Y', $dt);
    }
}