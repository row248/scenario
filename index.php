<?php session_start() ?>
<!DOCTYPE html>
<html>
<head>
    <title>Главная страница</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body class="index">
    <?php require 'config.php' ?>
    <?php require 'nav-menu.php' ?>
    <?php drawNavBar() ?>
    <?php var_dump($_SERVER) ?>
</body>
</html>
