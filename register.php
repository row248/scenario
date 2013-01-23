<?php

error_reporting(-1);

session_start();

require 'config.php';
require 'nav-menu.php';
require 'includes/functions.php';

/**** CSRF *****/

getToken(); 

/**** CSRF ****/


$loginError = '';
$passwordError1 = '';
$passwordError2 = '';

if ( isset($_POST['submit']) && checkTokens($_POST['csrf_token']) ) {
    isset($_POST['login'])     ? $login     = trim($_POST['login'])      :  $login = '';
    isset($_POST['password1']) ? $password1 = trim($_POST['password1'])  :  $password1 = '';
    isset($_POST['password2']) ? $password2 = trim($_POST['password2'])  :  $password2 = '';

    /* If login already use */

    try {
    $dbh = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);

    $sth = $dbh->prepare('SELECT `login` FROM `users` WHERE `login` = ? ');
    $sth->bindParam(1, $login);
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    $sth->execute();

    $row = $sth->fetch();

    if ($row) {
        $loginError = "Такой логин уже <br> есть в системе";
    }

    }catch (PDOException $e) {
        recordError($e);
        exit();
    }
    


    if ( empty($login) ) {
        $loginError = "Введите логин";
    }

    if ( empty($password1) ) {
        $passwordError1 = "Введите пароль";
    }

    if ( empty($password2) ) {
        $passwordError2 = "Подтвердите пароль";
    }

    if ( !empty($login) && !empty($password1) && !empty($password2) ) {

        if ( !preg_match('!^[a-zA-Z0-9]{4,}$!', $login) ) {
            $loginError = "Некорректный логин";
        }

        if ( !preg_match('![a-zA-Z]+!', $login) ) {
            $loginError = "Некорректный логин";
        }

        if ( !preg_match('!^[a-zA-Z0-9]{5,}$!', $password1) && !preg_match('!^[a-zA-Z0-9]{5,}$!', $password2) ) {
            $passwordError1 = "Неуместный пароль";
        }

        if ( $password1 != $password2 ) {
            $passwordError1 = "Пароли не одинаковы";
        }

        if ( mb_strlen($password1) < 5 ) {
            $passwordError1 = "Неуместный пароль";
        }
            
    }

    /* If all erros empty */

    if ( empty($loginError) && empty($passwordError1) && empty($passwordError2) ) {

        $password1 = md5($password1);
        $right = ACCESS_USER;

        try {

        $sth = $dbh->prepare( "INSERT INTO `users` (`login`, `password`, `right`) VALUES (?, ?, ?) ");
        $sth->bindParam(1, $login);
        $sth->bindParam(2, $password1);
        $sth->bindParam(3, $right);

        $sth->execute();
        } catch (PDOException $e) {

            recordError($e);
            exit();
        }

        header("Location: success-registr.phtml");

    }


} else {
    $login = '';
    $password1 = '';

}

require 'register.phtml';
